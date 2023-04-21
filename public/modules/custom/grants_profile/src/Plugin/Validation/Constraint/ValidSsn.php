<?php

namespace Drupal\grants_profile\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks that the submitted value is valid Finnish social security number.
 *
 * @Constraint(
 *   id = "ValidSsn",
 *   label = @Translation("Valid Finnish SSN", context = "Validation"),
 *   type = "string"
 * )
 */
class ValidSsn extends Constraint {

  /**
   * The message that will be shown if the value is not valid.
   *
   * @var string
   */
  public string $notValidSsn = '%value is not valid Finnish social security number';

}
