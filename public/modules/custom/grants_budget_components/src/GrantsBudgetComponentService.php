<?php

namespace Drupal\grants_budget_components;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\TypedData\ListInterface;
use Drupal\grants_metadata\AtvSchema;

/**
 * Useful tools for budget components.
 */
class GrantsBudgetComponentService {

  /**
   * Parse budget income fields.
   *
   * @param \Drupal\Core\TypedData\ListInterface $property
   *   Property that is handled.
   *
   * @return array
   *   Processed items.
   */
  public static function processBudgetIncomeStatic(ListInterface $property): array {

    $items = [];

    $dataDefinition = $property->getDataDefinition();
    $usedFields = $dataDefinition->getSetting('fieldsForApplication');

    foreach ($property as $p) {
      foreach ($p as $item) {
        $itemName = $item->getName();

        // If this item is not selected for jsonData.
        if (!in_array($itemName, $usedFields)) {
          // Just continue...
          continue;
        }
        // Get item value types from item definition.
        $itemDefinition = $item->getDataDefinition();
        $valueTypes = AtvSchema::getJsonTypeForDataType($itemDefinition);

        if ($itemName === 'incomeGroupName') {
        }
        else {
          $items[] = [
            'ID' => $itemName,
            'label' => $itemDefinition->getLabel(),
            'value' => $item->getValue(),
            'valueType' => $valueTypes['jsonType'],
          ];
        }
      }
    }
    return $items;
  }

  /**
   * Parse budget income fields.
   *
   * @param \Drupal\Core\TypedData\ListInterface $property
   *   Property that is handled.
   *
   * @return array
   *   Processed items.
   */
  public static function processBudgetCostStatic(ListInterface $property): array {

    $items = [];

    $dataDefinition = $property->getDataDefinition();
    $usedFields = $dataDefinition->getSetting('fieldsForApplication');

    foreach ($property as $itemIndex => $p) {
      $itemValues = [];
      foreach ($p as $item) {
        $itemName = $item->getName();

        // If this item is not selected for jsonData.
        if (!in_array($itemName, $usedFields)) {
          // Just continue...
          continue;
        }
        // Get item value types from item definition.
        $itemDefinition = $item->getDataDefinition();
        $valueTypes = AtvSchema::getJsonTypeForDataType($itemDefinition);

        if ($itemName === 'costGroupName') {
        }
        else {
          $itemValues[] = [
            'ID' => $itemName,
          // @todo Real labels.
            'label' => $itemName,
            'value' => $item->getValue(),
            'valueType' => $valueTypes['jsonType'],
          ];
        }
        $items[$itemIndex] = $itemValues;
      }
    }
    return $items;
  }

  /**
   * Format Other Income/Cost values to ATV Schema format.
   *
   * @param \Drupal\Core\TypedData\ListInterface $property
   *   ListInterface property.
   *
   * @return array
   *   Formatted data.
   */
  public static function processBudgetOtherValues(ListInterface $property): array {
    $items = [];

    foreach ($property as $itemIndex => $p) {
      $values = $p->getValues();
      $itemValues = [
        'ID' => '123',
        'label' => $values['label'] ?? NULL,
        'value' => $values['value'] ?? NULL,
        'valueType' => 'double',
      ];

      $items[$itemIndex] = $itemValues;
    }
    return $items;
  }

  /**
   * Transform ATV Data to Webform.
   *
   * @param array $documentData
   *   Document data from ATV.
   *
   * @return array
   *   Formatted data.
   */
  public static function getBudgetIncomeOtherValues(array $documentData): array {

    $retVal = [];
    $elements = NestedArray::getValue(
      $documentData,
      [
        'compensation',
        'budgetInfo',
        'incomeGroupsArrayStatic',
        'otherIncomeRowsArrayStatic',
      ]
    );

    if (!empty($elements)) {
      $retVal = array_map(function ($e) {
        return [
          'label' => $e['label'] ?? NULL,
          'value' => $e['value'] ?? NULL,
        ];
      }, $elements);
    }

    return $retVal;
  }

  /**
   * Transform ATV Data to Webform.
   *
   * @param array $documentData
   *   Document data from ATV.
   *
   * @return array
   *   Formatted data.
   */
  public static function getBudgetCostOtherValues(array $documentData): array {

    $retVal = [];
    $elements = NestedArray::getValue(
      $documentData,
      [
        'compensation',
        'budgetInfo',
        'costGroupsArrayStatic',
        'otherCostRowsArrayStatic',
      ]
    );

    if (!empty($elements)) {
      $retVal = array_map(function ($e) {
        return [
          'label' => $e['label'] ?? NULL,
          'value' => $e['value'] ?? NULL,
        ];
      }, $elements);
    }

    return $retVal;
  }

  /**
   * Get Budget income static values in webform format.
   *
   * @param array $documentData
   *   ATV document data.
   *
   * @return array
   *   Formatted Data.
   */
  public static function getBudgetIncomeStaticValues(array $documentData) {
    $retVal = [];
    $elements = NestedArray::getValue(
      $documentData,
      [
        'compensation',
        'budgetInfo',
        'incomeGroupsArrayStatic',
        'incomeRowsArrayStatic',
      ]
    );

    if (!empty($elements)) {

      $values = [];
      foreach ($elements as $row) {
        $values[$row['ID']] = $row['value'];
      }
      $retVal[] = $values;

    }
    return $retVal;
  }

  /**
   * Get Budget cost static values in webform format.
   *
   * @param array $documentData
   *   ATV document data.
   *
   * @return array
   *   Formatted Data.
   */
  public static function getBudgetCostStaticValues(array $documentData) {
    $retVal = [];
    $elements = NestedArray::getValue(
      $documentData,
      [
        'compensation',
        'budgetInfo',
        'costGroupsArrayStatic',
        'costRowsArrayStatic',
      ]
    );

    if (!empty($elements)) {
      $values = [];
      foreach (reset($elements) as $row) {
        $values[$row['ID']] = $row['value'];
      }
      $retVal[] = $values;
    }

    return $retVal;
  }

}
