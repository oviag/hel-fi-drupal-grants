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
  // Public function getFunctions(): array {
  // return [
  // new \Twig\TwigFunction('foo', function ($argument = NULL) {
  // return 'Foo: ' . $argument;
  // }),
  // ];
  // }.

  /**
   * {@inheritdoc}
   */
  // Public function getFilters(): array {
  // return [
  // new \Twig\TwigFilter('bar', function ($text) {
  // return str_replace('bar', 'BAR', $text);
  // }),
  // ];
  // }.

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
