<?php

namespace Drupal\harbourmaster\Plugin\Block;

/**
 * Provides a 'ResetPassword' block.
 *
 * @Block(
 *   id = "harbourmaster_reset_password_block",
 *   admin_label = @Translation("HMS ResetPassword block"),
 * )
 */
class ResetPassword extends HmsAwareAbstractBlock {

  /**
   * @inheritdoc
   */
  public function build() {
    return [
      '#theme' => 'usermanager.reset_password',
    ];
  }

}
