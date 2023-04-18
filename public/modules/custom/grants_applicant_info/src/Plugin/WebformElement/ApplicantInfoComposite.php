<?php

namespace Drupal\grants_applicant_info\Plugin\WebformElement;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformElement\WebformCompositeBase;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Provides a 'form_tool_profile_data' element.
 *
 * @WebformElement(
 *   id = "applicant_info_composite",
 *   label = @Translation("Grants applicant info"),
 *   description = @Translation("Provides webform component to gather details from helsinki profile."),
 *   category = @Translation("Helfi"),
 *   multiline = TRUE,
 *   composite = TRUE,
 *   states_wrapper = TRUE,
 * )
 *
 * @see \Drupal\webform\Plugin\WebformElement\WebformCompositeBase
 * @see \Drupal\webform\Plugin\WebformElementBase
 * @see \Drupal\webform\Plugin\WebformElementInterface
 * @see \Drupal\webform\Annotation\WebformElement
 */
class ApplicantInfoComposite extends WebformCompositeBase {

  /**
   * {@inheritdoc}
   */
  protected function defineDefaultProperties() {
    // Here you define your webform element's default properties,
    // which can be inherited.
    //
    // @see \Drupal\webform\Plugin\WebformElementBase::defaultProperties
    // @see \Drupal\webform\Plugin\WebformElementBase::defaultBaseProperties
    return [
      'noauth' => [],
      'weak' => [],
      'strong' => [],
    ] + parent::defineDefaultProperties();
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state): array {


    return $form;
  }

  /**
   * Format a composite as a list of HTML items.
   *
   * @param array $element
   *   An element.
   * @param \Drupal\webform\WebformSubmissionInterface $webform_submission
   *   A webform submission.
   * @param array $options
   *   An array of options.
   *
   * @return array|string
   *   A composite as a list of HTML items.
   */
  protected function formatCompositeHtmlItems(array $element, WebformSubmissionInterface $webform_submission, array $options = []) {
    $value = $this->getValue($element, $webform_submission, $options);
    $titles = ApplicantInfoComposite::getFieldSelections();
    $lines = [];

    foreach ($value as $fieldName => $fieldValue) {
      foreach ($titles as $auth => $fields) {
        if (
          isset($fields[$fieldName]) &&
          !array_key_exists($fieldName, $lines)
        ) {
          $items[$fieldName] = [
            '#type' => 'inline_template',
            '#template' => '<label>{{ title }}:</label> {{ value }}',
            '#context' => [
              'title' => $fields[$fieldName]->render(),
              'value' => $fieldValue,
            ],
          ];
        }
      }
    }
    return $items;
  }

  /**
   * {@inheritdoc}
   */
  protected function formatHtmlItemValue(array $element, WebformSubmissionInterface $webform_submission, array $options = []) {
    return $this->formatTextItemValue($element, $webform_submission, $options);
  }

  /**
   * {@inheritdoc}
   */
  protected function formatTextItemValue(array $element, WebformSubmissionInterface $webform_submission, array $options = []) {
    $value = $this->getValue($element, $webform_submission, $options);

    $lines = [];
    foreach ($value as $fieldName => $fieldValue) {
      $lines[] = $fieldValue;

    }
    return $lines;
  }

}
