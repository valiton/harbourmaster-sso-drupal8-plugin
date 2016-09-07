<?php

namespace Drupal\harbourmaster\Routing;

use Symfony\Component\Routing\Route;
use Drupal\Core\Config\Config;

/**
 * Class RouteCreator.
 *
 * @package Drupal\harbourmaster\Routing
 */
class RouteCreator {

  private $crossDomainLoginPath;
  private $crossDomainLogoutPath;

  /**
   * RouteCreator constructor.
   *
   * @param \Drupal\Core\Config\Config $harbourmaster_settings
   */
  function __construct(Config $harbourmaster_settings) {
    $this->crossDomainLoginPath = $harbourmaster_settings->get('cross_domain_login_path');
    $this->crossDomainLogoutPath = $harbourmaster_settings->get('cross_domain_logout_path');
  }

  /**
   * {@inheritdoc}
   */
  public function getRoutes() {
    $routes = [];

    $routes['harbourmaster.cross_domain_login'] = new Route(
      $this->crossDomainLoginPath,
      ['_controller' => '\Drupal\harbourmaster\Controller\CrossDomainAuthController::login'],
      ['_access' => 'TRUE'],
      ['no_cache' => TRUE]
    );

    $routes['harbourmaster.cross_domain_logout'] = new Route(
      $this->crossDomainLogoutPath,
      ['_controller' => '\Drupal\harbourmaster\Controller\CrossDomainAuthController::logout'],
      ['_access' => 'TRUE'],
      ['no_cache' => TRUE]
    );

    return $routes;
  }

}
