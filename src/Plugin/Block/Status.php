<?php

namespace Drupal\harbourmaster\Plugin\Block;

/**
 * Provides a 'Status' block.
 *
 * @Block(
 *   id = "harbourmaster_status_block",
 *   admin_label = @Translation("HMS Status block"),
 * )
 */
class Status extends HmsAwareAbstractBlock {

  /**
   * @inheritdoc
   */
  public function build() {

    $render = [
      '#theme' => 'status',
      '#cache' => [
        'contexts' => ['user'],
      ],
      // '#cache' => [
      //        'max-age' => 0,
      //      ],.
      '#currentUser' => $this->currentUser,
      '#currentUserRoles' => $this->currentUser->getRoles(),
    ];

    if ($this->currentUser->isAuthenticated()) {
      $userKey = $this->harbourmasterUserManager->findHmsUserKeyForUid($this->currentUser->id());
      if ($userKey) {
        $render += [
          '#harbourmasterUserKey' => $userKey,
        ];
      }
    }

    return $render;

  }

}
