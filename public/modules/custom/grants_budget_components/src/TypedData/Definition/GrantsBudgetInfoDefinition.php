<?php

namespace Drupal\grants_budget_components\TypedData\Definition;

use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\DataDefinitionInterface;
use Drupal\Core\TypedData\ListDataDefinition;
use Drupal\Core\TypedData\MapDataDefinition;

/**
 * Define Budget Cost Static data.
 */
class GrantsBudgetInfoDefinition extends MapDataDefinition {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(): array {
    if (!isset($this->propertyDefinitions)) {
      $info = &$this->propertyDefinitions;

      $info['budget_other_income'] = $this->getOtherIncomeDefinition();
      $info['budget_other_cost'] = $this->getOtherCostDefinition();

      $info['costGroupName'] = DataDefinition::create('string')
        ->setSetting('jsonPath', [
          'costGroupName',
        ])
        ->setSetting('defaultValue', 'general')
        ->setSetting('fullItemValueCallback', [
          'service' => 'grants_budget_components.service',
          'method' => 'processGroupName',
        ]);

      $info['incomeGroupName'] = DataDefinition::create('string')
        ->setSetting('jsonPath', [
          'incomeGroupName',
        ])
        ->setSetting('defaultValue', 'general')
        ->setSetting('fullItemValueCallback', [
          'service' => 'grants_budget_components.service',
          'method' => 'processGroupName',
        ]);

    }

    return $this->propertyDefinitions;
  }

  /**
   * Sets the definition of a map property.
   *
   * @param string $name
   *   The name of the property to define.
   * @param \Drupal\Core\TypedData\DataDefinitionInterface|null $definition
   *   (optional) The property definition to set, or NULL to unset it.
   *
   * @return $this
   */
  public function setPropertyDefinition($name, DataDefinitionInterface $definition = NULL) {

    $this->getPropertyDefinitions();

    if (isset($definition)) {
      $this->propertyDefinitions[$name] = $definition;
    }
    else {
      unset($this->propertyDefinitions[$name]);
    }
    return $this;
  }

  /**
   * Helper function to get basic static income definition.
   *
   * @return \Drupal\grants_budget_components\TypedData\Definition\GrantsBudgetIncomeStaticDefinition
   *   Ready to use income static definition
   */
  public static function getStaticIncomeDefinition() {
    return GrantsBudgetIncomeStaticDefinition::create('grants_budget_income_static')
      ->setSetting('fullItemValueCallback', [
        'service' => 'grants_budget_components.service',
        'method' => 'processBudgetStaticValues',
      ])
      ->setSetting('webformDataExtracter', [
        'service' => 'grants_budget_components.service',
        'method' => 'extractToWebformData',
      ])
      ->setSetting('jsonPath', [
        'incomeRowsArrayStatic',
      ]);
  }

  /**
   * Helper function to get basic static cost definition.
   *
   * @return \Drupal\grants_budget_components\TypedData\Definition\GrantsBudgetCostStaticDefinition
   *   Ready to use cost static definition
   */
  public static function getStaticCostDefinition() {
    return GrantsBudgetCostStaticDefinition::create('grants_budget_cost_static')
      ->setSetting('fullItemValueCallback', [
        'service' => 'grants_budget_components.service',
        'method' => 'processBudgetStaticValues',
      ])
      ->setSetting('webformDataExtracter', [
        'service' => 'grants_budget_components.service',
        'method' => 'extractToWebformData',
      ])
      ->setSetting('jsonPath', [
        'costRowsArrayStatic',
      ]);
  }

  /**
   * Helper function to get basic other cost definition.
   *
   * @return \Drupal\grants_budget_components\TypedData\Definition\GrantsBudgetCostOtherDefinition
   *   Ready to use cost static definition
   */
  public static function getOtherCostDefinition() {
    return ListDataDefinition::create('grants_budget_cost_other')
      ->setSetting('fullItemValueCallback', [
        'service' => 'grants_budget_components.service',
        'method' => 'processBudgetOtherValues',
      ])
      ->setSetting('webformDataExtracter', [
        'service' => 'grants_budget_components.service',
        'method' => 'extractToWebformData',
      ])
      ->setSetting('jsonPath', [
        'otherCostRowsArrayStatic',
      ]);
  }

  /**
   * Helper function to get basic other income definition.
   *
   * @return \Drupal\grants_budget_components\TypedData\Definition\GrantsBudgetIncomeOtherDefinition
   *   Ready to use cost static definition
   */
  public static function getOtherIncomeDefinition() {
    return ListDataDefinition::create('grants_budget_income_other')
      ->setSetting('fullItemValueCallback', [
        'service' => 'grants_budget_components.service',
        'method' => 'processBudgetOtherValues',
      ])
      ->setSetting('webformDataExtracter', [
        'service' => 'grants_budget_components.service',
        'method' => 'extractToWebformData',
      ])
      ->setSetting('jsonPath', [
        'otherIncomeRowsArrayStatic',
      ]);
  }

}
