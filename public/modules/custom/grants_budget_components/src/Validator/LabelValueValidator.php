<?php

namespace Drupal\grants_budget_components\Validator;

use Drupal\Core\Form\FormStateInterface;

/**
 *
 */
class LabelValueValidator {

  /**
   * Form element validation for budget label / value combo.
   *
   * Note that #required is validated by _form_validate() already.
   */
  public static function validate(&$element, FormStateInterface $form_state, &$complete_form) {
    $value = $element['#value'];
    $parents = $element['#parents'];
    $field = array_pop($parents);
    $parent = $form_state->getValue($parents);

    switch ($field) {
      case 'value':
        $pair = 'label';
        break;

      case 'label':
        $pair = 'value';
        break;

      default:
        return;
    }

    $pair_value = $parent[$pair] ?? NULL;

    if (empty(trim($value)) && ($pair_value || trim($pair_value) !== '')) {
      $form_state->setError($element, t("%name can't be empty, when %pair has a value", ['%pair' => $pair]));
    }

  }

}
