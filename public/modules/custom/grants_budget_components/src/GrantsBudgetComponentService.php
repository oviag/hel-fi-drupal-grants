<?php

namespace Drupal\grants_budget_components;

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

        if ($itemName !== 'incomeGroupName') {
          $itemValues[] = [
            'ID' => $itemName,
            'label' => $itemDefinition->getLabel(),
            'value' => $item->getValue(),
            'valueType' => $valueTypes['jsonType'],
          ];
        }
        $items[$itemIndex] = $itemValues;
      }
    }
    return $items;
  }

}
