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

namespace Drupal\hms\User;


use Drupal\Component\Utility\Random;
use Drupal\user\UserInterface;

class DefaultUserAdapter extends AbstractHmsUserAdapter {


  /**
   * @param array $hmsSessionData
   *    The HMS data struct for the current session
   * @return \Drupal\user\UserInterface
   *    The updated and saved User entity
   */
  public function createUser(array $hmsSessionData) {
    $r = new Random();

    /**
     * @var $user UserInterface
     */
    $user = $this->entityTypeManager->getStorage('user')->create();
    $user = static::setUserData($hmsSessionData, $user);

    // random password so a user can never log in via Drupal
    $user->setPassword($r->string(32));
    $user->enforceIsNew();
    $user->activate();
    $user->save();

    return $user;
  }

  /**
   * @param array $hmsSessionData
   *    The HMS data struct for the current session
   * @param \Drupal\user\UserInterface $user
   *    The user associated with the current session userkey
   * @return \Drupal\user\UserInterface
   *    The updated and saved User entity
   */
  public function updateUser(array $hmsSessionData, UserInterface $user) {
    if ($user->getChangedTime() >= intval($hmsSessionData['user']['modifiedAt'] / 1000)) {
      return $user;
    }

    $user = static::setUserData($hmsSessionData, $user);
    $user->save();
    return $user;
  }

  /**
   * @param array $hmsSessionData
   * @param \Drupal\user\UserInterface $user
   * @return \Drupal\user\UserInterface
   */
  protected static function setUserData(array $hmsSessionData, UserInterface $user) {
    $user->setEmail($hmsSessionData['user']['email']);
    // TODO handle username collision and other errors
    $user->setUsername('hms.' . $hmsSessionData['user']['login']);
    // TODO find out whether this actually works
    $user->setChangedTime(intval($hmsSessionData['user']['modifiedAt'] / 1000));
    return $user;
  }

}