<?php

namespace Drupal\grants_applicant_info\TypedData\Definition;

use Drupal\Core\TypedData\ComplexDataDefinitionBase;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Define Application official data.
 */
class ApplicantInfoDefinition extends ComplexDataDefinitionBase {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(): array {
    if (!isset($this->propertyDefinitions)) {
      $info = &$this->propertyDefinitions;

      $info['applicantType'] = DataDefinition::create('string')
        ->setLabel('Nimi')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'applicantType',
        ]);

      $info['firstname'] = DataDefinition::create('string')
        ->setLabel('Nimi')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'firstname',
        ]);
      $info['lastname'] = DataDefinition::create('string')
        ->setLabel('Nimi')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'lastname',
        ]);
      $info['socialSecurityNumber'] = DataDefinition::create('string')
        ->setLabel('Nimi')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'socialSecurityNumber',
        ]);
      $info['street'] = DataDefinition::create('string')
        ->setLabel('Nimi')
        ->setSetting('jsonPath', [
          'compensation',
          'currentAddressInfoArray',
          'street',
        ]);
      $info['city'] = DataDefinition::create('string')
        ->setLabel('Nimi')
        ->setSetting('jsonPath', [
          'compensation',
          'currentAddressInfoArray',
          'city',
        ]);
      $info['postCode'] = DataDefinition::create('string')
        ->setLabel('Nimi')
        ->setSetting('jsonPath', [
          'compensation',
          'currentAddressInfoArray',
          'postCode',
        ]);
      $info['country'] = DataDefinition::create('string')
        ->setLabel('Nimi')
        ->setSetting('jsonPath', [
          'compensation',
          'currentAddressInfoArray',
          'country',
        ]);

      $info['applicantType'] = DataDefinition::create('string')
        ->setLabel('Hakijan tyyppi')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'applicantType',
        ])
        ->addConstraint('NotBlank');

      $info['companyNumber'] = DataDefinition::create('string')
        ->setLabel('Rekisterinumero')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'companyNumber',
        ]);

      $info['communityOfficialName'] = DataDefinition::create('string')
        ->setLabel('Yhteisön nimi')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'communityOfficialName',
        ]);

      $info['communityOfficialNameShort'] = DataDefinition::create('string')
        // ->setRequired(TRUE)
        ->setLabel('Yhteisön lyhenne')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'communityOfficialNameShort',
        ]);
      $info['registrationDate'] = DataDefinition::create('datetime_iso8601')
        ->setLabel('Rekisteröimispäivä')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'registrationDate',
        ]);

      $info['foundingYear'] = DataDefinition::create('string')
        ->setLabel('Perustamisvuosi')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'foundingYear',
        ]);
      $info['home'] = DataDefinition::create('string')
        ->setLabel('Kotipaikka')
        ->setSetting('jsonPath', ['compensation', 'applicantInfoArray', 'home']);

      $info['homePage'] = DataDefinition::create('string')
        ->setLabel('www-sivut')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'homePage',
        ])
        ->setSetting('defaultValue', "");

      $info['email'] = DataDefinition::create('email')
        ->setLabel('Sähköpostiosoite')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'email',
        ])
        ->setSetting('typeOverride', [
          'dataType' => 'email',
          'jsonType' => 'string',
        ])
        ->addConstraint('Email');

    }
    return $this->propertyDefinitions;
  }

}
