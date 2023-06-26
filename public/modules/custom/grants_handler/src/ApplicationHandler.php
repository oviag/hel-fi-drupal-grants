<?php

namespace Drupal\grants_handler;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManager;
use Drupal\Core\Logger\LoggerChannel;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\TypedData\TypedDataInterface;
use Drupal\grants_attachments\AttachmentHandler;
use Drupal\grants_metadata\AtvSchema;
use Drupal\grants_profile\GrantsProfileService;
use Drupal\helfi_atv\AtvDocument;
use Drupal\helfi_atv\AtvDocumentNotFoundException;
use Drupal\helfi_atv\AtvService;
use Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData;
use Drupal\helfi_helsinki_profiili\ProfileDataException;
use Drupal\webform\Entity\Webform;
use Drupal\webform\Entity\WebformSubmission;
use Drupal\webform\WebformSubmissionInterface;
use GuzzleHttp\ClientInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Drupal\grants_mandate\CompanySelectException;

/**
 * ApplicationUploader service.
 */
class ApplicationHandler {

  use StringTranslationTrait;

  /**
   * Name of the table where log entries are stored.
   */
  const TABLE = 'grants_handler_saveids';

  /**
   * Name of the navigation handler.
   */
  const HANDLER_ID = 'application_handler';

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected ClientInterface $httpClient;

  /**
   * The helfi_helsinki_profiili.userdata service.
   *
   * @var \Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData
   */
  protected HelsinkiProfiiliUserData $helfiHelsinkiProfiiliUserdata;

  /**
   * Atv access.
   *
   * @var \Drupal\helfi_atv\AtvService
   */
  protected AtvService $atvService;

  /**
   * Atv data mapper.
   *
   * @var \Drupal\grants_metadata\AtvSchema
   */
  protected AtvSchema $atvSchema;

  /**
   * Grants profile access.
   *
   * @var \Drupal\grants_profile\GrantsProfileService
   */
  protected GrantsProfileService $grantsProfileService;

  /**
   * Holds document fetched from ATV for checks.
   *
   * @var \Drupal\helfi_atv\AtvDocument
   */
  protected AtvDocument $atvDocument;

  /**
   * Logger.
   *
   * @var \Drupal\Core\Logger\LoggerChannel
   */
  protected LoggerChannel $logger;

  /**
   * Show messages.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected MessengerInterface $messenger;

  /**
   * Handle events with applications.
   *
   * @var \Drupal\grants_handler\EventsService
   */
  protected EventsService $eventsService;

  /**
   * Debug status.
   *
   * @var bool
   */
  protected bool $debug;

  /**
   * Endpoint used for integration.
   *
   * @var string
   */
  protected string $endpoint;

  /**
   * Username for REST endpoint.
   *
   * @var string
   */
  protected string $username;

  /**
   * Password for endpoint.
   *
   * @var string
   */
  protected string $password;

  /**
   * New status header text for integration.
   *
   * @var string
   */
  protected string $newStatusHeader;

  /**
   * The database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The Language manager.
   *
   * @var \Drupal\Core\Language\LanguageManager
   */
  protected $languageManager;

  /**
   * Applicationtypes.
   *
   * @var array
   */
  protected static array $applicationTypes;

  /**
   * Application statuses.
   *
   * @var array
   */
  protected static array $applicationStatuses;

  /**
   * Access form errors.
   *
   * @var \Drupal\grants_handler\GrantsHandlerNavigationHelper
   */
  protected GrantsHandlerNavigationHelper $grantsHandlerNavigationHelper;

  /**
   * Constructs an ApplicationUploader object.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client.
   * @param \Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData $helfi_helsinki_profiili_userdata
   *   The helfi_helsinki_profiili.userdata service.
   * @param \Drupal\helfi_atv\AtvService $atvService
   *   Access to ATV.
   * @param \Drupal\grants_metadata\AtvSchema $atvSchema
   *   ATV schema mapper.
   * @param \Drupal\grants_profile\GrantsProfileService $grantsProfileService
   *   Access grants profile data.
   * @param \Drupal\Core\Logger\LoggerChannelFactory $loggerChannelFactory
   *   Logger.
   * @param \Drupal\Core\Messenger\Messenger $messenger
   *   Messenger.
   * @param \Drupal\grants_handler\EventsService $eventsService
   *   Access to events.
   * @param \Drupal\Core\Database\Connection $datababse
   *   Database connection.
   * @param \Drupal\Core\Language\LanguageManager $languageManager
   *   Language manager.
   * @param \Drupal\grants_handler\GrantsHandlerNavigationHelper $grantsFormNavigationHelper
   *   Access error messages.
   */
  public function __construct(
    ClientInterface $http_client,
    HelsinkiProfiiliUserData $helfi_helsinki_profiili_userdata,
    AtvService $atvService,
    AtvSchema $atvSchema,
    GrantsProfileService $grantsProfileService,
    LoggerChannelFactory $loggerChannelFactory,
    Messenger $messenger,
    EventsService $eventsService,
    Connection $datababse,
    LanguageManager $languageManager,
    GrantsHandlerNavigationHelper $grantsFormNavigationHelper
  ) {

    $this->httpClient = $http_client;
    $this->helfiHelsinkiProfiiliUserdata = $helfi_helsinki_profiili_userdata;
    $this->atvService = $atvService;
    $this->atvSchema = $atvSchema;
    $this->grantsProfileService = $grantsProfileService;

    $this->atvSchema->setSchema(getenv('ATV_SCHEMA_PATH'));

    $this->messenger = $messenger;
    $this->logger = $loggerChannelFactory->get('grants_application_handler');
    $this->eventsService = $eventsService;

    $this->endpoint = getenv('AVUSTUS2_ENDPOINT');
    $this->username = getenv('AVUSTUS2_USERNAME');
    $this->password = getenv('AVUSTUS2_PASSWORD');

    $this->newStatusHeader = '';
    $this->database = $datababse;
    $this->languageManager = $languageManager;
    $this->grantsHandlerNavigationHelper = $grantsFormNavigationHelper;
  }

  /*
   * Static methods
   */

  /**
   * Get application types from config.
   *
   * @return array
   *   Application types parsed from active config.
   */
  public static function getApplicationTypes(): array {
    if (!isset(self::$applicationTypes)) {
      $config = \Drupal::config('grants_metadata.settings');
      $thirdPartyOpts = $config->get('third_party_options');
      $applicationTypes = [];
      foreach ((array) $thirdPartyOpts['application_types'] as $applicationTypeId => $config) {
        $tempConfig = $config;
        foreach ($config['labels'] as $lang => $label) {
          $tempConfig[$lang] = $label;
        }
        $tempConfig['applicationTypeId'] = $applicationTypeId;
        $applicationTypes[$config['id']] = $tempConfig;
      }
      self::$applicationTypes = $applicationTypes;
    }

    return self::$applicationTypes;
  }

  /**
   * Get application statuses from config.
   *
   * @return array
   *   Application statuses parsed from active config.
   */
  public static function getApplicationStatuses(): array {
    if (!isset(self::$applicationStatuses)) {
      $config = \Drupal::config('grants_metadata.settings');
      $thirdPartyOpts = $config->get('third_party_options');
      self::$applicationStatuses = (array) $thirdPartyOpts['application_statuses'];
    }

    return self::$applicationStatuses;
  }

