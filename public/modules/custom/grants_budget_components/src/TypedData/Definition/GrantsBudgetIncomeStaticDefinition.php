<?php

namespace Drupal\grants_budget_components\TypedData\Definition;

use Drupal\Core\TypedData\ComplexDataDefinitionBase;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Define Budget Income Static data.
 */
class GrantsBudgetIncomeStaticDefinition extends ComplexDataDefinitionBase {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(): array {
    if (!isset($this->propertyDefinitions)) {
      $info = &$this->propertyDefinitions;

      $info['incomeGroupName'] = DataDefinition::create('string')
        ->setSetting('jsonPath', [
          'budgetInfo',
          'incomeGroupsArrayStatic',
          'incomeGroupName',
        ])
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['compensation'] = DataDefinition::create('string')
        ->setLabel('Haettu avustus')
        ->setSetting('jsonPath', [
          'budgetInfo',
          'incomeGroupsArrayStatic',
          'compensation',
        ])
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['plannedOtherCompensations'] = DataDefinition::create('string')
        ->setLabel('Muut avustukset')
        ->setSetting('jsonPath', [
          'budgetInfo',
          'incomeGroupsArrayStatic',
          'plannedOtherCompensations',
        ])
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['sponsorships'] = DataDefinition::create('string')
        ->setLabel('Yksityinen rahoitus (esim. sponsorointi, yritysyhteistyö,lahjoitukset)')
        ->setSetting('jsonPath', [
          'budgetInfo',
          'incomeGroupsArrayStatic',
          'sponsorships',
        ])
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['entryFees'] = DataDefinition::create('string')
        ->setLabel('Pääsy- ja osallistumismaksut')
        ->setSetting('jsonPath', [
          'budgetInfo',
          'incomeGroupsArrayStatic',
          'entryFees',
        ])
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['sales'] = DataDefinition::create('string')
        ->setLabel('Muut oman toiminnan tulot')
        ->setSetting('jsonPath', [
          'budgetInfo',
          'incomeGroupsArrayStatic',
          'sales',
        ])
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['ownFunding'] = DataDefinition::create('string')
        ->setLabel('Yhteisön oma rahoitus')
        ->setSetting('jsonPath', [
          'budgetInfo',
          'incomeGroupsArrayStatic',
          'ownFunding',
        ])
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['plannedTotalIncome'] = DataDefinition::create('string')
        ->setLabel('Ehdotetut tulot yhteensä Euroa')
        ->setSetting('jsonPath', [
          'budgetInfo',
          'incomeGroupsArrayStatic',
          'plannedTotalIncome',
        ])
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['otherCompensationFromCity'] = DataDefinition::create('string')
        ->setLabel('Helsingin kaupungin kulttuuripalveluiden toiminta-avustus')
        ->setSetting('jsonPath', [
          'budgetInfo',
          'incomeGroupsArrayStatic',
          'otherCompensationFromCity',
        ])
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['otherCompensations'] = DataDefinition::create('string')
        ->setLabel('Muut avustukset')
        ->setSetting('jsonPath', [
          'budgetInfo',
          'incomeGroupsArrayStatic',
          'otherCompensations',
        ])
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['totalIncome'] = DataDefinition::create('string')
        ->setLabel('Tulot yhteensä')
        ->setSetting('jsonPath', [
          'budgetInfo',
          'incomeGroupsArrayStatic',
          'totalIncome',
        ])
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['totalIncomeWithoutSubventions'] = DataDefinition::create('string')
        ->setLabel('Tulot ilman avustuksia')
        ->setSetting('jsonPath', [
          'budgetInfo',
          'incomeGroupsArrayStatic',
          'totalIncomeWithoutSubventions',
        ])
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['shareOfIncomeWithoutSubventions'] = DataDefinition::create('string')
        ->setLabel('Muiden kuin avustusten osuus tuloista')
        ->setSetting('jsonPath', [
          'budgetInfo',
          'incomeGroupsArrayStatic',
          'shareOfIncomeWithoutSubventions',
        ])
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['donations'] = DataDefinition::create('string')
        ->setSetting('jsonPath', [
          'budgetInfo',
          'incomeGroupsArrayStatic',
          'donations',
        ])
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

    }
    return $this->propertyDefinitions;
  }

}
