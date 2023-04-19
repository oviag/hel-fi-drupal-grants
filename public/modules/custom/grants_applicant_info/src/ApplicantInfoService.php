<?php

namespace Drupal\grants_applicant_info;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\TypedData\ComplexDataInterface;
use Drupal\Core\TypedData\ListInterface;
use Drupal\grants_metadata\AtvSchema;

/**
 * ApplicantInfoService service.
 */
class ApplicantInfoService {

  /**
   * Since this is full property provider, we need to return full json array
   * to be merged.
   */
  public function processApplicantInfo(ComplexDataInterface $property) {

    $retval = [];
    $dataDefinition = $property->getDataDefinition();
    $usedFields = $dataDefinition->getSetting('fieldsForApplication');

    foreach ($property as $itemIndex => $p) {
      $pDef = $p->getDataDefinition();
      $pJsonPath = $pDef->getSetting('jsonPath');
      $defaultValue = $pDef->getSetting('defaultValue');
      $valueCallback = $pDef->getSetting('valueCallback');
      $temp = $pJsonPath;
      $elementName = array_pop($temp);

      $itemTypes = AtvSchema::getJsonTypeForDataType($pDef);
      $itemValue = AtvSchema::getItemValue($itemTypes, $p->getValue(), $defaultValue, $valueCallback);

      $pValue = [
        'ID' => $elementName,
        'value' => $itemValue,
        'valueType' => $itemTypes['jsonType'],
        'label' => $pDef->getLabel(),
      ];

      NestedArray::setValue($retval, $pJsonPath, $pValue);
    }

    if ($retval["compensation"]["applicantInfoArray"]["applicantType"]['value'] == 'registered_community') {
      unset($retval["compensation"]["applicantInfoArray"]["firstname"]);
      unset($retval["compensation"]["applicantInfoArray"]["lastname"]);
      unset($retval["compensation"]["applicantInfoArray"]["socialSecurityNumber"]);
    }
    if ($retval["compensation"]["applicantInfoArray"]["applicantType"]['value'] == 'unregistered_community') {
      unset($retval["compensation"]["applicantInfoArray"]["firstname"]);
      unset($retval["compensation"]["applicantInfoArray"]["lastname"]);
      unset($retval["compensation"]["applicantInfoArray"]["socialSecurityNumber"]);
      unset($retval["compensation"]["applicantInfoArray"]["companyNumber"]);
      unset($retval["compensation"]["applicantInfoArray"]["registrationDate"]);
      unset($retval["compensation"]["applicantInfoArray"]["foundingYear"]);
      unset($retval["compensation"]["applicantInfoArray"]["home"]);
      unset($retval["compensation"]["applicantInfoArray"]["homePage"]);
    }
    if ($retval["compensation"]["applicantInfoArray"]["applicantType"]['value'] == 'private_person') {
      unset($retval["compensation"]["applicantInfoArray"]["companyNumber"]);
      unset($retval["compensation"]["applicantInfoArray"]["communityOfficialName"]);
      unset($retval["compensation"]["applicantInfoArray"]["communityOfficialNameShort"]);
      unset($retval["compensation"]["applicantInfoArray"]["registrationDate"]);
      unset($retval["compensation"]["applicantInfoArray"]["foundingYear"]);
      unset($retval["compensation"]["applicantInfoArray"]["home"]);
      unset($retval["compensation"]["applicantInfoArray"]["homePage"]);
    }

    return $retval;
  }

  /**
   * @param $jsonStructure
   *
   * @return array $webformStructure
   */
  public function generateWebformData($jsonStructure){

    // ...

    // webform rakenne
    return [

    ];

  }

}
