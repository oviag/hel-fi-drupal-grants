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
use Drupal\webform\Entity\Webform;
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

    $webForm = Webform::load($element["#webform"]);
    $thirdPartySettings = $webForm->getThirdPartySettings('grants_metadata');


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
