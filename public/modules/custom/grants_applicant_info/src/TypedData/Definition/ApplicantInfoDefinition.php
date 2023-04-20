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

      $info['email'] = DataDefinition::create('string')
        ->setLabel('Nimi')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'email',
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
        ])
        ->addConstraint('NotBlank');

      $info['communityOfficialName'] = DataDefinition::create('string')
        ->setLabel('Yhteisön nimi')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'communityOfficialName',
        ])
        ->addConstraint('NotBlank');

      $info['communityOfficialNameShort'] = DataDefinition::create('string')
        // ->setRequired(TRUE)
        ->setLabel('Yhteisön lyhenne')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'communityOfficialNameShort',
        ]);
      // ->addConstraint('NotBlank');
      $info['registrationDate'] = DataDefinition::create('datetime_iso8601')
        // ->setRequired(TRUE)
        ->setLabel('Rekisteröimispäivä')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'registrationDate',
        ])
        ->addConstraint('NotBlank');

      $info['foundingYear'] = DataDefinition::create('string')
        // ->setRequired(TRUE)
        ->setLabel('Perustamisvuosi')
        ->setSetting('jsonPath', [
          'compensation',
          'applicantInfoArray',
          'foundingYear',
        ]);
      // ->addConstraint('NotBlank');
      $info['home'] = DataDefinition::create('string')
        // ->setRequired(TRUE)
        ->setLabel('Kotipaikka')
        ->setSetting('jsonPath', ['compensation', 'applicantInfoArray', 'home'])
        ->addConstraint('NotBlank');

      $info['homePage'] = DataDefinition::create('string')
        // ->setRequired(TRUE)
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
        ->addConstraint('NotBlank')
        ->addConstraint('Email');

    }
    return $this->propertyDefinitions;
  }

}
