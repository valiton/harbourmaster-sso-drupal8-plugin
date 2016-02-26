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
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\Entity\User;
use Drupal\user\UserDataInterface;

class Helper {

  /**
   * @var \Drupal\user\UserDataInterface
   */
  protected $userDataService;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Helper constructor.
   * @param \Drupal\user\UserDataInterface $userDataService
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(UserDataInterface $userDataService, EntityTypeManagerInterface $entityTypeManager) {
    $this->userDataService = $userDataService;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Creates a Drupal user from HMS data struct.
   *
   * TODO maybe make this an extra adapter class
   *
   * @param array $hmsUserData
   *
   * @return \Drupal\user\Entity\User
   */
  public function createDrupalUserFromHmsStruct(array $hmsUserData) {
    $r = new Random();

    /**
     * @var $user User
     */
    $user = $this->entityTypeManager->getStorage('user')->create();
    $user->setPassword($r->string(32));
    $user->enforceIsNew();
    $user->setEmail($hmsUserData['email']);
    // TODO handle username collision and other errors
    $user->setUsername($hmsUserData['user']['login']);
    $user->activate();
    $user->save();
    $this->userDataService->set('hms', $user->id(), 'userKey', $hmsUserData['userKey']);
    return $user;
  }

  /**
   * Fetches a Drupal uid for a given HMS userKey.
   *
   * @param string $userKey HMS userKey
   *
   * @return int|null
   */
  public function findUidForHmsUserKey($userKey) {
    // return an array of the form $uid => $userKey
    $userKeysByUid = $this->userDataService->get('hms', NULL, 'userKey');
    $uidsByUserKey = array_flip($userKeysByUid);
    return isset($uidsByUserKey[$userKey]) ? $uidsByUserKey[$userKey] : NULL;
  }

  /**
   * @param int $uid
   *
   * @return string|null
   */
  public function findHmsUserKeyForUid($uid) {
    $userKeysByUid = $this->userDataService->get('hms', NULL, 'userKey');
    return isset($userKeysByUid[$uid]) ? $userKeysByUid[$uid] : NULL;
  }

  /**
   * @param $userKey HMS user key
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   */
  public function loadUserByHmsUserKey($userKey) {
    $uid = $this->findUidForHmsUserKey($userKey);
    return ($uid === NULL ? NULL : $this->entityTypeManager->getStorage('user')->load($uid));
  }

}