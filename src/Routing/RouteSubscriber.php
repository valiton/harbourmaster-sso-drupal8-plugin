<?php

namespace Drupal\harbourmaster\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 *
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {

    // These routes pass the request to an extension of the original form/controller
    // which decides whether to handle the action itself (when handling a HMS user)
    // or pass it to its parent function otherwise.
    // Form for requesting pw reset.
    if ($route = $collection->get('user.pass')) {
      $route->setDefaults(['_form' => '\Drupal\harbourmaster\Form\UserPasswordForm']);
    }
    // User profile page.
    if ($route = $collection->get('user.page')) {
      $route->setDefaults(['_controller' => '\Drupal\harbourmaster\Controller\UserController::userPage']);
    }
    // Logout.
    if ($route = $collection->get('user.logout')) {
      $route->setDefaults(['_controller' => '\Drupal\harbourmaster\Controller\UserController::logout']);
      $route->addOptions(['no_cache' => TRUE]);
    }

    // These checks deny some standard functionality to HMS users
    // TODO could this be done in a better way? (redirects or similar) - where could the logic be attached to?
    if ($route = $collection->get('entity.user.canonical')) {
      $route->addRequirements(['_harbourmaster_user_is_logged_in' => 'false']);
    }
    if ($route = $collection->get('entity.user.edit_form')) {
      $route->addRequirements(['_harbourmaster_user_is_logged_in' => 'false']);
    }
    if ($route = $collection->get('entity.user.cancel_form')) {
      $route->addRequirements(['_harbourmaster_user_is_logged_in' => 'false']);
    }

  }

}
