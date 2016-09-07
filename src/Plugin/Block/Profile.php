<?php

namespace Drupal\harbourmaster\Plugin\Block;

/**
 * Provides a 'Profile' block.
 *
 * @Block(
 *   id = "harbourmaster_profile_block",
 *   admin_label = @Translation("HMS Profile block"),
 * )
 */
class Profile extends HmsAwareAbstractBlock {

  /**
   * @inheritdoc
   */
  public function build() {
    return [
      '#theme' => 'usermanager.profile',
    ];
  }

}
