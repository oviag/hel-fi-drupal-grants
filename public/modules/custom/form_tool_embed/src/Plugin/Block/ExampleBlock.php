<?php

namespace Drupal\form_tool_embed\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "form_tool_embed_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("Form Tool Embed")
 * )
 */
class ExampleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['content'] = [
      '#markup' => $this->t('It works!'),
    ];
    return $build;
  }

}
