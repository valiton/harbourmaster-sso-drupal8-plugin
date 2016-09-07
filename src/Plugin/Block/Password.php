<?php

namespace Drupal\harbourmaster\Plugin\Block;

/**
 * Provides a 'Password' block.
 *
 * @Block(
 *   id = "harbourmaster_password_block",
 *   admin_label = @Translation("HMS Password block"),
 * )
 */
class Password extends HmsAwareAbstractBlock {

  /**
   * @inheritdoc
   */
  public function build() {
    return [
      '#theme' => 'usermanager.password',
    ];
  }

}
