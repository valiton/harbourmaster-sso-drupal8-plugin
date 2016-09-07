<?php

namespace Drupal\harbourmaster\Plugin\Block;

/**
 * Provides a 'Confirmation' block.
 *
 * @Block(
 *   id = "harbourmaster_confirmation_block",
 *   admin_label = @Translation("HMS Confirmation block"),
 * )
 */
class Confirmation extends HmsAwareAbstractBlock {

  /**
   * @inheritdoc
   */
  public function build() {
    return [
      '#theme' => 'usermanager.confirmation',
    ];
  }

}
