<?php

namespace Drupal\harbourmaster\Plugin\Block;

/**
 * Provides a 'SignIn' block.
 *
 * @Block(
 *   id = "harbourmaster_signin_block",
 *   admin_label = @Translation("HMS SignIn block"),
 * )
 */
class SignIn extends HmsAwareAbstractBlock {

  /**
   * @inheritdoc
   */
  public function build() {
    return [
      '#theme' => 'usermanager.signin',
    ];
  }

}
