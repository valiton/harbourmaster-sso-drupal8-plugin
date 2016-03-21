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

namespace Drupal\hms\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    // form for requesting pw reset
    if ($route = $collection->get('user.pass')) {
      $route->setDefaults(['_form' => '\Drupal\hms\Form\UserPasswordForm']);
    }
    if ($route = $collection->get('user.logout')) {
      $route->setDefaults(['_controller' => '\Drupal\hms\Controller\UserRouteProxyController::logout']);
    }
    if ($route = $collection->get('user.reset')) {
      $route->setDefaults(['_controller' => '\Drupal\hms\Controller\UserRouteProxyController::password']);
    }
    if ($route = $collection->get('user.page')) {
      $route->
//      $route->setRequirement('_access', 'FALSE');
      $route->setDefaults(['_controller' => '\Drupal\gp_blogs_login\Controller\RedirectUserPagesController::redirectUser']);
    }
    if ($route = $collection->get('user.pass')) {
//      $route->setRequirement('_access', 'FALSE');
      $route->setDefaults(['_controller' => '\Drupal\gp_blogs_login\Controller\RedirectUserPagesController::redirectUser']);
    }
  }

}
