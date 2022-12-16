<?php

namespace Drupal\helfi_gdpr_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Http\RequestStack;
use Drupal\helfi_atv\AtvService;
use Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for helfi_gdpr_api routes.
 */
class TestGdprApi extends ControllerBase {

  protected HelsinkiProfiiliUserData $helsinkiProfiiliUserData;

  protected RequestStack $request;

  protected AtvService $atvService;

  protected ClientInterface $httpClient;

  /**
   * CompanyController constructor.
   */
  public function __construct(
    RequestStack $request,
    HelsinkiProfiiliUserData $helsinkiProfiiliUserData,
    AtvService $atvService,
    ClientInterface $http_client,
  ) {
    $this->request = $request;
    $this->helsinkiProfiiliUserData = $helsinkiProfiiliUserData;
    $this->atvService = $atvService;
    $this->httpClient = $http_client;
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
    );
  }

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    $jwt = $this->helsinkiProfiiliUserData->getApiAccessTokens();

    foreach ($jwt as $token) {
//      $resp = $this->httpClient->request(
//        'GET',
//        'https://hel-fi-drupal-grant-applications.docker.so/helfi-gdpr-api/endpoint',
//        [
//          'headers' => [
//            'Authorization' => 'Bearer ' . $token,
//          ]
//        ]
//      );
      $d = 'asdf';
    }

    return $build;
  }

}
