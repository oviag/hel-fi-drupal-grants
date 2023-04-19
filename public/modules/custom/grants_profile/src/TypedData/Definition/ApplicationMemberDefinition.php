<?php

namespace Drupal\grants_profile\TypedData\Definition;

use Drupal\Core\TypedData\ComplexDataDefinitionBase;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Define Application member data.
 */
class ApplicationMemberDefinition extends ComplexDataDefinitionBase {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(): array {
    if (!isset($this->propertyDefinitions)) {
      $info = &$this->propertyDefinitions;

      $info['name'] = DataDefinition::create('string')
        ->setLabel('Nimi')
        ->setSetting('jsonPath', ['grantsProfile', 'membersArray', 'name'])
        ->setRequired(TRUE)
        ->addConstraint('NotBlank');

      $info['email'] = DataDefinition::create('string')
        ->setLabel('Sähköposti')
        ->setSetting('jsonPath', ['grantsProfile', 'membersArray', 'email'])
        ->addConstraint('Email')
        ->addConstraint('NotBlank')
        ->setRequired(TRUE);

      $info['phone'] = DataDefinition::create('string')
        ->setLabel('Puhelinnumero')
        ->setSetting('jsonPath', ['grantsProfile', 'membersArray', 'phone'])
        ->addConstraint('NotBlank')
        ->setRequired(TRUE);

      $info['additional'] = DataDefinition::create('string')
        ->setLabel('Lisätietoja')
        ->setSetting('jsonPath', ['grantsProfile', 'membersArray', 'additional']);

    }
    return $this->propertyDefinitions;
  }

}
