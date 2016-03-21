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

namespace Drupal\hms\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Routing\Route;
use Drupal\hms\User\Manager as HmsUserManager;

/**
 * Determines access to routes based on login status of current user.
 */
class LoginStatusCheck implements AccessInterface {

  /**
   * @var \Drupal\hms\User\Manager
   */
  protected $hmsUserManager;

  public function __construct(HmsUserManager $hmsUserManager) {
    $this->hmsUserManager = $hmsUserManager;
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
    $required_status = filter_var($route->getRequirement('_hms_user_is_logged_in'), FILTER_VALIDATE_BOOLEAN);
    $actual_status = $account->isAuthenticated() && (NULL !== $this->hmsUserManager->findHmsUserKeyForUid($account->id()));
    // TODO maybe add own cache contexts?
    return AccessResult::allowedIf($required_status === $actual_status)->addCacheContexts(['user']);
  }

}
