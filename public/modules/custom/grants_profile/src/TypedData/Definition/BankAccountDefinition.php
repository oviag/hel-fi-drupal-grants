<?php

namespace Drupal\grants_profile\TypedData\Definition;

use Drupal\Core\TypedData\ComplexDataDefinitionBase;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Define bank account data.
 */
class BankAccountDefinition extends ComplexDataDefinitionBase {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(): array {
    if (!isset($this->propertyDefinitions)) {
      $info = &$this->propertyDefinitions;

      $info['bankAccount'] = DataDefinition::create('string')
        ->setRequired(TRUE)
        ->setLabel('bankAccount')
        ->setSetting('jsonPath', [
          'grantsProfile',
          'bankAccountsArray',
          'bankAccount',
        ])
        ->addConstraint('NotEmptyValue')
        ->addConstraint('ValidIban');

      $info['ownerName'] = DataDefinition::create('string')
        ->setRequired(TRUE)
        ->setLabel('ownerName')
        ->setSetting('jsonPath', [
          'grantsProfile',
          'bankAccountsArray',
          'ownerName',
        ])
        ->addConstraint('NotEmptyValue');

      $info['ownerSsn'] = DataDefinition::create('string')
        ->setRequired(TRUE)
        ->setLabel('ownerSsn')
        ->setSetting('jsonPath', [
          'grantsProfile',
          'bankAccountsArray',
          'ownerSsn',
        ])
        ->addConstraint('NotEmptyValue')
        ->addConstraint('ValidSsn');

      $info['confirmationFile'] = DataDefinition::create('string')
        ->setRequired(TRUE)
        ->setLabel('File attachment to validate bank account')
        ->setSetting('jsonPath', [
          'grantsProfile',
          'bankAccountsArray',
          'confirmationFile',
        ])
        ->addConstraint('NotEmptyValue');

    }
    return $this->propertyDefinitions;
  }

}
