<?php

declare(strict_types=1);

namespace Drupal\helfi_gdpr_api\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityBase;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultAllowed;
use Drupal\Core\Access\AccessResultForbidden;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Http\RequestStack;
use Drupal\Core\Language\ContextProvider\CurrentLanguageContext;
use Drupal\helfi_atv\AtvAuthFailedException;
use Drupal\helfi_atv\AtvDocumentNotFoundException;
use Drupal\helfi_atv\AtvFailedToConnectException;
use Drupal\helfi_atv\AtvService;
use Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData;
use Drupal\helfi_helsinki_profiili\TokenExpiredException;
use Drupal\user\Entity\User;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Carbon\Carbon;

/**
 * Returns responses for helfi_gdpr_api routes.
 */
class HelfiGdprApiController extends ControllerBase {

  /**
   * Profiili data access.
   *
   * @var \Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData
   */
  protected HelsinkiProfiiliUserData $helsinkiProfiiliUserData;

  /**
   * Request stack.
   *
   * @var \Drupal\Core\Http\RequestStack
   */
  protected RequestStack $request;

  /**
   * User jwt token decoded.
   *
   * @var array
   */
  protected array $jwtData;

  /**
   * User jwt token string.
   *
   * @var string
   */
  protected string $jwtToken;

  /**
   * Access to ATV.
   *
   * @var \Drupal\helfi_atv\AtvService
   */
  protected AtvService $atvService;

  /**
   * Http client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected ClientInterface $httpClient;

  /**
   * Translator for texts.
   *
   * @var \Drupal\Core\Language\ContextProvider\CurrentLanguageContext
   */
  protected CurrentLanguageContext $currentLanguageContext;

  /**
   * Db connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected Connection $connection;

  /**
   * Audience configuration from db.
   *
   * @var array|mixed|null
   */
  protected array $audienceConfig;

  /**
   * DEbug or not?
   *
   * @var bool
   */
  protected bool $debug;

  /**
   * Is debug on?
   *
   * @return bool
   *   Debug on / off?
   */
  public function isDebug(): bool {
    return $this->debug;
  }

  /**
   * Set debug value.
   *
   * @param bool $debug
   *   True / False?
   */
  public function setDebug(bool $debug): void {
    $this->debug = $debug;
  }

