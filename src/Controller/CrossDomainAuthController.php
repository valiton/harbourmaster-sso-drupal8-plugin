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

namespace Drupal\harbourmaster\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\harbourmaster\Responses\TransparentPixelResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\Core\Config\Config;

/**
 * Class CrossDomainAuthController
 * @package Drupal\harbourmaster\Controller
 *
 * @todo Move all cookie code to own class.
 */
class CrossDomainAuthController extends ControllerBase {

  protected $harbourmasterSettings;
  protected $cookieHelper;
  
  protected $sessionData;
  protected $sessionToken;
  protected $logger;

  const HARBOURMASTER_SESSION_DATA_PATH = '/msg/session/crossdomain';

  public function __construct(Config $harbourmaster_settings, $cookie_helper) {
    $this->harbourmasterSettings = $harbourmaster_settings;
    $this->logger = $this->getLogger('harbourmaster');
    $this->cookieHelper = $cookie_helper;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('harbourmaster.settings'),
      $container->get('harbourmaster.cookie_helper')
    );
  }

  public function login(Request $request) {
    $parameters = $request->query;
    if (empty($token = $parameters->get('onetimelogintoken'))) {
      $this->logger->debug('Login: No token found in URL');
      throw new NotFoundHttpException();
    }
    $this->logger->debug("Login: Token found in URL: $token");
    $this->getSessionData($token);
    if ($this->validSession()) {
      $this->setSessionToken();
      $this->logger->debug("Login: Session data token: $this->sessionToken");
      $this->startSession();
    }
    else {
      $this->logger->debug('Login: No session found');
    }
    return new TransparentPixelResponse();
  }

  protected function getSessionData($token) {
    $session_data_url = $this->harbourmasterSettings->get('harbourmaster_api_url')
      . '/' . $this->harbourmasterSettings->get('harbourmaster_api_version')
      . self::HARBOURMASTER_SESSION_DATA_PATH
      . '?onetimelogintoken=' . $token
      . '&domain=' . $this->cookieHelper->getDomain();

    $this->logger->debug("Login: Curl request: $session_data_url");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $session_data_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $session_data_string = curl_exec($ch);
    $this->logger->debug("Login: Session data: $session_data_string");
    $this->sessionData = json_decode($session_data_string);
    //todo exception handling
  }

  protected function validSession() {
    return !empty($this->sessionData->status
      && !empty($this->sessionData->data->token));
  }

  protected function setSessionToken() {
    $this->sessionToken = $this->sessionData->data->token;
  }

  protected function startSession() {
    return $this->cookieHelper->setCookie($this->sessionToken);
  }

  protected function invalidateSession() {
    return $this->cookieHelper->setCookie('deleted');
  }

  public function logout() {
    $this->invalidateSession();
    return new TransparentPixelResponse();
  }
}