  /**
   * Check if given submission status can be set to SUBMITTED.
   *
   * Ie, will submission be sent to Avus2 by integration. Currently only DRAFT
   * -> SUBMITTED is allowed for end user.
   *
   * @param \Drupal\webform\Entity\WebformSubmission|null $submission
   *   Submission in question.
   * @param string|null $status
   *   If no object is available, do text comparison.
   *
   * @return bool
   *   Is submission editable?
   */
  public static function canSubmissionBeSubmitted(?WebformSubmission $submission, ?string $status): bool {
    if (NULL === $submission) {
      $submissionStatus = $status;
    }
    else {
      $data = $submission->getData();
      $submissionStatus = $data['status'];
    }

    if (in_array($submissionStatus, [
      'DRAFT',
    ])) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Check if given submission is allowed to be edited.
   *
   * @param \Drupal\webform\Entity\WebformSubmission|null $submission
   *   Submission in question.
   * @param string $status
   *   If no object is available, do text comparison.
   *
   * @return bool
   *   Is submission editable?
   */
  public static function isSubmissionEditable(?WebformSubmission $submission, string $status = ''): bool {
    if (NULL === $submission) {
      $submissionStatus = $status;
    }
    else {
      $data = $submission->getData();
      $submissionStatus = $data['status'];

    }

    $applicationStatuses = self::getApplicationStatuses();

    if (in_array($submissionStatus, [
      $applicationStatuses['DRAFT'],
      $applicationStatuses['SUBMITTED'],
      $applicationStatuses['SENT'],
      $applicationStatuses['RECEIVED'],
    ])) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Check if given submission is allowed to be edited.
   *
   * @param \Drupal\webform\Entity\WebformSubmission|null $submission
   *   Submission in question.
   * @param string $status
   *   If no object is available, do text comparison.
   *
   * @return bool
   *   Is submission editable?
   */
  public static function isSubmissionFinished(?WebformSubmission $submission, string $status = ''): bool {
    if (NULL === $submission) {
      $submissionStatus = $status;
    }
    else {
      $data = $submission->getData();
      $submissionStatus = $data['status'];
    }

    $applicationStatuses = self::getApplicationStatuses();

    if (in_array($submissionStatus, [
      $applicationStatuses['READY'],
      $applicationStatuses['DONE'],
      $applicationStatuses['DELETED'],
      $applicationStatuses['CANCELED'],
      $applicationStatuses['CANCELLED'],
      $applicationStatuses['CLOSED'],
    ])) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Figure out status for new or updated application submission.
   *
   * @param string $triggeringElement
   *   Element clicked.
   * @param array $form
   *   Form specs.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   State of form.
   * @param array $submittedFormData
   *   Submitted data.
   * @param \Drupal\webform\WebformSubmissionInterface $webform_submission
   *   Submission object.
   *
   * @return string
   *   Status for application, unchanged if no specific update done.
   */
  public function getNewStatus(
    string $triggeringElement,
    array $form,
    FormStateInterface $form_state,
    array $submittedFormData,
    WebformSubmissionInterface $webform_submission
  ): string {

    $applicationStatuses = ApplicationHandler::getApplicationStatuses();

    if ($triggeringElement == '::submitForm') {
      return $applicationStatuses['DRAFT'];
    }

    if ($triggeringElement == '::submit') {
      // Try to update status only if it's allowed.
      if (self::canSubmissionBeSubmitted($webform_submission, NULL)) {
        if (
          $submittedFormData['status'] == 'DRAFT' ||
          !isset($submittedFormData['status']) ||
          $submittedFormData['status'] == '') {
          // If old status is draft or it's not set, we'll update status in
          // document with HEADER as well.
          $this->newStatusHeader = $applicationStatuses['SUBMITTED'];
        }

        return $applicationStatuses['SUBMITTED'];
      }
    }

    // If no other status determined, return existing one without changing.
    // submission should ALWAYS have status set if it's something else
    // than DRAFT.
    return $submittedFormData['status'] ?? $applicationStatuses['DRAFT'];
  }

  /**
   * Check if given submission is allowed to be messaged.
   *
   * @param \Drupal\webform\Entity\WebformSubmission|null $submission
   *   Submission in question.
   * @param string|null $status
   *   If no object is available, do text comparison.
   *
   * @return bool
   *   Is submission editable?
   */
  public static function isSubmissionMessageable(?WebformSubmission $submission, ?string $status): bool {

    if (NULL === $submission) {
      $submissionStatus = $status;
    }
    else {
      $data = $submission->getData();
      $submissionStatus = $data['status'];
    }

    $applicationStatuses = self::getApplicationStatuses();

    if (in_array($submissionStatus, [
      $applicationStatuses['SUBMITTED'],
      $applicationStatuses['SENT'],
      $applicationStatuses['RECEIVED'],
      $applicationStatuses['PENDING'],
      $applicationStatuses['PROCESSING'],
    ])) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * All app envs in array.
   *
   * @return array
   *   Unique environments.
   */
  public static function getAppEnvs() {

    $envs = [
      'DEV',
      'PROD',
      'TEST',
      'STAGE',
      'LOCAL',
      'LOCALJ',
      'LOCALP',
      self::getAppEnv(),
    ];

    return array_unique($envs);
  }

  /**
   * Return Application environment shortcode.
   *
   * If environment is one of the set ones, use those. But if not, use one in
   * .env file.
   *
   * @return string
   *   Shortcode from current environment.
   */
  public static function getAppEnv(): string {
    $appEnv = getenv('APP_ENV');

    if ($appEnv == 'development') {
      $appParam = 'DEV';
    }
    else {
      if ($appEnv == 'production') {
        $appParam = 'PROD';
      }
      else {
        if ($appEnv == 'testing') {
          $appParam = 'TEST';
        }
        else {
          if ($appEnv == 'staging') {
            $appParam = 'STAGE';
          }
          else {
            $appParam = strtoupper($appEnv);
          }
        }
      }
    }
    return $appParam;
  }

  /**
   * Generate application number from submission id.
   *
   * @param \Drupal\webform\Entity\WebformSubmission $submission
   *   Webform data.
   *
   * @return string
   *   Generated number.
   */
  public static function createApplicationNumber(WebformSubmission &$submission): string {
    $appParam = self::getAppEnv();

    $serial = $submission->serial();

    $applicationType = $submission->getWebform()
      ->getThirdPartySetting('grants_metadata', 'applicationType');

    $typeCode = self::getApplicationTypes()[$applicationType]['code'] ?? '';

    if ($appParam == 'PROD') {
      return 'GRANTS-' . $typeCode . '-' . sprintf('%08d', $serial);
    }
    return 'GRANTS-' . $appParam . '-' . $typeCode . '-' . sprintf('%08d', $serial);
  }

  /**
   * Generate next available application number for the submission.
   *
   * @param \Drupal\webform\Entity\WebformSubmission $submission
   *   Webform data.
   *
   * @return string
   *   Generated number.
   *
   * @throws \Drupal\helfi_atv\AtvDocumentNotFoundException
   * @throws \Drupal\helfi_atv\AtvFailedToConnectException
   * @throws \Drupal\helfi_helsinki_profiili\TokenExpiredException
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public static function getAvailableApplicationNumber(WebformSubmission &$submission): string {

    $appParam = self::getAppEnv();
    $serial = $submission->serial();
    $webform_id = $submission->getWebform()->id();
    $lastSerialKey = $webform_id . '_' . $appParam;
    $kvService = \Drupal::service('keyvalue.database');
    $kvStorage = $kvService->get('application_numbers');
    $savedSerial = $kvStorage->get($lastSerialKey);

    if (!empty($submission->getData())) {
      return self::createApplicationNumber($submission);
    }

    if ($savedSerial && $savedSerial > $serial) {
      $serial = $savedSerial;
    }

    /** @var \Drupal\helfi_atv\AtvService $atvService */
    $atvService = \Drupal::service('helfi_atv.atv_service');

    $applicationType = $submission->getWebform()
      ->getThirdPartySetting('grants_metadata', 'applicationType');

    $typeCode = self::getApplicationTypes()[$applicationType]['code'] ?? '';
    $check = TRUE;

    while ($check) {
      $applicationNumber = self::getApplicationNumberInEnvFormat($appParam, $typeCode, $serial);
      $applNumberIsAvailable = $atvService->checkDocumentExistsByTransactionId($applicationNumber);
      if ($applNumberIsAvailable) {
        // Check that there is no local submission with given serial.
        $query = \Drupal::entityQuery('webform_submission')
          ->condition('webform_id', $webform_id)
          ->condition('serial', $serial);
        $results = $query->execute();

        if (empty($results)) {
          $check = FALSE;
        }
        else {
          // Increase serial because we found local a submission.
          $serial++;
        }
      }
      else {
        // No luck, let's check another one.
        $serial++;
      }
    }

    $submission->set('serial', $serial);
    $kvStorage->set($lastSerialKey, $serial);
    return $applicationNumber;
  }

  /**
   * Format application number based by the enviroment.
   */
  private static function getApplicationNumberInEnvFormat($appParam, $typeCode, $serial): string {
    $applicationNumber = 'GRANTS-' . $appParam . '-' . $typeCode . '-' . sprintf('%08d', $serial);

    if ($appParam == 'PROD') {
      $applicationNumber = 'GRANTS-' . $typeCode . '-' . sprintf('%08d', $serial);
    }

    return $applicationNumber;
  }

  /**
   * Extract serial numbor from application number string.
   *
   * @param string $applicationNumber
   *   Application number.
   *
   * @return string
   *   Webform submission serial.
   */
  public static function getSerialFromApplicationNumber(string $applicationNumber): string {
    $exploded = explode('-', $applicationNumber);
    $number = end($exploded);
    return ltrim($number, '0');
  }

  /**
   * Extract webform id from application number string.
   *
   * @param string $applicationNumber
   *   Application number.
   *
   * @return \Drupal\webform\Entity\Webform
   *   Webform object.
   */
  public static function getWebformFromApplicationNumber(string $applicationNumber): Webform {
    // Explode number.
    $exploded = explode('-', $applicationNumber);
    // Get serial.
    array_pop($exploded);
    // Get shortcode.
    $webformShortCode = array_pop($exploded);

    // Load webforms.
    $wids = \Drupal::entityQuery('webform')
      ->execute();
    $webforms = Webform::loadMultiple(array_keys($wids));

    $applicationTypes = self::getApplicationTypes();

    // Look for for application type and return if found.
    $webform = array_filter($webforms, function ($wf) use ($webformShortCode, $applicationTypes) {

      $thirdPartySettings = $wf->getThirdPartySettings('grants_metadata');

      $thisApplicationTypeConfig = array_filter($applicationTypes, function ($appType) use ($thirdPartySettings) {
        if (isset($thirdPartySettings["applicationTypeID"]) &&
          $thirdPartySettings["applicationTypeID"] ===
          (string) $appType["applicationTypeId"]) {
          return TRUE;
        }
        return FALSE;
      });
      $thisApplicationTypeConfig = reset($thisApplicationTypeConfig);

      if (isset($thisApplicationTypeConfig["code"]) && $thisApplicationTypeConfig["code"] == $webformShortCode) {
        return TRUE;
      }
      return FALSE;
    });

    return reset($webform);
  }

  /**
   * Get submission object from local database & fill form data from ATV.
   *
   * Or if local submission is not found, create new and set data.
   *
   * @param string $applicationNumber
   *   String to try and parse submission id from. Ie GRANTS-DEV-00000098.
   * @param \Drupal\helfi_atv\AtvDocument|null $document
   *   Document to extract values from.
   * @param bool $refetch
   *   Force refetch from ATV.
   *
   * @return \Drupal\webform\Entity\WebformSubmission|null
   *   Webform submission.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Core\TempStore\TempStoreException
   * @throws \Drupal\grants_mandate\CompanySelectException
   * @throws \Drupal\helfi_atv\AtvDocumentNotFoundException
   * @throws \Drupal\helfi_atv\AtvFailedToConnectException
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public static function submissionObjectFromApplicationNumber(
    string $applicationNumber,
    AtvDocument $document = NULL,
    bool $refetch = FALSE
  ): ?WebformSubmission {

    $submissionSerial = self::getSerialFromApplicationNumber($applicationNumber);
    $webform = self::getWebformFromApplicationNumber($applicationNumber);

    $result = \Drupal::entityTypeManager()
      ->getStorage('webform_submission')
      ->loadByProperties([
        'serial' => $submissionSerial,
        'webform_id' => $webform->id(),
      ]);

    /** @var \Drupal\helfi_atv\AtvService $atvService */
    $atvService = \Drupal::service('helfi_atv.atv_service');

    /** @var \Drupal\grants_metadata\AtvSchema $atvSchema */
    $atvSchema = \Drupal::service('grants_metadata.atv_schema');

    /** @var \Drupal\grants_profile\GrantsProfileService $grantsProfileService */
    $grantsProfileService = \Drupal::service('grants_profile.service');
    $selectedCompany = $grantsProfileService->getSelectedRoleData();

    // If no company selected, no mandates no access.
    if ($selectedCompany == NULL) {
      throw new CompanySelectException('User not authorised');
    }

    if ($document == NULL) {
      $sParams = [
        'transaction_id' => $applicationNumber,
        'lookfor' => 'appenv:' . ApplicationHandler::getAppEnv(),
      ];

      $document = $atvService->searchDocuments(
        $sParams,
        $refetch
      );
      if (empty($document)) {
        throw new AtvDocumentNotFoundException('Document not found');
      }
      $document = reset($document);
    }

    // If there's no local submission with given serial
    // we can actually create that object on the fly and use that for editing.
    if (empty($result)) {
      $webform = self::getWebformFromApplicationNumber($applicationNumber);
      if ($webform) {
        $submissionObject = WebformSubmission::create(['webform_id' => $webform->id()]);
        $submissionObject->set('serial', $submissionSerial);

        // Lets mark that we don't want to generate new application
        // number, as we just assigned the serial from ATV application id.
        // check GrantsHandler@preSave.
        // @todo notes field handling to separate service etc.
        $customSettings = ['skip_available_number_check' => TRUE];
        $submissionObject->set('notes', serialize($customSettings));
        $submissionObject->save();
      }
    }
    else {
      $submissionObject = reset($result);
    }
    if ($submissionObject) {

      $dataDefinition = self::getDataDefinition($document->getType());

      $sData = $atvSchema->documentContentToTypedData(
        $document->getContent(),
        $dataDefinition,
        $document->getMetadata()
      );

      $sData['messages'] = self::parseMessages($sData);

      // Set submission data from parsed mapper.
      $submissionObject->setData($sData);

      return $submissionObject;
    }
    return NULL;
  }

  /**
   * Extract serial numbor from application number string.
   *
   * @param string $applicationNumber
   *   Application number.
   * @param bool $refetch
   *   Force refetch from ATV.
   *
   * @return Drupal\helfi_atv\AtvDocument
   *   ATV Document
   *
   * @throws \Drupal\helfi_atv\AtvDocumentNotFoundException
   */
  public static function atvDocumentFromApplicationNumber(
    string $applicationNumber,
    bool $refetch = FALSE
  ) {

    $submissionSerial = self::getSerialFromApplicationNumber($applicationNumber);

    /** @var \Drupal\helfi_atv\AtvService $atvService */
    $atvService = \Drupal::service('helfi_atv.atv_service');

    /** @var \Drupal\grants_metadata\AtvSchema $atvSchema */
    $atvSchema = \Drupal::service('grants_metadata.atv_schema');

    /** @var \Drupal\grants_metadata\AtvSchema $atvSchema */
    $grantsProfileService = \Drupal::service('grants_profile.service');
    $selectedCompany = $grantsProfileService->getSelectedRoleData();

    // If no company selected, no mandates no access.
    if ($selectedCompany == NULL) {
      throw new CompanySelectException('User not authorised');
    }
    /** @var Drupal\helfi_atv\AtvDocument[] $document */
    try {
      $sParams = [
        'transaction_id' => $applicationNumber,
        'lookfor' => 'appenv:' . ApplicationHandler::getAppEnv(),
      ];

      $document = $atvService->searchDocuments(
        $sParams,
        $refetch
      );
    }
    catch (\Throwable $e) {
    }

    if (empty($document)) {
      throw new AtvDocumentNotFoundException('Document not found');
    }
    $document = reset($document);
    return $document;
  }

  /**
   * Check if application is open.
   *
   * In reality check if given date is between other dates.
   *
   * @param \Drupal\webform\Entity\Webform $webform
   *   Webform.
   *
   * @return bool
   *   Is or not open.
   */
  public static function isApplicationOpen(Webform $webform): bool {

    $thirdPartySettings = $webform->getThirdPartySettings('grants_metadata');
    $applicationContinuous = $thirdPartySettings["applicationContinuous"] == 1;

    try {
      $now = new \DateTime();
      $from = new \DateTime($thirdPartySettings["applicationOpen"]);
      $to = new \DateTime($thirdPartySettings["applicationClose"]);
    }
    catch (\Exception $e) {
      \Drupal::logger('application_handler')
        ->error('isApplicationOpen date error: @error', ['@error' => $e->getMessage()]);
      return $applicationContinuous;
    }

    // If today is between open & close dates return true.
    if ($now->getTimestamp() > $from->getTimestamp() && $now->getTimestamp() < $to->getTimestamp()) {
      return TRUE;
    }
    // Otherwise return true if is continuous, false if not.
    return $applicationContinuous;

  }

  /**
   * Atv document holding this application.
   *
   * @param string $transactionId
   *   Id of the transaction.
   * @param bool $refetch
   *   Force atv document fetch.
   *
   * @return \Drupal\helfi_atv\AtvDocument
   *   FEtched document.
   *
   * @throws \Drupal\helfi_atv\AtvDocumentNotFoundException
   * @throws \Drupal\helfi_atv\AtvFailedToConnectException
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getAtvDocument(string $transactionId, bool $refetch = FALSE): AtvDocument {

    if (!isset($this->atvDocument) || $refetch === TRUE) {
      $sParams = [
        'transaction_id' => $transactionId,
        'lookfor' => 'appenv:' . ApplicationHandler::getAppEnv(),
      ];

      $res = $this->atvService->searchDocuments($sParams);
      $this->atvDocument = reset($res);
    }

    return $this->atvDocument;
  }

  /**
   * Get typed data object for webform data.
   *
   * @param array $submittedFormData
   *   Form data.
   *
   * @return \Drupal\Core\TypedData\TypedDataInterface
   *   Typed data with values set.
   */
  public function webformToTypedData(
    array $submittedFormData
  ): TypedDataInterface {

    $dataDefinitionKeys = self::getDataDefinitionClass($submittedFormData['application_type']);

    $dataDefinition = $dataDefinitionKeys['definitionClass']::create($dataDefinitionKeys['definitionId']);

    $typeManager = $dataDefinition->getTypedDataManager();
    $applicationData = $typeManager->create($dataDefinition);

    $applicationData->setValue($submittedFormData);

    return $applicationData;
  }

  /**
   * Validate application data so that it is correct for saving to AVUS2.
   *
   * @param \Drupal\Core\TypedData\TypedDataInterface $applicationData
   *   Typed data object.
   * @param array $form
   *   Form array.
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   Form state object.
   * @param \Drupal\webform\Entity\WebformSubmission $webform_submission
   *   Submission object.
   *
   * @return \Symfony\Component\Validator\ConstraintViolationListInterface
   *   Constraint violation object.
   */
  public function validateApplication(
    TypedDataInterface $applicationData,
    array &$form,
    FormStateInterface &$formState,
    WebformSubmission $webform_submission
  ): ConstraintViolationListInterface {

    $violations = $applicationData->validate();

    $appProps = $applicationData->getProperties();

    $erroredItems = [];

    $webform = $webform_submission->getWebform();
    $formElementsDecodedAndFlattened = $webform->getElementsDecodedAndFlattened();

    if ($violations->count() > 0) {
      $violationPrints = [];
      /** @var \Symfony\Component\Validator\ConstraintViolationInterface $violation */
      foreach ($violations as $violation) {
        $propertyPath = $violation->getPropertyPath();

        if ($propertyPath == 'hakijan_tiedot.email') {
          continue;
        }

        $propertyPathArray = explode('.', $propertyPath);

        $thisProperty = $appProps[$propertyPathArray[0]];

        $thisDefinition = $thisProperty->getDataDefinition();
        $label = $thisDefinition->getLabel();
        $thisDefinitionSettings = $thisDefinition->getSettings();
        $message = $violation->getMessage();

        $violationPrints[$propertyPath] = $message;

        // formErrorElement setting controls what element on form errors
        // if data validation fails.
        if (isset($thisDefinitionSettings['formSettings']['formElement'])) {
          // Set property path to one defined in settings.
          $propertyPath = $thisDefinitionSettings['formSettings']['formElement'];
          // If not added already.
          if (!in_array($propertyPath, $erroredItems)) {
            $errorMsg = $thisDefinitionSettings['formSettings']['formError'] ?? $violation->getMessage();

            // Set message.
            $message = $this->t(
              '@label: @msg',
              [
                '@label' => $label,
                '@msg' => $errorMsg,
              ]
            );
            // Add errors to form.
            $formState->setErrorByName(
              $propertyPath,
              $message
            );
            // Add propertypath to errored items to have only
            // single error from whole address item.
            $erroredItems[] = $propertyPath;
          }
        }
        else {
          if (($formElement = $formElementsDecodedAndFlattened[$propertyPath]) && isset($formElement['#parents'])) {
            // Add errors to form.
            $formState->setError(
              $formElement,
              $message
            );
            // Add propertypath to errored items to have only
            // single error from whole address item.
          }
          else {
            if (count($propertyPathArray) > 1) {
              $propertyKey = str_replace('.', '][', $propertyPath);
              // Add errors to form.
              $formState->setErrorByName(
                $propertyKey,
                $message
              );
            }
            else {
              // Add errors to form.
              $formState->setErrorByName(
                $propertyPath,
                $message
              );
            }

            // Add propertypath to errored items to have only
            // single error from whole address item.
          }
          $erroredItems[] = $propertyPath;
        }
      }
      $values = $applicationData->getValue();

      if ($this->isDebug()) {
        $this->logger->error('@appno data validation failed, errors: @errors',
          [
            '@appno' => $values["application_number"],
            '@errors' => json_encode($violationPrints),
          ]);
      }
    }
    try {
      $this->grantsHandlerNavigationHelper->logPageErrors($webform_submission, $formState);
    }
    catch (\Exception $e) {
    }

    return $violations;
  }

  /**
   * Get webform title based on id and language code.
   */
  private function getWebformTitle($webform_id, $langCode) {
    // Get the target language object.
    $language = $this->languageManager->getLanguage($langCode);

    // Remember original language before this operation.
    $originalLanguage = $this->languageManager->getConfigOverrideLanguage();

    // Set the translation target language on the configuration factory.
    $this->languageManager->setConfigOverrideLanguage($language);
    $translatedLabel = \Drupal::config("webform.webform.${webform_id}")
      ->get('title');
    $this->languageManager->setConfigOverrideLanguage($originalLanguage);
    return $translatedLabel;
  }

  /**
   * Method to initialise application document in ATV. Create & save.
   *
   * If data is given, use that data to copy things to new application.
   *
   * @param string $webform_id
   *   Id of a webform of created application.
   * @param array $submissionData
   *   If we want to pass any initial data for new application, do it with
   *   this.
   *   Must be like webform data.
   *
   * @return \Drupal\webform\Entity\WebformSubmission
   *   Newly created application content.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\helfi_atv\AtvDocumentNotFoundException
   * @throws \Drupal\helfi_atv\AtvFailedToConnectException
   * @throws \GuzzleHttp\Exception\GuzzleException|\Drupal\helfi_helsinki_profiili\ProfileDataException
   */
  public function initApplication(string $webform_id, array $submissionData = []): WebformSubmission {

    $webform = Webform::load($webform_id);
    $userData = $this->helfiHelsinkiProfiiliUserdata->getUserData();

    if ($userData == NULL) {
      // We absolutely cannot create new application without user data.
      throw new ProfileDataException('No Helsinki profile data found');
    }

    $selectedCompany = $this->grantsProfileService->getSelectedRoleData();
    $companyData = $this->grantsProfileService->getGrantsProfileContent($selectedCompany);

    // If we've given data to work with, clear it for copying.
    if (empty($submissionData)) {
      $copy = FALSE;
    }
    else {
      $copy = TRUE;
      $submissionData = self::clearDataForCopying($submissionData);
    }

    // Set.
    $submissionData['application_type_id'] = $webform->getThirdPartySetting('grants_metadata', 'applicationTypeID');
    $submissionData['application_type'] = $webform->getThirdPartySetting('grants_metadata', 'applicationType');
    $submissionData['applicant_type'] = $this->grantsProfileService->getApplicantType();
    $submissionData['status'] = self::getApplicationStatuses()['DRAFT'];
    $submissionData['company_number'] = $selectedCompany['identifier'];
    $submissionData['business_purpose'] = $companyData['businessPurpose'] ?? '';

    try {
      // Merge sender details to new stuff.
      $submissionData = array_merge($submissionData, $this->parseSenderDetails());
    }
    catch (ApplicationException $e) {
      $this->logger->error('Sender details parsing threw error: @error', ['@error' => $e->getMessage()]);
    }

    // Set form timestamp to current time.
    // apparently this is always set to latest submission.
    $dt = new \DateTime();
    $dt->setTimezone(new \DateTimeZone('Europe/Helsinki'));
    $submissionData['form_timestamp'] = $dt->format('Y-m-d\TH:i:s');
    $submissionData['form_timestamp_created'] = $dt->format('Y-m-d\TH:i:s');

    $submissionObject = WebformSubmission::create([
      'webform_id' => $webform->id(),
      'draft' => TRUE,
    ]);
    $submissionObject->set('in_draft', TRUE);
    $submissionObject->save();

    $applicationNumber = ApplicationHandler::createApplicationNumber($submissionObject);
    $submissionData['application_number'] = $applicationNumber;

    $atvDocument = AtvDocument::create([]);
    $atvDocument->setTransactionId($applicationNumber);
    $atvDocument->setStatus(self::getApplicationStatuses()['DRAFT']);
    $atvDocument->setType($submissionData['application_type']);
    $atvDocument->setService(getenv('ATV_SERVICE'));
    $atvDocument->setUserId($userData['sub']);
    $atvDocument->setTosFunctionId(getenv('ATV_TOS_FUNCTION_ID'));
    $atvDocument->setTosRecordId(getenv('ATV_TOS_RECORD_ID'));
    if ($submissionData['applicant_type'] == 'registered_community') {
      $atvDocument->setBusinessId($selectedCompany['identifier']);
    }
    $atvDocument->setDraft(TRUE);
    $atvDocument->setDeletable(FALSE);

    $humanReadableTypes = [
      'en' => $this->getWebformTitle($webform_id, 'en'),
      'fi' => $this->getWebformTitle($webform_id, 'fi'),
      'sv' => $this->getWebformTitle($webform_id, 'sv'),
    ];

    $atvDocument->setHumanReadableType($humanReadableTypes);

    $atvDocument->setMetadata([
      'appenv' => self::getAppEnv(),
      // Hmm, maybe no save id at this point?
      'saveid' => $copy ? 'copiedSave' : 'initialSave',
      'applicationnumber' => $applicationNumber,
      'language' => $this->languageManager->getCurrentLanguage()->getId(),
      'applicant_type' => $selectedCompany['type'],
      'applicant_id' => $selectedCompany['identifier'],
    ]);

    $typeData = $this->webformToTypedData($submissionData);
    /** @var \Drupal\Core\TypedData\TypedDataInterface $applicationData */
    $appDocumentContent = $this->atvSchema->typedDataToDocumentContent($typeData, $submissionObject);

    $atvDocument->setContent($appDocumentContent);

    $newDocument = $this->atvService->postDocument($atvDocument);

    $dataDefinitionKeys = self::getDataDefinitionClass($submissionData['application_type']);
    $dataDefinition = $dataDefinitionKeys['definitionClass']::create($dataDefinitionKeys['definitionId']);

    $submissionObject->setData($this->atvSchema->documentContentToTypedData($newDocument->getContent(), $dataDefinition));

    return $submissionObject;
  }

  /**
   * Handle application upload directly to ATV.
   *
   * @param \Drupal\Core\TypedData\TypedDataInterface $applicationData
   *   Application data in typed data object.
   * @param string $applicationNumber
   *   Application number.
   *
   * @return \Drupal\helfi_atv\AtvDocument|bool|null
   *   Result of the upload.
   *
   * @throws \Drupal\helfi_atv\AtvDocumentNotFoundException
   * @throws \Drupal\helfi_atv\AtvFailedToConnectException
   * @throws \Drupal\helfi_helsinki_profiili\TokenExpiredException
   * @throws \GuzzleHttp\Exception\GuzzleException
   * @throws \Exception
   */
  public function handleApplicationUploadToAtv(
    TypedDataInterface $applicationData,
    string $applicationNumber
  ): AtvDocument|bool|null {
    $webform_submission = ApplicationHandler::submissionObjectFromApplicationNumber($applicationNumber);
    /** @var \Drupal\Core\TypedData\TypedDataInterface $applicationData */
    $appDocumentContent = $this->atvSchema->typedDataToDocumentContent($applicationData, $webform_submission);

    $atvDocument = $this->getAtvDocument($applicationNumber, TRUE);
    try {
      $atvDocument->addMetadata(
        'saveid',
        $this->logSubmissionSaveid(NULL, $applicationNumber)
      );
    }
    catch (\Exception $e) {
    }

    $atvDocument->setContent($appDocumentContent);

    if ($this->newStatusHeader && $this->newStatusHeader != '') {
      $atvDocument->setStatus($this->newStatusHeader);
    }

    $updatedDocument = $this->atvService->patchDocument(
      $atvDocument->getId(),
      $atvDocument->toArray()
    );

    $this->atvDocument = $updatedDocument;

    return $updatedDocument;

  }

  /**
   * Take in typed data object, export to Avus2 document structure & upload.
   *
   * @param \Drupal\Core\TypedData\TypedDataInterface $applicationData
   *   Typed data object.
   * @param string $applicationNumber
   *   Used application number.
   *
   * @return bool
   *   Result.
   */
  public function handleApplicationUploadViaIntegration(
    TypedDataInterface $applicationData,
    string $applicationNumber
  ): bool {
    $webformSubmission = ApplicationHandler::submissionObjectFromApplicationNumber($applicationNumber);
    /** @var \Drupal\Core\TypedData\TypedDataInterface $applicationData */
    $appDocument = $this->atvSchema->typedDataToDocumentContent($applicationData, $webformSubmission);
    $myJSON = Json::encode($appDocument);

    if ($this->isDebug()) {
      $t_args = [
        '%endpoint' => $this->endpoint,
      ];
      $this->logger
        ->debug('DEBUG: Endpoint: %endpoint', $t_args);

      $t_args = [
        '%myJSON' => $myJSON,
      ];
      $this->logger
        ->debug('DEBUG: Sent JSON: %myJSON', $t_args);
    }

    try {

      $headers = [];
      if ($this->newStatusHeader && $this->newStatusHeader != '') {
        $headers['X-Case-Status'] = $this->newStatusHeader;
      }

      // Current environment as a header to be added to meta -fields.
      $headers['X-hki-appEnv'] = self::getAppEnv();
      // Set application number to meta as well to enable better searches.
      $headers['X-hki-applicationNumber'] = $applicationNumber;
      // Set new saveid and save it to db.
      $headers['X-hki-saveId'] = $this->logSubmissionSaveid(NULL, $applicationNumber);

      $res = $this->httpClient->post($this->endpoint, [
        'auth' => [
          $this->username,
          $this->password,
          "Basic",
        ],
        'body' => $myJSON,
        'headers' => $headers,
      ]);

      $status = $res->getStatusCode();

      if ($this->isDebug()) {
        $t_args = [
          '@status' => $status,
        ];
        $this->logger
          ->debug('Data sent to integration, response status: @status', $t_args);
      }

      if ($status === 200) {
        $this->atvService->clearCache($applicationNumber);
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
    catch (\Exception $e) {
      $this->messenger->addError($this->t('Application saving failed, error has been logged.'));
      $this->logger->error('Error saving application: %msg', ['%msg' => $e->getMessage()]);
      return FALSE;
    }
  }

  /**
   * If debug is on or not.
   *
   * @return bool
   *   TRue or false depending on if debug is on or not.
   */
  public function isDebug(): bool {
    return $this->debug;
  }

  /**
   * Set debug.
   *
   * @param bool $debug
   *   True or false.
   */
  public function setDebug(bool $debug): void {
    $this->debug = $debug;
  }

  /**
   * Figure out from events which messages are unread.
   *
   * @param array $data
   *   Submission data.
   * @param bool $onlyUnread
   *   Return only unread messages.
   *
   * @return array
   *   Parsed messages with read information
   */
  public static function parseMessages(array $data, $onlyUnread = FALSE) {

    $messageEvents = array_filter($data['events'], function ($event) {
      if ($event['eventType'] == EventsService::$eventTypes['MESSAGE_READ']) {
        return TRUE;
      }
      return FALSE;
    });

    $eventIds = array_column($messageEvents, 'eventTarget');

    $messages = [];
    $unread = [];

    foreach ($data['messages'] as $message) {
      $msgUnread = NULL;
      $ts = strtotime($message["sendDateTime"]);

      if (in_array($message['messageId'], $eventIds)) {
        $message['messageStatus'] = 'READ';
        $msgUnread = FALSE;
      }
      else {
        $message['messageStatus'] = 'UNREAD';
        $msgUnread = TRUE;
      }

      if ($onlyUnread === TRUE && $msgUnread === TRUE) {
        $unread[$ts] = $message;
      }
      $messages[$ts] = $message;
    }
    if ($onlyUnread === TRUE) {
      return $unread;
    }
    return $messages;
  }

  /**
   * Set up sender details from helsinkiprofiili data.
   *
   * @return array
   *   Sender details.
   *
   * @throws \Drupal\helfi_helsinki_profiili\TokenExpiredException
   * @throws \Drupal\grants_handler\ApplicationException
   */
  public function parseSenderDetails(): array {
    // Set sender information after save so no accidental saving of data.
    $userProfileData = $this->helfiHelsinkiProfiiliUserdata->getUserProfileData();
    $userData = $this->helfiHelsinkiProfiiliUserdata->getUserData();

    $senderDetails = [];

    if (isset($userProfileData["myProfile"])) {
      $data = $userProfileData["myProfile"];
    }
    else {
      $data = $userProfileData;
    }

    // If no userprofile data, we need to hardcode these values.
    if ($userProfileData == NULL || $userData == NULL) {
      throw new ApplicationException('No profile data found for user.');
    }
    else {
      $senderDetails['sender_firstname'] = $data["verifiedPersonalInformation"]["firstName"];
      $senderDetails['sender_lastname'] = $data["verifiedPersonalInformation"]["lastName"];
      $senderDetails['sender_person_id'] = $data["verifiedPersonalInformation"]["nationalIdentificationNumber"];
      $senderDetails['sender_user_id'] = $userData["sub"];
      $senderDetails['sender_email'] = $data["primaryEmail"]["email"];
    }

    return $senderDetails;
  }

  /**
   * Access method to clear cache in atv service.
   *
   * @param string $applicationNumber
   *   Application number.
   */
  public function clearCache(string $applicationNumber) {
    $this->atvService->clearCache($applicationNumber);
  }

  /**
   * Get data definition class from application type.
   *
   * @param string $type
   *   Type of the application.
   */
  public static function getDataDefinition(string $type) {
    $defClass = self::getApplicationTypes()[$type]['dataDefinition']['definitionClass'];
    $defId = self::getApplicationTypes()[$type]['dataDefinition']['definitionId'];
    return $defClass::create($defId);
  }

  /**
   * Get data definition class from application type.
   *
   * @param string $type
   *   Type of the application.
   */
  public static function getDataDefinitionClass(string $type) {
    return self::getApplicationTypes()[$type]['dataDefinition'];
  }

  /**
   * Get company applications, either sorted by finished or all in one array.
   *
   * @param array $selectedCompany
   *   Company data.
   * @param string $appEnv
   *   Environment.
   * @param bool $sortByFinished
   *   When true, results will be sorted by finished status.
   * @param bool $sortByStatus
   *   Sort by application status.
   * @param string $themeHook
   *   Use theme hook to render content. Set this to theme hook wanted to use,
   *   and sen #submission to webform submission.
   *
   * @return array
   *   Submissions in array.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Core\TempStore\TempStoreException
   * @throws \Drupal\grants_mandate\CompanySelectException
   * @throws \Drupal\helfi_atv\AtvDocumentNotFoundException
   * @throws \Drupal\helfi_atv\AtvFailedToConnectException
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public static function getCompanyApplications(
    array $selectedCompany,
    string $appEnv,
    bool $sortByFinished = FALSE,
    bool $sortByStatus = FALSE,
    string $themeHook = ''): array {

    /** @var \Drupal\helfi_atv\AtvService $atvService */
    $atvService = \Drupal::service('helfi_atv.atv_service');

    /** @var \Drupal\grants_profile\GrantsProfileService $grantsProfileService */
    $grantsProfileService = \Drupal::service('grants_profile.service');

    /** @var \Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData $helsinkiProfiiliService */
    $helsinkiProfiiliService = \Drupal::service('helfi_helsinki_profiili.userdata');
    $userData = $helsinkiProfiiliService->getUserData();

    $applications = [];
    $finished = [];
    $unfinished = [];

    $selectedRoleData = $grantsProfileService->getSelectedRoleData();

    $lookForAppEnv = 'appenv:' . $appEnv;

    if ($selectedRoleData['type'] == 'private_person') {
      $searchParams = [
        'service' => 'AvustushakemusIntegraatio',
        'user_id' => $userData['sub'],
        'lookfor' => $lookForAppEnv . ',applicant_type:' . $selectedRoleData['type'],
      ];
    }
    elseif ($selectedRoleData['type'] == 'unregistered_community') {
      $searchParams = [
        'service' => 'AvustushakemusIntegraatio',
        'user_id' => $userData['sub'],
        'lookfor' => $lookForAppEnv . ',applicant_type:' . $selectedRoleData['type'] .
        ',applicant_id:' . $selectedRoleData['identifier'],
      ];
    }
    else {
      $searchParams = [
        'service' => 'AvustushakemusIntegraatio',
        'business_id' => $selectedCompany['identifier'],
        'lookfor' => $lookForAppEnv,
      ];
    }

    $applicationDocuments = $atvService->searchDocuments($searchParams);

    /**
     * Create rows for table.
     *
     * @var  \Drupal\helfi_atv\AtvDocument $document
     */
    foreach ($applicationDocuments as $document) {
      // Make sure the type is acceptable one.
      $docArray = $document->toArray();
      $id = AtvSchema::extractDataForWebForm(
        $docArray['content'], ['applicationNumber']
      );

      if (!isset($id['applicationNumber']) || empty($id['applicationNumber'])) {
        continue;
      }

      if (array_key_exists($document->getType(), ApplicationHandler::getApplicationTypes())) {
        $submissionObject = self::submissionObjectFromApplicationNumber($document->getTransactionId(), $document);
        $submissionData = $submissionObject->getData();
        $ts = strtotime($submissionData['form_timestamp_created'] ?? '');
        if ($themeHook !== '') {
          $submission = [
            '#theme' => $themeHook,
            '#submission' => $submissionObject,
            '#document' => $document,
          ];
        }
        else {
          $submission = $submissionObject;
        }
        if ($sortByFinished === TRUE) {
          if (self::isSubmissionFinished($submission)) {
            $finished[$ts] = $submission;
          }
          else {
            $unfinished[$ts] = $submission;
          }
        }
        elseif ($sortByStatus === TRUE) {
          $applications[$submissionData['status']][] = $submission;
        }
        else {
          $applications[$ts] = $submission;
        }
      }
    }

    if ($sortByFinished === TRUE) {
      ksort($finished);
      ksort($unfinished);
      return [
        'finished' => $finished,
        'unifinished' => $unfinished,
      ];
    }
    else {
      ksort($applications);
      return $applications;
    }
  }

  /**
   * Logs the current submission page.
   *
   * @param \Drupal\webform\WebformSubmissionInterface|null $webform_submission
   *   A webform submission entity.
   * @param string $applicationNumber
   *   The page to log.
   * @param string $saveId
   *   Submission save id.
   *
   * @throws \Exception
   */
  public function logSubmissionSaveid(
    ?WebformSubmissionInterface $webform_submission,
    string $applicationNumber,
    string $saveId = ''
  ): string {

    if (empty($saveId)) {
      $saveId = Uuid::uuid4()->toString();
    }

    if ($webform_submission == NULL) {
      $webform_submission = ApplicationHandler::submissionObjectFromApplicationNumber($applicationNumber);
    }

    $userData = $this->helfiHelsinkiProfiiliUserdata->getUserData();
    $fields = [
      'webform_id' => ($webform_submission) ? $webform_submission->getWebform()
        ->id() : '',
      'sid' => ($webform_submission) ? $webform_submission->id() : 0,
      'handler_id' => self::HANDLER_ID,
      'application_number' => $applicationNumber,
      'saveid' => $saveId,
      'uid' => \Drupal::currentUser()->id(),
      'user_uuid' => $userData['sub'] ?? '',
      'timestamp' => (string) \Drupal::time()->getRequestTime(),
    ];

    $query = $this->database->insert(self::TABLE, $fields);
    $query->fields($fields)->execute();

    return $saveId;

  }

  /**
   * Validate submission data integrity.
   *
   * Validates file uploads as well, we can't allow other updates to data
   * before all attachment related things are done properly with integration.
   *
   * @param \Drupal\webform\WebformSubmissionInterface|null $webform_submission
   *   Webform submission object, if known. If this is not set,
   *   submission data must be provided.
   * @param array|null $submissionData
   *   Submission data. If no submission object, this is required.
   * @param string $applicationNumber
   *   Application number.
   * @param string $saveIdToValidate
   *   Save uuid to validate data integrity against.
   *
   * @return string
   *   Data integrity status.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\helfi_atv\AtvDocumentNotFoundException
   */
  public function validateDataIntegrity(
    ?WebformSubmissionInterface $webform_submission,
    ?array $submissionData,
    string $applicationNumber,
    string $saveIdToValidate): string {

    if ($submissionData == NULL || empty($submissionData)) {
      if ($webform_submission == NULL) {
        $webform_submission = ApplicationHandler::submissionObjectFromApplicationNumber($applicationNumber);
      }
      $submissionData = $webform_submission->getData();
    }
    if ($submissionData == NULL || empty($submissionData)) {
      $this->logger->log('info', 'No submissiondata when trying to validate saveid: %application_number @saveid', [
        '%application_number' => $applicationNumber,
        '@saveid' => $saveIdToValidate,
      ]);
      return 'NO_SUBMISSION_DATA';
    }

    $query = $this->database->select(self::TABLE, 'l');
    $query->condition('application_number', $applicationNumber);
    $query->fields('l', [
      'lid',
      'saveid',
    ]);
    $query->orderBy('l.lid', 'DESC');
    $query->range(0, 1);

    $saveid_log = $query->execute()->fetch();
    $latestSaveid = !empty($saveid_log->saveid) ? $saveid_log->saveid : '';

    // initialSave or copied save no datavalidation.
    if ($saveIdToValidate == 'copiedSave' || $saveIdToValidate == 'initialSave') {
      return 'OK';
    }

    if ($saveIdToValidate !== $latestSaveid) {
      $this->logger->log('info', 'Save ids not matching  %application_number ATV:@saveid, Local: %local_save_id', [
        '%application_number' => $applicationNumber,
        '%local_save_id' => $latestSaveid,
        '@saveid' => $saveIdToValidate,
      ]);
      return 'DATA_NOT_SAVED_ATV';
    }

    $applicationEvents = EventsService::filterEvents($submissionData['events'] ?? [], 'INTEGRATION_INFO_APP_OK');

    if (!in_array($saveIdToValidate, $applicationEvents['event_targets'])) {
      if (isset($submissionData['status']) && $submissionData['status'] != 'DRAFT') {
        $this->logger->log('info', 'Data not saved to Avus. %application_number ATV:@saveid, Local: %local_save_id', [
          '%application_number' => $applicationNumber,
          '%local_save_id' => $latestSaveid,
          '@saveid' => $saveIdToValidate,
        ]);
        return 'DATA_NOT_SAVED_AVUS2';
      }
    }

    $attachmentEvents = EventsService::filterEvents($submissionData['events'] ?? [], 'HANDLER_ATT_OK');

    $fileFieldNames = AttachmentHandler::getAttachmentFieldNames($submissionData["application_number"]);

    $nonUploaded = 0;
    foreach ($fileFieldNames as $fieldName) {
      $fileField = $submissionData[$fieldName] ?? NULL;
      if ($fileField == NULL) {
        continue;
      }
      if (self::isMulti($fileField)) {
        foreach ($fileField as $muu_liite) {
          if (isset($muu_liite['fileName'])) {
            if (!in_array($muu_liite['fileName'], $attachmentEvents["event_targets"])) {
              // $nonUploaded++;
            }
          }
        }
      }
      else {
        if (
          (isset($fileField['fileName']) && !empty($fileField['fileName'])) &&
          (isset($fileField['fileStatus']) && $fileField['fileStatus'] !== 'justUploaded')
        ) {
          if (!in_array($fileField['fileName'], $attachmentEvents["event_targets"])) {
            $nonUploaded++;
          }
        }

      }
    }

    if ($nonUploaded !== 0) {
      $this->logger->log('info', 'File upload not finished.  %application_number ATV:@saveid, Local: %local_save_id', [
        '%application_number' => $applicationNumber,
        '%local_save_id' => $latestSaveid,
        '@saveid' => $saveIdToValidate,
      ]);
      return 'FILE_UPLOAD_PENDING';
    }

    return 'OK';

  }

  /**
   * Is array multidimensional.
   *
   * @param array $arr
   *   Array to be inspected.
   *
   * @return bool
   *   True or false.
   */
  public static function isMulti(array $arr) {
    foreach ($arr as $v) {
      if (is_array($v)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Clear application data for noncopyable elements.
   *
   * @param array $data
   *   Data to copy from.
   *
   * @return array
   *   Cleaned values.
   */
  public static function clearDataForCopying(array $data): array {

    unset($data["sender_firstname"]);
    unset($data["sender_lastname"]);
    unset($data["sender_person_id"]);
    unset($data["sender_user_id"]);
    unset($data["sender_email"]);
    unset($data["metadata"]);
    unset($data["attachments"]);
    unset($data["form_timestamp_submitted"]);
    unset($data["form_timestamp_created"]);

    $data['events'] = [];
    $data['messages'] = [];
    $data['status_updates'] = [];

    // Clear uploaded files..
    foreach (AttachmentHandler::getAttachmentFieldNames($data["application_number"]) as $fieldName) {
      unset($data[$fieldName]);
    }
    unset($data["application_number"]);

    return $data;

  }

  /**
   * Get updated status header. Empty if no updates.
   *
   * @return string
   *   New status or empty
   */
  public function getNewStatusHeader(): string {
    return $this->newStatusHeader;
  }

  /**
   * Gets webform & submission with data and determines access.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   User account.
   * @param string $operation
   *   Operation we check access against.
   * @param \Drupal\webform\Entity\Webform $webform
   *   Webform object.
   * @param \Drupal\webform\Entity\WebformSubmission $webform_submission
   *   Submission object.
   *
   * @return bool
   *   Access status
   */
  public function singleSubmissionAccess(AccountInterface $account, string $operation, Webform $webform, WebformSubmission $webform_submission): bool {

    // If we have account number, load details.
    $selectedCompany = $this->grantsProfileService->getSelectedRoleData();
    $grantsProfileDocument = $this->grantsProfileService->getGrantsProfile($selectedCompany);
    $profileContent = $grantsProfileDocument->getContent();
    $webformData = $webform_submission->getData();
    $companyType = $selectedCompany['type'] ?? NULL;

    if (!$companyType) {
      return FALSE;
    }

    $atvDoc = ApplicationHandler::atvDocumentFromApplicationNumber($webformData['application_number']);
    $atvMetadata = $atvDoc->getMetadata();
    // Mismatch between profile and application applicant type.
    if ($companyType !== $webformData['hakijan_tiedot']['applicantType']) {
      return FALSE;
    }
    elseif ($companyType == "registered_community" && $profileContent['businessId'] !== $atvDoc->getBusinessId()) {
      return FALSE;
    }
    elseif ($companyType === "private_person" && $profileContent['businessId'] !== $atvDoc->getUserId()) {
      return FALSE;
    }
    elseif ($companyType === "unregistered_community" && $profileContent['businessId'] !== $atvMetadata['applicant_id']) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Easier method to check if we're in production.
   *
   * @param string $appEnv
   *   App env from handler.
   *
   * @return bool
   *   Is production env?
   */
  public static function isProduction(string $appEnv): bool {
    $proenvs = [
      'production',
      'PRODUCTION',
      'PROD',
    ];
    return in_array($appEnv, $proenvs);
  }

}
