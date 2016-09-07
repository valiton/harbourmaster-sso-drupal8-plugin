<?php

namespace Drupal\harbourmaster\User;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\UserDataInterface;
use Drupal\harbourmaster\User\AdapterInterface as HmsUserAdapter;
use Drupal\user\UserInterface;
use Psr\Log\LoggerAwareTrait;

/**
 *
 */
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

  /**
   *
   */
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
   * @param string $userKey
   *   HMS userKey.
   *
   * @return int|null
   */
  public function findUidForHmsUserKey($userKey) {
    // Return an array of the form $uid => $userKey.
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
