<?php
/**
 * @file
 * Alter route of /user and redirect users to openid.
 */

namespace Drupal\gp_blogs_login\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    // Deny access for some user pages.
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
