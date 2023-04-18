<?php

namespace Drupal\grants_applicant_info\Element;

use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\EmailValidator;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\helfi_helsinki_profiili\TokenExpiredException;
use Drupal\webform\Element\WebformCompositeBase;
use Drupal\form_tool_profile_data\Plugin\WebformElement\FormToolProfileData as ProfileDataElement;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a 'applicant_info_composite'.
 *
 * Webform composites contain a group of sub-elements.
 *
 *
 * IMPORTANT:
 * Webform composite can not contain multiple value elements (i.e. checkboxes)
 * or composites (i.e. applicant_info_composite)
 *
 * @FormElement("applicant_info_composite")
 *
 * @see \Drupal\webform\Element\WebformCompositeBase
 * @see \Drupal\form_tool_profile_data\Element\FormToolProfileData
 */
class ApplicantInfoComposite extends WebformCompositeBase {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    return parent::getInfo() + ['#theme' => 'applicant_info_composite'];
  }

  /**
   * {@inheritdoc}
   */
  public static function getCompositeElements(array $element) {

    $elements = [];

//    /** @var \Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData $hpud */
//    $hpud = \Drupal::service('helfi_helsinki_profiili.userdata');
//
//    // If user is not helsinkiproifile user we don't have any user info.
//    $currentUserRoles = \Drupal::currentUser()->getRoles();
//    if (
//      !in_array('helsinkiprofiili_vahva', $currentUserRoles) &&
//      !in_array('helsinkiprofiili_heikko', $currentUserRoles)) {
//      return [];
//    }
//
//    $options = ProfileDataElement::getFieldSelections();
//
//    try {
//      $userProfile = $hpud->getUserProfileData();
//    }
//    catch (TokenExpiredException $e) {
//      \Drupal::logger('form_tool_profile_data')->error('Error fetching user profile data: @error', ['@error' => $e->getMessage()]);
//    }
//
//    if (!\Drupal::service('router.admin_context')->isAdminRoute()) {
//      if (empty($userProfile) || empty($userProfile['myProfile'])) {
//        throw new AccessDeniedHttpException('No profile data available');
//      }
//    }
//
//    // Debug user data.
//    self::debug('User data from HP: @userdata', ['@userdata' => Json::encode($userProfile)]);
//
//    $authLevel = $hpud->getAuthenticationLevel();
//
//    if ($authLevel == 'strong' && isset($element['#strong'])) {
//
//      $selectedFields = $element['#strong'];
//
//      if ($userProfile === NULL) {
//        return $elements;
//      }
//
//      $elements['visibleList'] = [
//        '#type' => 'html_tag',
//        '#tag' => 'dl',
//        '#attributes' => [
//          'class' => ['profile-data'],
//        ],
//      ];
//      if (isset($selectedFields['verifiedFirstName']) && $selectedFields['verifiedFirstName'] !== 0) {
//        $elements['visibleList']['verifiedFirstNameTitle'] = [
//          '#type' => 'html_tag',
//          '#tag' => 'dt',
//          '#value' => $options['strong']['verifiedFirstName'],
//        ];
//        $elements['visibleList']['verifiedFirstNameDesc'] = [
//          '#type' => 'html_tag',
//          '#tag' => 'dd',
//          '#value' => $userProfile["myProfile"]["verifiedPersonalInformation"]["firstName"] ?? '-',
//        ];
//        $elements['verifiedFirstName'] = [
//          '#type' => 'hidden',
//          '#value' => $userProfile["myProfile"]["verifiedPersonalInformation"]["firstName"],
//          '#required' => TRUE,
//        ];
//      }
//      if (isset($selectedFields['verifiedLastName']) && $selectedFields['verifiedLastName'] !== 0) {
//        $elements['visibleList']['verifiedLastNameTitle'] = [
//          '#type' => 'html_tag',
//          '#tag' => 'dt',
//          '#value' => $options['strong']['verifiedLastName'],
//        ];
//        $elements['visibleList']['verifiedLastNameDesc'] = [
//          '#type' => 'html_tag',
//          '#tag' => 'dd',
//          '#value' => $userProfile["myProfile"]["verifiedPersonalInformation"]["lastName"] ?? '-',
//        ];
//        $elements['verifiedLastName'] = [
//          '#type' => 'hidden',
//          '#value' => $userProfile["myProfile"]["verifiedPersonalInformation"]["lastName"],
//          '#required' => TRUE,
//        ];
//      }
//      if (isset($selectedFields['verifiedSsn']) && $selectedFields['verifiedSsn'] !== 0) {
//        $elements['visibleList']['verifiedSsnTitle'] = [
//          '#type' => 'html_tag',
//          '#tag' => 'dt',
//          '#value' => $options['strong']['verifiedSsn'],
//        ];
//        $elements['visibleList']['verifiedSsnDesc'] = [
//          '#type' => 'html_tag',
//          '#tag' => 'dd',
//          '#value' => $userProfile["myProfile"]["verifiedPersonalInformation"]["nationalIdentificationNumber"] ?? '-',
//        ];
//        $elements['verifiedSsn'] = [
//          '#type' => 'hidden',
//          '#value' => $userProfile["myProfile"]["verifiedPersonalInformation"]["nationalIdentificationNumber"],
//          '#required' => TRUE,
//        ];
//      }
//      if (isset($selectedFields['verifiedGivenName']) && $selectedFields['verifiedGivenName'] !== 0) {
//        $elements['visibleList']['verifiedGivenNameTitle'] = [
//          '#type' => 'html_tag',
//          '#tag' => 'dt',
//          '#value' => $options['strong']['verifiedGivenName'],
//        ];
//        $elements['visibleList']['verifiedGivenNameDesc'] = [
//          '#type' => 'html_tag',
//          '#tag' => 'dd',
//          '#value' => $userProfile["myProfile"]["verifiedPersonalInformation"]["givenName"] ?? '-',
//        ];
//        $elements['verifiedGivenName'] = [
//          '#type' => 'hidden',
//          '#value' => $userProfile["myProfile"]["verifiedPersonalInformation"]["givenName"],
//          '#required' => TRUE,
//        ];
//      }
//      if (isset($selectedFields['verifiedPermanentAddress']) && $selectedFields['verifiedPermanentAddress'] !== 0) {
//        $permanentAddress = [
//          $userProfile["myProfile"]["verifiedPersonalInformation"]["permanentAddress"]["streetAddress"],
//          $userProfile["myProfile"]["verifiedPersonalInformation"]["permanentAddress"]["postalCode"],
//          $userProfile["myProfile"]["verifiedPersonalInformation"]["permanentAddress"]["postOffice"],
//        ];
//        $elements['visibleList']['verifiedPermanentAddressTitle'] = [
//          '#type' => 'html_tag',
//          '#tag' => 'dt',
//          '#value' => $options['strong']['verifiedPermanentAddress'],
//        ];
//        $elements['visibleList']['verifiedPermanentAddressDesc'] = [
//          '#type' => 'html_tag',
//          '#tag' => 'dd',
//          '#value' => implode(', ', $permanentAddress) ?? '-',
//        ];
//        $elements['verifiedPermanentAddress'] = [
//          '#type' => 'hidden',
//          '#value' =>
//          $userProfile["myProfile"]["verifiedPersonalInformation"]["permanentAddress"]["streetAddress"] . ', ' .
//          $userProfile["myProfile"]["verifiedPersonalInformation"]["permanentAddress"]["postalCode"] . ', ' .
//          $userProfile["myProfile"]["verifiedPersonalInformation"]["permanentAddress"]["postOffice"],
//          '#required' => TRUE,
//        ];
//      }
//    }
//
//    if ($authLevel == 'weak' && isset($element['#weak'])) {
//      $selectedFields = $element['#weak'];
//
//      if (isset($selectedFields['primaryAddress']) && $selectedFields['primaryAddress'] !== 0) {
//        $primaryAddress = [
//          $userProfile["myProfile"]["primaryAddress"]["address"],
//          $userProfile["myProfile"]["primaryAddress"]["postalCode"],
//          $userProfile["myProfile"]["primaryAddress"]["city"],
//          $userProfile["myProfile"]["primaryAddress"]["countryCode"],
//        ];
//        $elements['visibleList']['primaryAddressTitle'] = [
//          '#type' => 'html_tag',
//          '#tag' => 'dt',
//          '#value' => $options['weak']['primaryAddress'],
//        ];
//        $elements['visibleList']['primaryAddressDesc'] = [
//          '#type' => 'html_tag',
//          '#tag' => 'dd',
//          '#value' => implode(', ', $primaryAddress) ?? '-',
//        ];
//        $elements['primaryAddress'] = [
//          '#type' => 'hidden',
//          '#value' =>
//          $userProfile["myProfile"]["primaryAddress"]["address"] . ', ' .
//          $userProfile["myProfile"]["primaryAddress"]["postalCode"] . ', ' .
//          $userProfile["myProfile"]["primaryAddress"]["city"] . ', ' .
//          $userProfile["myProfile"]["primaryAddress"]["countryCode"],
//          '#required' => TRUE,
//        ];
//      }
//    }
//
//    // Move this outside of those ifs so that snyk doesn't go crazy.
//    if (isset($selectedFields['primaryEmail']) &&
//      $selectedFields['primaryEmail'] !== 0
//    ) {
//      $elements['visibleList']['primaryEmailTitle'] = [
//        '#type' => 'html_tag',
//        '#tag' => 'dt',
//        '#value' => $options['weak']['primaryEmail'],
//      ];
//      $elements['visibleList']['primaryEmailDesc'] = [
//        '#type' => 'html_tag',
//        '#tag' => 'dd',
//        '#value' => $userProfile["myProfile"]["primaryEmail"]["email"] ?? '-',
//      ];
//      $elements['primaryEmail'] = [
//        '#type' => 'hidden',
//        '#value' => $userProfile["myProfile"]["primaryEmail"]["email"],
//        '#required' => TRUE,
//        '#element_validate' => [
//          [static::class, 'validateEmail'],
//        ],
//      ];
//    }
//    if (isset($selectedFields['primaryPhone']) &&
//      $selectedFields['primaryPhone'] !== 0
//    ) {
//      $elements['visibleList']['primaryPhoneTitle'] = [
//        '#type' => 'html_tag',
//        '#tag' => 'dt',
//        '#value' => $options['weak']['primaryPhone'],
//      ];
//      $elements['visibleList']['primaryPhonelDesc'] = [
//        '#type' => 'html_tag',
//        '#tag' => 'dd',
//        '#value' => $userProfile["myProfile"]["primaryPhone"]["phone"] ?? '-',
//      ];
//      $elements['primaryPhone'] = [
//        '#type' => 'hidden',
//        '#value' => $userProfile["myProfile"]["primaryPhone"]["phone"] ?? '-',
//        '#required' => TRUE,
//        '#element_validate' => [
//          [static::class, 'validatePhoneNumber'],
//        ],
//      ];
//    }
//
//    $profileEditUrl = Url::fromUri(getenv('HELSINKI_PROFIILI_URI'));
//    $profileEditUrl->mergeOptions([
//      'attributes' => [
//        'title' => t('If you want to change the information from Helsinki-profile you can do that by going to the Helsinki-profile from this link.'),
//      ],
//    ]);
//
//    $profileRefreshUrl = Url::fromRoute('form_tool_profile_data.refresh_profile_data');
//    $profileRefreshUrl->mergeOptions([
//      'attributes' => [
//        'title' => t('If the data from Helsinki-profile is old you can refresh the data by pressing this link.'),
//        'class' => 'profile-data__refresh-link',
//      ],
//    ]);
//
//    $elements['profile_data_links'] = [
//      '#type' => 'container',
//      '#attributes' => [
//        'class' => 'profile-data__links-wrapper',
//      ],
//      'editLink' => [
//        '#type' => 'link',
//        '#url' => $profileEditUrl,
//        '#attributes' => ['target' => '_blank'],
//        '#title' => t('Go to Helsinki-profile to edit your information.'),
//        '#suffix' => '(' . t('The link opens in a new tab', [], ['context' => 'Explanation for users that the link opens in a new tab instead of the expected current tab']) . ')',
//      ],
//      'refreshLink' => [
//        '#type' => 'link',
//        '#url' => $profileRefreshUrl,
//        '#title' => t('Refresh data', [], ['context' => 'Refresh data from Helsinki-profile link text']),
//      ],
//      'refreshSubmit' => [
//        '#type' => 'submit',
//        '#name' => 'refresh_profile',
//        '#submit' => [[static::class, 'profileDataRefreshSubmitHandler']],
//        '#attributes' => [
//          'class' => ['is-hidden', 'profile-refresh-data-button'],
//        ],
//        '#title' => 'Refresh profile data',
//        '#ajax' => [
//          'callback' => [static::class, 'profileDataRefreshAjaxCallback'],
//        ],
//        '#limit_validation_errors' => [],
//      ],
//      'messageContainer' => [
//        '#type' => 'container',
//        '#attributes' => [
//          'class' => ['profile-data-message-container'],
//        ],
//      ],
//    ];
//
//    $elements['#attached']['library'][] = 'form_tool_profile_data/form_tool_profile_data_refresh';

    return $elements;
  }

  /**
   * Submit handler for profile data refresh functionallity.
   */
  public static function profileDataRefreshSubmitHandler(array $form, FormStateInterface $form_state) {

    try {
      \Drupal::service('helfi_helsinki_profiili.userdata')->getUserProfileData(TRUE);
      $form_state->setStorage([
        'profile_update_text' => [
          'status' => [t('Profile data updated')],
        ],
      ]);
    }
    catch (TokenExpiredException $e) {
      $form_state->setStorage([
        'profile_update_text' => [
          'warning' => [t('Profile data update failed. Error has been logged.')],
        ],
      ]);
    }

    $form_state->setRebuild(TRUE);

    return $form;
  }

  /**
   * Ajax callback for the profile data refresh.
   */
  public static function profileDataRefreshAjaxCallback(array $form, FormStateInterface $form_state) {

    $response = new AjaxResponse();
    $message = $form_state->getStorage();

    $render = [
      '#theme' => 'status_messages',
      '#message_list' => $message['profile_update_text'],
      '#status_headings' => [
        'status' => t('Status message'),
        'error' => t('Error message'),
        'warning' => t('Warning message'),
      ],
      '#attributes' => ['toast' => 'top-right'],
    ];

    $renderedHtml = \Drupal::service('renderer')->render($render);

    $response->addCommand(new ReplaceCommand('form', $form));
    $response->addCommand(
      new ReplaceCommand(
        '.profile-data-message-container',
        $renderedHtml
      )
    );
    $response->addCommand(new InvokeCommand(
      '.profile-data__refresh-link',
      'focus'
    ));

    return $response;
  }

  /**
   * Performs the after_build callback.
   */
  public static function afterBuild(array $element, FormStateInterface $form_state) {
    // Add #states targeting the specific element and table row.
    preg_match('/^(.+)\[[^]]+]$/', $element['#name'], $match);
    $composite_name = $match[1];
    $element['#states']['disabled'] = [
      [':input[name="' . $composite_name . '[first_name]"]' => ['empty' => TRUE]],
      [':input[name="' . $composite_name . '[last_name]"]' => ['empty' => TRUE]],
    ];
    // Add .js-form-wrapper to wrapper (ie td) to prevent #states API from
    // disabling the entire table row when this element is disabled.
    $element['#wrapper_attributes']['class'][] = 'js-form-wrapper';
    return $element;
  }

  /**
   * Handle text value for the description fields.
   *
   * @param string|array $textValue
   *   String or array containing text values.
   *
   * @return array
   *   Returns render array.
   */
  private static function handleTextValue(string|array $textValue) : array {
    $description = is_array($textValue)
      ? implode(', ', $textValue)
      : $textValue;

    return [
      '#plain_text' => $description,
    ];
  }

  /**
   * Custom validator to validate primary phone number.
   *
   * @param array $element
   *   Form element.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   */
  public static function validatePhoneNumber(array $element, FormStateInterface $form_state) {
    $value = $element['#value'] ?? NULL;

    if (!empty($value)) {
      $valid = preg_match("/^\+?[\d]+\b/", $value);
      if (!$valid) {
        $form_state->setError($element, t('%name is not a valid number.', [
          '%name' => t('Primary phone'),
        ]));
      }
    }
  }

  /**
   * Custom validator to validate primary email.
   *
   * @param array $element
   *   Form element.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   */
  public static function validateEmail(array $element, FormStateInterface $form_state) {
    $value = $element['#value'] ?? NULL;
    $validator = new EmailValidator();
    $isValid = $validator->isValid($value);
    if (!$isValid) {
      $form_state->setError($element, t('%email is not a valid email address.', [
        '%email' => Xss::filter($value),
      ]));
    }
  }

  /**
   * If environment is set to debug mode, print messages.
   *
   * @param string $message
   *   Message string.
   * @param array $replacements
   *   Replacements array.
   */
  public static function debug(string $message, array $replacements) {
    $debug = getenv('DEBUG');

    if ($debug == 'true' || $debug === TRUE) {
      \Drupal::logger('form_tool_profile_data')->debug($message, $replacements);
    }

  }

}
