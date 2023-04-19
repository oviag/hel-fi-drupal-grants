<?php

namespace Drupal\grants_budget_components\TypedData\Definition;

use Drupal\Core\TypedData\ComplexDataDefinitionBase;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\grants_budget_components\Element\GrantsBudgetCostStatic;

/**
 * Define Budget Cost Static data.
 */
class GrantsBudgetCostStaticDefinition extends ComplexDataDefinitionBase {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(): array {
    if (!isset($this->propertyDefinitions)) {
      $info = &$this->propertyDefinitions;

      $info['costGroupName'] = DataDefinition::create('string');

      $fieldNames = array_keys(GrantsBudgetCostStatic::getFieldNames());

      foreach ($fieldNames as $fieldName) {
        $info[$fieldName] = DataDefinition::create('string')
          ->setLabel('Haettu avustus')
          ->setSetting('typeOverride', [
            'dataType' => 'string',
            'jsonType' => 'double',
          ]);

      }

    }
    return $this->propertyDefinitions;
  }

}
