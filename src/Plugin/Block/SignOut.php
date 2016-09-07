<?php

namespace Drupal\harbourmaster\Plugin\Block;

/**
 * Provides a 'SignOut' block.
 *
 * @Block(
 *   id = "harbourmaster_signout_block",
 *   admin_label = @Translation("HMS SignOut block"),
 * )
 */
class SignOut extends HmsAwareAbstractBlock {

  /**
   * @inheritdoc
   */
  public function build() {
    return [
      '#theme' => 'usermanager.signout',
    ];
  }

}
