<?php

namespace Drupal\grants_premises\Element;

use Drupal\webform\Element\WebformCompositeBase;

/**
 * Provides a 'rent_income_composite'.
 *
 * Webform composites contain a group of sub-elements.
 *
 *
 * IMPORTANT:
 * Webform composite can not contain multiple value elements (i.e. checkboxes)
 * or composites (i.e. rent_income_composite)
 *
 * @FormElement("rent_income_composite")
 *
 * @see \Drupal\webform\Element\WebformCompositeBase
 */
class RentIncomeComposite extends WebformCompositeBase {

  /**
   * {@inheritdoc}
   */
  public function getInfo(): array {
    return parent::getInfo() + ['#theme' => 'rent_income_composite'];
  }

  /**
   * {@inheritdoc}
   */
  public static function getCompositeElements(array $element): array {
    $elements = [];
    $tOpts = ['context' => 'rent_income_composite'];

    $elements['premiseName'] = [
      '#type' => 'textfield',
      '#title' => t('Sports facility', [], $tOpts),
    ];

    $elements['dateBegin'] = [
      '#type' => 'date',
      '#title' => t('Start date'),
    ];

    $elements['dateEnd'] = [
      '#type' => 'date',
      '#title' => t('End date'),
    ];

    $elements['tenantName'] = [
      '#type' => 'textfield',
      '#title' => t("Tenant's name", [], $tOpts),
    ];

    $elements['hours'] = [
      '#type' => 'number',
      '#title' => t('Total hours', [], $tOpts),
    ];

    $elements['sum'] = [
      '#type' => 'number',
      '#title' => t('Rental income (€)', [], $tOpts),
    ];

    return $elements;
  }

}
