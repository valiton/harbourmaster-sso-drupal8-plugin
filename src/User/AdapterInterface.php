<?php

namespace Drupal\harbourmaster\User;

use Drupal\user\UserInterface;

/**
 *
 */
interface AdapterInterface {

  /**
   * @param array $harbourmasterSessionData
   * @return UserInterface
   */
  public function createUser(array $harbourmasterSessionData);

  /**
   * @param array $harbourmasterSessionData
   * @param \Drupal\user\UserInterface $user
   * @return bool whether the Drupal user was changed
   */
  public function updateUser(array $harbourmasterSessionData, UserInterface &$user);

}
