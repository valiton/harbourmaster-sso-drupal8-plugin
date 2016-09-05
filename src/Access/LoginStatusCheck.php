<?php

namespace Drupal\harbourmaster\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Routing\Route;
use Drupal\harbourmaster\User\Manager as HmsUserManager;

/**
 * Determines access to routes based on login status of current user.
 */
class LoginStatusCheck implements AccessInterface {

  /**
   * @var \Drupal\harbourmaster\User\Manager
   */
  protected $harbourmasterUserManager;

  /**
   *
   */
  public function __construct(HmsUserManager $harbourmasterUserManager) {
    $this->harbourmasterUserManager = $harbourmasterUserManager;
  }

  /**
   * Checks access.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The currently logged in account.
   * @param \Symfony\Component\Routing\Route $route
   *   The route to check against.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $account, Route $route) {
    $required_status = filter_var($route->getRequirement('_harbourmaster_user_is_logged_in'), FILTER_VALIDATE_BOOLEAN);
    $actual_status = $account->isAuthenticated() && (NULL !== $this->harbourmasterUserManager->findHmsUserKeyForUid($account->id()));
    // TODO maybe add own cache contexts?
    return AccessResult::allowedIf($required_status === $actual_status)->addCacheContexts(['user']);
  }

}
