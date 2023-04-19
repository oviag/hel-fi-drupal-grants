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
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['compensation'] = DataDefinition::create('string')
        ->setLabel('Haettu avustus')
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['plannedOtherCompensations'] = DataDefinition::create('string')
        ->setLabel('Muut avustukset')
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['sponsorships'] = DataDefinition::create('string')
        ->setLabel('Yksityinen rahoitus (esim. sponsorointi, yritysyhteistyö,lahjoitukset)')
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['entryFees'] = DataDefinition::create('string')
        ->setLabel('Pääsy- ja osallistumismaksut')
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['sales'] = DataDefinition::create('string')
        ->setLabel('Muut oman toiminnan tulot')
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['ownFunding'] = DataDefinition::create('string')
        ->setLabel('Yhteisön oma rahoitus')
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['plannedTotalIncome'] = DataDefinition::create('string')
        ->setLabel('Ehdotetut tulot yhteensä Euroa')
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['otherCompensationFromCity'] = DataDefinition::create('string')
        ->setLabel('Helsingin kaupungin kulttuuripalveluiden toiminta-avustus')
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['otherCompensations'] = DataDefinition::create('string')
        ->setLabel('Muut avustukset')
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['totalIncome'] = DataDefinition::create('string')
        ->setLabel('Tulot yhteensä')
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['totalIncomeWithoutSubventions'] = DataDefinition::create('string')
        ->setLabel('Tulot ilman avustuksia')
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['shareOfIncomeWithoutSubventions'] = DataDefinition::create('string')
        ->setLabel('Muiden kuin avustusten osuus tuloista')
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

      $info['donations'] = DataDefinition::create('string')
        ->setSetting('typeOverride', [
          'dataType' => 'string',
          'jsonType' => 'double',
        ]);

    }
    return $this->propertyDefinitions;
  }

}
