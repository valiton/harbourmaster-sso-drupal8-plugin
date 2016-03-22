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

    // These routes pass the request to an extension of the original form/controller
    // which decides whether to handle the action itself (when handling a HMS user)
    // or pass it to its parent function otherwise

    // form for requesting pw reset
    if ($route = $collection->get('user.pass')) {
      $route->setDefaults(['_form' => '\Drupal\hms\Form\UserPasswordForm']);
    }
    // user profile page
    if ($route = $collection->get('user.page')) {
      $route->setDefaults(['_controller' => '\Drupal\hms\Controller\UserController::userPage']);
    }
    // logout
    if ($route = $collection->get('user.logout')) {
      $route->setDefaults(['_controller' => '\Drupal\hms\Controller\UserController::logout']);
      $route->addOptions(['no_cache' => TRUE]);
    }


    // These checks deny some standard functionality to HMS users
    // TODO could this be done in a better way? (redirects or similar) - where could the logic be attached to?
    if ($route = $collection->get('entity.user.canonical')) {
      $route->addRequirements([ '_hms_user_is_logged_in' => 'false' ]);
    }
    if ($route = $collection->get('entity.user.edit_form')) {
      $route->addRequirements([ '_hms_user_is_logged_in' => 'false' ]);
    }
    if ($route = $collection->get('entity.user.cancel_form')) {
      $route->addRequirements([ '_hms_user_is_logged_in' => 'false' ]);
    }

  }

}
