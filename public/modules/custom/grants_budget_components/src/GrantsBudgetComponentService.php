<?php

namespace Drupal\grants_budget_components;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\TypedData\ListInterface;
use Drupal\grants_handler\Plugin\WebformHandler\GrantsHandler;
use Drupal\grants_metadata\AtvSchema;

/**
 * Useful tools for budget components.
 */
class GrantsBudgetComponentService {

  const IGNORED_FIELDS = [
    'costGroupName',
    'incomeGroupName',
  ];

  /**
   * Parse budget income fields.
   *
   * @param \Drupal\Core\TypedData\ListInterface $property
   *   Property that is handled.
   *
   * @return array
   *   Processed items.
   */
  public static function processBudgetStaticValues(ListInterface $property): array {

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

        if (!in_array($itemName, self::IGNORED_FIELDS)) {
          $items[] = [
            'ID' => $itemName,
            'label' => $itemDefinition->getLabel(),
            'value' => (string) GrantsHandler::convertToFloat($item->getValue()),
            'valueType' => $valueTypes['jsonType'],
          ];
        }
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
        'value' => (string) GrantsHandler::convertToFloat($values['value']) ?? NULL,
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
   * @param array $jsonPath
   *   Json path as array.
   *
   * @return array
   *   Formatted data.
   */
  public static function getBudgetOtherValues(array $documentData, array $jsonPath): array {

    $retVal = [];
    $elements = NestedArray::getValue(
      $documentData,
      $jsonPath
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
   * @param array $jsonPath
   *   Json path as array.
   *
   * @return array
   *   Formatted Data.
   */
  public static function getBudgetStaticValues(array $documentData, array $jsonPath) {
    $retVal = [];
    $elements = NestedArray::getValue(
      $documentData,
      $jsonPath
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
   * Extract typed data to webform format based definition.
   *
   * @return array
   *
   *   Formatted data.
   */
  public static function extractToWebformData($definition, array $documentData) {

    $retVal = [];
    $jsonPath = $definition->getSetting('jsonPath');

    $pathLast = end($jsonPath);

    switch ($pathLast) {
      case 'incomeRowsArrayStatic':
      case 'costRowsArrayStatic':
        $retVal = self::getBudgetStaticValues($documentData, $jsonPath);
        break;

      case 'otherIncomeRowsArrayStatic':
      case 'otherCostRowsArrayStatic':
        $retVal = self::getBudgetOtherValues($documentData, $jsonPath);
        break;
    }

    return $retVal;
  }

  /**
   * Process group name.
   */
  public static function processGroupName($property) {
    return $property->getValue();
  }

}
