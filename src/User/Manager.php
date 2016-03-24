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


use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\UserDataInterface;
use Drupal\hms\User\AdapterInterface as HmsUserAdapter;
use Drupal\user\UserInterface;
use Psr\Log\LoggerAwareTrait;

class Manager {

  use LoggerAwareTrait;

  /**
   * @var \Drupal\user\UserDataInterface
   */
  protected $userDataService;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\hms\User\AdapterInterface
   */
  protected $userAdapter;

  public function __construct(UserDataInterface $userDataService, EntityTypeManagerInterface $entityTypeManager, HmsUserAdapter $userAdapter) {
    $this->userDataService = $userDataService;
    $this->entityTypeManager = $entityTypeManager;
    $this->userAdapter = $userAdapter;
  }

  /**
   * Creates a Drupal user from HMS data struct.
   *
   * @param array $hmsSessionData
   *
   * @return \Drupal\user\UserInterface
   */
  public function createAndAssociateUser(array $hmsSessionData) {
    $user = $this->userAdapter->createUser($hmsSessionData);
    $this->userDataService->set('hms', $user->id(), 'userKey', $hmsSessionData['userKey']);
    return $user;
  }

  /**
   * @param array $hmsSessionData
   * @param \Drupal\user\UserInterface $user
   * @return \Drupal\user\UserInterface
   */
  public function updateAssociatedUser(array $hmsSessionData, UserInterface $user) {
    return $this->userAdapter->updateUser($hmsSessionData, $user);
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
   * @param string $userKey HMS user key
   *
   * @return UserInterface|null
   */
  /**
   * @param $userKey
   * @return \Drupal\user\UserInterface
   */
  public function loadUserByHmsUserKey($userKey) {
    $uid = $this->findUidForHmsUserKey($userKey);
    return ($uid === NULL ? NULL : $this->entityTypeManager->getStorage('user')->load($uid));
  }

  public static function isHmsAccount(AccountInterface $account) {
    return in_array('hms_user', $account->getRoles(), TRUE);
  }

}