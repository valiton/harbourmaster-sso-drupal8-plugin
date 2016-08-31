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

namespace Drupal\harbourmaster\Routing;

use Symfony\Component\Routing\Route;
use Drupal\Core\Config\Config;

/**
 * Class RouteCreator
 * @package Drupal\harbourmaster\Routing
 */
class RouteCreator {

  private $crossDomainLoginPath;
  private $crossDomainLogoutPath;

  /**
   * RouteCreator constructor.
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
      ['_access'  => 'TRUE'],
      ['no_cache'  => TRUE]
    );

    $routes['harbourmaster.cross_domain_logout'] = new Route(
      $this->crossDomainLogoutPath,
      ['_controller' => '\Drupal\harbourmaster\Controller\CrossDomainAuthController::logout'],
      ['_access'  => 'TRUE'],
      ['no_cache'  => TRUE]
    );

    return $routes;
  }
}
