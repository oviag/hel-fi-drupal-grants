<?php

namespace Drupal\grants_metadata\TypedData\Definition;

use Drupal\Core\TypedData\ComplexDataDefinitionBase;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\ListDataDefinition;

/**
 * Define Yleisavustushakemus data.
 */
class KuvaProjektiDefinition extends ComplexDataDefinitionBase {

  use ApplicationDefinitionTrait;

  /**
   * Base data definitions for all.
   *
   * @return array
   *   Property definitions.
   */
  public function getPropertyDefinitions(): array {
    if (!isset($this->propertyDefinitions)) {

      $info = &$this->propertyDefinitions;

      foreach ($this->getBaseProperties() as $key => $property) {
        $info[$key] = $property;
      }

      $info['subventions'] = ListDataDefinition::create('grants_metadata_compensation_type')
        ->setLabel('compensationArray')
        ->setSetting('jsonPath', [
          'compensation',
          'compensationInfo',
          'compensationArray',
        ]);

      $info['ensisijainen_taiteen_ala'] = DataDefinition::create('string')
        ->setLabel('Ensisijainen taiteenala')
        ->setSetting('jsonPath', [
          'compensation',
          'compensationInfo',
          'generalInfoArray',
          'primaryArt',
        ]);

      $info['hankkeen_nimi'] = DataDefinition::create('string')
        ->setLabel('Hankkeen nimi')
        ->setSetting('jsonPath', [
          'compensation',
          'compensationInfo',
          'generalInfoArray',
          'nameOfEvent',
        ]);

      $info['hankkeen_nimi'] = DataDefinition::create('string')
        ->setLabel('Hankkeen nimi')
        ->setSetting('jsonPath', [
          'compensation',
          'compensationInfo',
          'generalInfoArray',
          'nameOfEvent',
        ]);

      $info['hankkeen_tai_toiminnan_lyhyt_esittelyteksti'] = DataDefinition::create('string')
        ->setLabel('Hankkeen tai toiminnan lyhyt esittelyteksti')
        ->setSetting('jsonPath', [
          'compensation',
          'compensationInfo',
          'generalInfoArray',
          'purpose',
        ]);

      $info['kokoaikainen_henkilosto'] = DataDefinition::create('string')
        ->setLabel('Kokoaikainen henkilöstö')
        ->setSetting('jsonPath', [
          'compensation',
          'communityInfo',
          'generalCommunityInfoArray',
          'staffPeopleFulltime',
        ]);

      $info['kokoaikainen_henkilosto'] = DataDefinition::create('string')
        ->setLabel('Kokoaikainen henkilöstö')
        ->setSetting('jsonPath', [
          'compensation',
          'communityInfo',
          'generalCommunityInfoArray',
          'staffPeopleFulltime',
        ]);

      $info['osa_aikainen_henkilosto'] = DataDefinition::create('string')
        ->setLabel('	Osa-aikainen henkilöstö')
        ->setSetting('jsonPath', [
          'compensation',
          'communityInfo',
          'generalCommunityInfoArray',
          'staffPeopleParttime',
        ]);

      $info['vapaaehtoinen_henkilosto'] = DataDefinition::create('string')
        ->setLabel('Vapaaehtoinen henkilöstö')
        ->setSetting('jsonPath', [
          'compensation',
          'communityInfo',
          'generalCommunityInfoArray',
          'staffPeopleVoluntary',
        ]);

      $info['toiminta_taiteelliset_lahtokohdat'] = DataDefinition::create('string')
        ->setLabel('Kuvaa toiminnan taiteellisia lähtökohtia ja tavoitteita, taiteellista ammattimaisuutta sekä asemaa taiteen kentällä.')
        ->setSetting('jsonPath', [
          'compensation',
          'activityBasisInfo',
          'activityBasisArray',
          'toiminta_taiteelliset_lahtokohdat',
        ]);

      $info['toiminta_tasa_arvo'] = DataDefinition::create('string')
        ->setLabel('Miten monimuotoisuus ja tasa-arvo toteutuu ja näkyy toiminnan järjestäjissä ja organisaatioissa sekä toiminnan sisällöissä? Minkälaisia toimenpiteitä, resursseja ja osaamista on asian edistämiseksi?')
        ->setSetting('jsonPath', [
          'compensation',
          'activityBasisInfo',
          'activityBasisArray',
          'toiminta_tasa_arvo',
        ]);

      $info['toiminta_saavutettavuus'] = DataDefinition::create('string')
        ->setLabel('Miten toiminta tehdään kaupunkilaiselle sosiaalisesti, kulttuurisesti, kielellisesti, taloudellisesti, fyysisesti, alueellisesti tai muutoin mahdollisimman saavutettavaksi? Minkälaisia toimenpiteitä, resursseja ja osaamista on asian edistämiseksi?')
        ->setSetting('jsonPath', [
          'compensation',
          'activityBasisInfo',
          'activityBasisArray',
          'toiminta_saavutettavuus',
        ]);

      $info['toiminta_yhteisollisyys'] = DataDefinition::create('string')
        ->setLabel('Miten toiminta vahvistaa yhteisöllisyyttä, verkostomaista yhteistyöskentelyä ja miten kaupunkilaisten on mahdollista osallistua toiminnan eri vaiheisiin? Minkälaisia toimenpiteitä, resursseja ja osaamista on asian edistämiseksi?')
        ->setSetting('jsonPath', [
          'compensation',
          'activityBasisInfo',
          'activityBasisArray',
          'toiminta_yhteisollisyys',
        ]);

      $info['toiminta_kohderyhmat'] = DataDefinition::create('string')
        ->setLabel('Keitä toiminnalla tavoitellaan? Miten kyseiset kohderyhmät aiotaan tavoittaa ja mitä osaamista näiden kanssa työskentelyyn on?')
        ->setSetting('jsonPath', [
          'compensation',
          'activityBasisInfo',
          'activityBasisArray',
          'toiminta_kohderyhmat',
        ]);

      $info['toiminta_ammattimaisuus'] = DataDefinition::create('string')
        ->setLabel('Kuvaa toiminnan järjestämisen ammattimaisuutta ja organisoimista')
        ->setSetting('jsonPath', [
          'compensation',
          'activityBasisInfo',
          'activityBasisArray',
          'toiminta_ammattimaisuus',
        ]);

      $info['toiminta_ekologisuus'] = DataDefinition::create('string')
        ->setLabel('Miten ekologisuus huomioidaan toiminnan järjestämisessä? Minkälaisia toimenpiteitä, resursseja ja osaamista on asian edistämiseksi?')
        ->setSetting('jsonPath', [
          'compensation',
          'activityBasisInfo',
          'activityBasisArray',
          'toiminta_ekologisuus',
        ]);

      $info['toiminta_yhteistyokumppanit'] = DataDefinition::create('string')
        ->setLabel('Nimeä keskeisimmät yhteistyökumppanit ja kuvaa yhteistyön muotoja ja ehtoja.')
        ->setSetting('jsonPath', [
          'compensation',
          'activityBasisInfo',
          'activityBasisArray',
          'toiminta_yhteistyokumppanit',
        ]);

    }
    return $this->propertyDefinitions;
  }

  /**
   * Override property definition.
   *
   * @param string $name
   *   Property name.
   *
   * @return \Drupal\Core\TypedData\DataDefinitionInterface|void|null
   *   Property definition.
   */
  public function getPropertyDefinition($name) {
    $retval = parent::getPropertyDefinition($name);
    return $retval;
  }

}
