<?php

namespace Drupal\grants_handler;

use Twig\TwigTest;

/**
 * Twig extension.
 */
class GrantsHandlerTwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getTests(): array {
    return [
      new TwigTest('numeric', function ($value) {
        return is_numeric($value);
      }),
    ];
  }

}
