<?php

namespace Drupal\grants_profile\Plugin\Validation\Constraint;

use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the ValidSsn constraint.
 */
class ValidSsnValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($value, $constraint) {
    if (!preg_match("/([0-2][0-9]|3[0-1])(0[0-9]|1[0-2])([0-9][0-9])([\+\-A])([[:digit:]]{3})([A-Z]|[[:digit:]])/", $value)) {
      $this->context->addViolation($constraint->notValidSsn, ['%value' => $value]);
    }
  }

}