  /**
   * CompanyController constructor.
   */
  public function __construct(
    RequestStack $request,
    HelsinkiProfiiliUserData $helsinkiProfiiliUserData,
    AtvService $atvService,
    ClientInterface $http_client,
    CurrentLanguageContext $currentLanguageContext,
    Connection $connection
  ) {
    $this->request = $request;
    $this->helsinkiProfiiliUserData = $helsinkiProfiiliUserData;
    $this->atvService = $atvService;
    $this->httpClient = $http_client;
    $this->currentLanguageContext = $currentLanguageContext;
    $this->connection = $connection;

    $this->audienceConfig = $this->config('helfi_gdpr_api.settings')
      ->get('audience_config');

    $this->setDebug(getenv('DEBUG') == 'true' || getenv('DEBUG') == TRUE);
    $this->parseJwt();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack'),
      $container->get('helfi_helsinki_profiili.userdata'),
      $container->get('helfi_atv.atv_service'),
      $container->get('http_client'),
      $container->get('language.current_language_context'),
      $container->get('database')
    );
  }

  /**
   * Builds the response.
   */
  public function get($userId) {

    // Decode the json data.
    try {
      $data = $this->getData();
    }
    catch (AtvDocumentNotFoundException $e) {
      return new JsonResponse(NULL, 404);
    }
    catch (AtvFailedToConnectException $e) {
      return new JsonResponse(NULL, 500);
    }
    catch (TokenExpiredException $e) {
      return new JsonResponse(NULL, 401);
    }
    catch (GuzzleException $e) {
      return new JsonResponse(NULL, 500);
    }

    if (empty($data)) {
      return new JsonResponse(NULL, 404);
    }

    return new JsonResponse($data);

  }

  /**
   * Builds the response.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JsonResponse.
   */
  public function delete($userId) {

    try {
      $user = $this->getUser();
      $user->delete();

      $this->atvService->deleteGdprData($this->jwtData['sub']);

    }
    catch (AtvDocumentNotFoundException $e) {
      return new JsonResponse(NULL, 404);
    }
    catch (AtvFailedToConnectException $e) {
      return new JsonResponse(NULL, 500);
    }
    catch (TokenExpiredException $e) {
      return new JsonResponse(NULL, 401);
    }
    catch (GuzzleException $e) {
      return new JsonResponse(NULL, 500);
    }
    catch (EntityStorageException $e) {
      return new JsonResponse(NULL, 404);
    }
    catch (AtvAuthFailedException $e) {
      return new JsonResponse(NULL, 403);
    }

    return new JsonResponse(NULL, 204);

  }

  /**
   * Checks access for this controller.
   */
  public function access($userId): AccessResultForbidden|AccessResultAllowed {

    $this->debug('GDPR Api access called. JWT token: @token', ['@token' => $this->jwtToken]);

    if (!$this->helsinkiProfiiliUserData->verifyJwtToken($this->jwtToken)) {
      return AccessResult::forbidden('Token verfication failed.');
    }

    // If audience does not match, forbid access.
    if ($this->jwtData['aud'] != $this->audienceConfig["audience_host"] . '/' . $this->audienceConfig["service_name"]) {
      $this->debug(
        'Access DENIED. Reason: @reason. JWT token: @token',
        [
          '@token' => $this->jwtToken,
          '@reason' => 'Audience mismatch',
        ]);
      return AccessResult::forbidden('Audience mismatch');
    }

    // Check the expiration time - note this will cause an error if there
    // is no 'exp' claim in the token.
    $expiration = Carbon::createFromTimestamp($this->jwtData['exp']);
    $tokenExpired = (Carbon::now()->diffInSeconds($expiration, FALSE) < 0);

    if ($tokenExpired) {
      $this->debug(
        'Local access DENIED. Reason: @reason. JWT token: @token',
        [
          '@token' => $this->jwtToken,
          '@reason' => 'Token expired',
        ]);
      return AccessResult::forbidden('Token expired.');
    }

    $hostkey = 'asdf';
    if ($this->request->getCurrentRequest()->getMethod() == 'GET') {

      // Set hostname for get requests.
      if (isset($this->jwtData[$this->audienceConfig["audience_host"]])) {
        $hostkey = $this->audienceConfig["service_name"] . '.gdprquery';
      }
      else {
        $this->debug(
          'Local access DENIED. Reason: @reason. JWT token: @token',
          [
            '@token' => $this->jwtToken,
            '@config' => Json::encode($this->audienceConfig),
            '@reason' => 'Incorrect scope',
          ]);
        // If no host/scope setting in jwt data, forbid access.
        return AccessResult::forbidden('Incorrect scope');
      }
    }
    if ($this->request->getCurrentRequest()->getMethod() == 'DELETE') {
      // Same with delete requests, but key used is different.
      if (isset($this->jwtData[$this->audienceConfig["audience_host"]])) {
        $hostkey = $this->audienceConfig["service_name"] . '.gdprdelete';
      }
      else {
        $this->debug(
          'Local access DENIED. Reason: @reason. JWT token: @token',
          [
            '@token' => $this->jwtToken,
            '@reason' => 'Incorrect scope',
          ]);
        return AccessResult::forbidden('Incorrect scope');
      }
    }

    if ($this->jwtData[$this->audienceConfig["audience_host"]][0] == $hostkey) {
      $this->debug(
        'Local access GRANTED. Reason: @reason. JWT token: @token',
        [
          '@token' => $this->jwtToken,
          '@reason' => 'All match..',
        ]);
      return AccessResult::allowed();
    }

    $this->debug(
      'Local access DENIED. Reason: @reason. JWT token: @token',
      [
        '@token' => $this->jwtToken,
        '@reason' => 'Last forbinned.',
      ]);
    return AccessResult::forbidden('Authentication failed.');
  }

  /**
   * Parse jwt token data from token in request.
   */
  public function parseJwt(): void {

    $currentRequest = $this->request->getCurrentRequest();
    $jwtToken = str_replace('Bearer ', '', $currentRequest->headers->get('authorization'));
    $tokenData = $this->helsinkiProfiiliUserData->parseToken($jwtToken);
    $this->jwtData = $tokenData;
    $this->jwtToken = $jwtToken;
  }

  /**
   * Get user GDPR data from ATV api.
   *
   * @return array
   *   User's GDPR data
   *
   * @throws \Drupal\helfi_atv\AtvDocumentNotFoundException
   * @throws \Drupal\helfi_atv\AtvFailedToConnectException
   * @throws \Drupal\helfi_helsinki_profiili\TokenExpiredException
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getData(): array {

    $data = [];

    $user = $this->getUser();

    // If we have user, then add user data.
    if ($user) {
      $data[0] = [
        'key' => 'GRANT_APPLICATIONS_USER',
        'label' => [
          'en' => 'Grant applications user',
          'fi' => $this->t('Grant applications user', [], ['langcode' => 'fi'])
            ->render(),
          'sv' => $this->t('Grant applications user', [], ['langcode' => 'sv'])
            ->render(),
        ],
        'children' => [
          [
            'key' => 'USER_ID',
            'label' => [
              'en' => 'User ID',
              'fi' => $this->t('User ID', [], ['langcode' => 'fi'])->render(),
              'sv' => $this->t('User ID', [], ['langcode' => 'sv'])->render(),
            ],
            'value' => $this->jwtData['sub'],
          ],
          [
            'key' => 'USERNAME',
            'label' => [
              'en' => 'Username',
              'fi' => $this->t('Username', [], ['langcode' => 'fi'])->render(),
              'sv' => $this->t('Username', [], ['langcode' => 'sv'])->render(),
            ],
            'value' => $user->getDisplayName(),
          ],
          [
            'key' => 'MAIL',
            'label' => [
              'en' => 'Email address',
              'fi' => $this->t('Email address', [], ['langcode' => 'fi'])
                ->render(),
              'sv' => $this->t('Email address', [], ['langcode' => 'sv'])
                ->render(),
            ],
            'value' => $user->getEmail(),
          ],
          [
            'key' => 'CREATED',
            'label' => [
              'en' => 'User created',
              'fi' => $this->t('User created', [], ['langcode' => 'fi'])
                ->render(),
              'sv' => $this->t('User created', [], ['langcode' => 'sv'])
                ->render(),
            ],
            'value' => $user->getCreatedTime(),
          ],
          [
            'key' => 'CHANGED',
            'label' => [
              'en' => 'User updated',
              'fi' => $this->t('User updated', [], ['langcode' => 'fi'])
                ->render(),
              'sv' => $this->t('User updated', [], ['langcode' => 'sv'])
                ->render(),
            ],
            'value' => $user->getChangedTime(),
          ],
        ],
      ];
    }

    // Get data.
    $gdprData = $this->atvService->getGdprData($this->jwtData['sub']);
    if ($gdprData["total_deletable"] == 0 && $gdprData["total_undeletable"] == 0) {
      return [];
    }

    // If we have data, then parse it.
    if ($gdprData) {

      $data[1] = [
        'key' => 'GRANT_APPLICATIONS',
        'label' => [
          'en' => 'Grant applications',
          'fi' => $this->t('Grant applications', [], ['langcode' => 'fi'])
            ->render(),
          'sv' => $this->t('Grant applications', [], ['langcode' => 'sv'])
            ->render(),
        ],
      ];

      foreach ($gdprData['documents'] as $metadoc) {
        $data[1]['children'][] = [
          [
            'key' => 'ID',
            'value' => $metadoc['id'],
            'formatting' => [
              'datatype' => 'string',
            ],
            'label' => [
              'en' => 'Document identifier',
              'fi' => $this->t('Document identifier', [], ['langcode' => 'fi'])
                ->render(),
              'sv' => $this->t('Document identifier', [], ['langcode' => 'sv'])
                ->render(),
            ],
          ],
          [
            'key' => 'CREATED_AT',
            'value' => $metadoc['created_at'],
            'formatting' => [
              'datatype' => 'date',
            ],
            'label' => [
              'en' => 'Document creation time',
              'fi' => $this->t('Document creation time', [], ['langcode' => 'fi'])
                ->render(),
              'sv' => $this->t('Document creation time', [], ['langcode' => 'sv'])
                ->render(),
            ],
          ],
          [
            'key' => 'USER_ID',
            'value' => $metadoc['user_id'],
            'formatting' => [
              'datatype' => 'string',
            ],
            'label' => [
              'en' => 'Document owner ID',
              'fi' => $this->t('Document owner ID', [], ['langcode' => 'fi'])
                ->render(),
              'sv' => $this->t('Document owner ID', [], ['langcode' => 'sv'])
                ->render(),
            ],
          ],
          [
            'key' => 'TYPE',
            'value' => $metadoc['type'],
            'formatting' => [
              'datatype' => 'string',
            ],
            'label' => [
              'en' => 'Document type',
              'fi' => $this->t('Document type', [], ['langcode' => 'fi'])
                ->render(),
              'sv' => $this->t('Document type', [], ['langcode' => 'sv'])
                ->render(),
            ],
          ],
          [
            'key' => 'DELETABLE',
            'value' => $metadoc['deletable'] ? 1 : 0,
            'formatting' => [
              'datatype' => 'integer',
            ],
            'label' => [
              'en' => 'Document deletable',
              'fi' => $this->t('Document deletable', [], ['langcode' => 'fi'])
                ->render(),
              'sv' => $this->t('Document deletable', [], ['langcode' => 'sv'])
                ->render(),
            ],
          ],
          [
            'key' => 'ATTACHMENT_COUNT',
            'value' => $metadoc['attachment_count'],
            'formatting' => [
              'datatype' => 'integer',
            ],
            'label' => [
              'en' => 'Document type',
              'fi' => $this->t('Document type', [], ['langcode' => 'fi'])
                ->render(),
              'sv' => $this->t('Document type', [], ['langcode' => 'sv'])
                ->render(),
            ],
          ],
        ];
      }
    }

    return $data;
  }

  /**
   * Get user from database.
   *
   * @return \Drupal\Core\Entity\EntityBase|\Drupal\Core\Entity\EntityInterface|\Drupal\user\Entity\User|null
   *   User or some other types.
   */
  public function getUser(): User|EntityBase|EntityInterface|null {
    $query = $this->connection->select('users', 'u',);
    $query->join('authmap', 'am', 'am.uid = u.uid');
    $query
      ->fields('u', ['uid'])
      ->condition('am.authname', $this->jwtData['sub']);
    $res = $query->execute()->fetchObject();

    $user = User::load($res->uid);
    return $user;
  }

  /**
   * Print to debug stream.
   *
   * @param string $msg
   *   Message.
   * @param array $options
   *   Options.
   */
  private function debug(string $msg, array $options = []) {
    if ($this->isDebug()) {
      $this->getLogger('helf_gdpr_api')->debug($msg, $options);
    }
  }

}
