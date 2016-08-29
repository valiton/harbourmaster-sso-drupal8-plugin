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

namespace Drupal\harbourmaster\User;


use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\UserDataInterface;
use Drupal\harbourmaster\User\AdapterInterface as HmsUserAdapter;
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
   * @var \Drupal\harbourmaster\User\AdapterInterface
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
   * @param array $harbourmasterSessionData
   *
   * @return \Drupal\user\UserInterface
   */
  public function createAndAssociateUser(array $harbourmasterSessionData) {
    $user = $this->userAdapter->createUser($harbourmasterSessionData);
    $this->userDataService->set('harbourmaster', $user->id(), 'userKey', $harbourmasterSessionData['userKey']);
    return $user;
  }

  /**
   * @param array $harbourmasterSessionData
   * @param \Drupal\user\UserInterface $user
   * @return \Drupal\user\UserInterface
   */
  public function updateAssociatedUser(array $harbourmasterSessionData, UserInterface $user) {
    return $this->userAdapter->updateUser($harbourmasterSessionData, $user);
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
    $userKeysByUid = $this->userDataService->get('harbourmaster', NULL, 'userKey');
    $uidsByUserKey = array_flip($userKeysByUid);
    return isset($uidsByUserKey[$userKey]) ? $uidsByUserKey[$userKey] : NULL;
  }

  /**
   * @param int $uid
   *
   * @return string|null
   */
  public function findHmsUserKeyForUid($uid) {
    $userKeysByUid = $this->userDataService->get('harbourmaster', NULL, 'userKey');
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

}