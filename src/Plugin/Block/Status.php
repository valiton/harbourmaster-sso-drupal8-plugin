<?php

/**
 * Copyright © 2016 Valiton GmbH.
 *
 * This file is part of Harbourmaster Drupal Plugin.
 *
 * Harbourmaster Drupal Plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Harbourmaster Drupal Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Harbourmaster Drupal Plugin.  If not, see <http://www.gnu.org/licenses/>.
 */

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
//      '#cache' => [
//        'max-age' => 0,
//      ],
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