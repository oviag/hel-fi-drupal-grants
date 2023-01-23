<?php

namespace Drupal\grants_front_banner\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a grants front banner block.
 *
 * @Block(
 *   id = "grants_front_banner",
 *   admin_label = @Translation("Grants Front Banner"),
 *   category = @Translation("Oma Asiointi")
 * )
 */
class GrantsFrontBannerBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $logged_in = \Drupal::currentUser()->isAuthenticated();

    $build = [
      '#theme' => 'grants_front_banner',
      '#loggedin' => $logged_in,
    ];
    return $build;
  }

}
