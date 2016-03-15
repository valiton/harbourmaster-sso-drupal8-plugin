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
use Drupal\file\FileInterface;
use Drupal\user\UserInterface;
use Drupal\user\UserStorageInterface;

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
     * @var UserStorageInterface
     */
    $userStorage = $this->entityTypeManager->getStorage('user');
    $users = $userStorage->loadByProperties(['name' => $hmsSessionData['user']['login']]);
    if (count($users) > 0) {
      $hmsSessionData['user']['login'] = $this->fixUserNameCollision($hmsSessionData['user']['login']);
    }

    /**
     * @var $user UserInterface
     */
    $user = $userStorage->create();

    $user = $this->setUserData($hmsSessionData, $user);

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
   *    The user associated with the current session userKey
   * @return bool
   *    Whether the user was updated
   */
  public function updateUser(array $hmsSessionData, UserInterface &$user) {
    if ($user->getChangedTime() >= intval($hmsSessionData['user']['modifiedAt'] / 1000)) {
      return FALSE;
    }

    $user = $this->setUserData($hmsSessionData, $user);
    $user->save();
    return TRUE;
  }

  protected function fixUserNameCollision($name) {
    return 'hms.' . $name;
  }

  /**
   * @param array $hmsSessionData
   * @param \Drupal\user\UserInterface $user
   * @return \Drupal\user\UserInterface
   */
  protected function setUserData(array $hmsSessionData, UserInterface $user) {
    $user->setEmail($hmsSessionData['user']['email']);
    $user->setUsername($hmsSessionData['user']['login']);

    if (user_picture_enabled()) {
      // TODO this should not be done on every login. maybe.
      $avatar = isset($hmsSessionData['user']['avatarImage']) ? $hmsSessionData['user']['avatarImage'] : '';
      if (!empty($avatar)) {
        // HMS always returns the 75px derivate, we want a bigger one
        $avatar = preg_replace('#/75([x_])75\.jpg$#', '/150\${1}150.jpg', $avatar);
        $this->logger->debug('Settings user data: custom picture, trying to retrieve @uri', [ '@uri' => $avatar ]);
      } else {
        // build default URL
        $avatar = $this->hmsSettings->get('user_manager_url') . '/usermanager/avatar/150_150/' . urlencode($hmsSessionData['user']['login']) . '.jpg';
        $this->logger->debug('Settings user data: default picture, trying to retrieve @uri', [ '@uri' => $avatar ]);
      }

      $dir = 'public://hms_pictures';

      if (file_prepare_directory($dir, FILE_CREATE_DIRECTORY | FILE_MODIFY_PERMISSIONS)) {
        $path = $dir . '/' . $hmsSessionData['userKey'] . '.jpg';
        /**
         * @var FileInterface $file
         */
        if ($file = system_retrieve_file($avatar, $path, TRUE, FILE_EXISTS_REPLACE)) {
          $this->logger->debug('Settings user data: retrieved @uri', [ '@uri' => $avatar ]);
          $user->set('user_picture', $file->id());
          image_path_flush($path);
        } else {
          $this->logger->debug('Settings user data: error retrieving @uri', [ '@uri' => $avatar ]);
        }
      }
    }

    // TODO find out whether this actually works
    $user->setChangedTime(intval($hmsSessionData['user']['modifiedAt'] / 1000));
    return $user;
  }

}