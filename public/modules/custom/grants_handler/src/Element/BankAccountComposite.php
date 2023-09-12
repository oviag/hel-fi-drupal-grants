<?php

namespace Drupal\grants_handler\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Element\WebformCompositeBase;

/**
 * Provides a 'bank_account_composite'.
 *
 * Webform composites contain a group of sub-elements.
 *
 *
 * IMPORTANT:
 * Webform composite can not contain multiple value elements (i.e. checkboxes)
 * or composites (i.e. bank_account_composite)
 *
 * @FormElement("bank_account_composite")
 *
 * @see \Drupal\webform\Element\WebformCompositeBase
 * @see \Drupal\grants_handler\Element\WebformExampleComposite
 */
class BankAccountComposite extends WebformCompositeBase {

  /**
   * {@inheritdoc}
   */
  public function getInfo(): array {
    return parent::getInfo() + [
      '#theme' => 'bank_account_composite',
      '#after_build' => [[get_called_class(), 'alterBankComposite']],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function getCompositeElements(array $element): array {
    $elements = [];

    $elements['account_number_select'] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#title' => t('Select bank account'),
      '#options' => [],
      '#after_build' => [[get_called_class(), 'buildAccountOptions']],
      '#attributes' => [
        'class' => [],
      ],
    ];
    if (isset($element['#help'])) {
      $elements['account_number_select']['#help'] = $element['#help'];
    }

    $elements['account_number'] = [
      '#type' => 'hidden',
    ];
    $elements['account_number_owner_name'] = [
      '#title' => t('Bank account owner name'),
      '#type' => 'hidden',
    ];
    $elements['account_number_ssn'] = [
      '#title' => t('Bank account owner SSN'),
      '#type' => 'hidden',
    ];

    return $elements;
  }

  /**
   * Build options for bank account select.
   *
   * @param array $element
   *   Element to add things to.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   *
   * @return array
   *   Edited element.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public static function buildAccountOptions(array $element, FormStateInterface $form_state): array {

    $user = \Drupal::currentUser();
    $roles = $user->getRoles();

    if (!in_array('helsinkiprofiili', $roles)) {
      return [];
    }

    /** @var \Drupal\grants_profile\GrantsProfileService $grantsProfileService */
    $grantsProfileService = \Drupal::service('grants_profile.service');

    $selectedCompany = $grantsProfileService->getSelectedRoleData();
    $profileData = $grantsProfileService->getGrantsProfileContent($selectedCompany ?? '');

    $accOoptions = [
      '' => '-' . t('Select account') . '-',
    ];

    if (!isset($profileData["bankAccounts"])) {
      return $element;
    }

    foreach ($profileData["bankAccounts"] as $account) {
      $accOoptions[$account['bankAccount']] = $account['bankAccount'];
    }

    $element['#options'] = $accOoptions;

    $errorStorage = $form_state->getStorage();

    if (isset($errorStorage['errors']['bank_account'])) {
      $element['#attributes']['class'][] = 'has-error';
      $element['#attributes']['error_label'] = $errorStorage['errors']['bank_account']['label'];
    }
    return $element;
  }

  /**
   * Remove the extra help from the container element.
   *
   * @param array $element
   *   Element to add things to.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   *
   * @return array
   *   Edited element.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public static function alterBankComposite(array $element, FormStateInterface $form_state): array {
    unset($element['#help']);
    return $element;
  }

}
