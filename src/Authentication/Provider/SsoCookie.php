<?php

/**
 * Copyright © 2016 Valiton GmbH
 *
 * This file is part of Harbourmaster Drupal Plugin.
 *
 * Harbourmaster Drupal Plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * Harbourmaster Drupal Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Harbourmaster Drupal Plugin.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Drupal\hms\Authentication\Provider;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\Config;
use Drupal\Core\Database\Connection;
use Drupal\Core\Logger\LoggerChannel;
use Drupal\Core\Session\SessionConfigurationInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Drupal\hms\Helper\CookieHelper;
use Drupal\hms\User\Manager as HmsUserManager;
use Drupal\user\Authentication\Provider\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Drupal\hms\Client\Harbourmaster as HarbourmasterClient;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


/**
 * Implements an authorization provider for Harbourmaster (HMS) SSO authorization.
 *
 * TODO composition might be better than inheritance here
 */
class SsoCookie extends Cookie {

  /**
   * Time that an authorization will be cached after looking it up in HMS.
   * This is required so that HMS does not need to be queried on every request.
   *
   * @var int
   */
  protected $cacheTtl = 60;

  /**
   * Set to true in __construct if $cacheTtl > 0
   *
   * @var bool
   */
  protected $cacheActive = false;

  /**
   * Name of the HMS SSO cookie in the request. Defaults to "token".
   *
   * @var string
   */
  protected $tokenCookieName = 'token';

  /**
   * @var bool
   */
  protected $allowOverrideByDrupalLogin = false;

  /**
   * Our own cache bin for caching HMS lookups during $cacheTtl.
   *
   * @var CacheBackendInterface
   */
  protected $cache;

  /**
   * HMS HTTP Client wrapper
   *
   * @var HarbourmasterClient
   */
  protected $hmsClient;

  /**
   * @var \Drupal\hms\User\Manager
   */
  protected $hmsUserHelper;

  /**
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * @var \Drupal\Core\Session\SessionConfigurationInterface
   */
  protected $sessionConfiguration;

  /**
   * A kernel.response subscriber that can be triggered to clear our cookie
   * (and has some helper methods for convenience)
   *
   * @var \Drupal\hms\Helper\CookieHelper
   */
  protected $cookieHelper;

  /**
   * The current session
   *
   * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
   */
  protected $session;

  /**
   * @var \Drupal\Core\Session\SessionManagerInterface
   */
  protected $sessionManager;

  /**
   * Constructs a new token authentication provider.
   *
   * TODO I'm not quite clear yet about how Session, SessionManager and
   * TODO SessionConfiguration interact with each other. Might be possible to
   * TODO replace some of them with each other.
   *
   * TODO Too many dependencies?
   *
   * @param \Drupal\hms\Client\Harbourmaster $hmsClient
   * @param \Drupal\Core\Config\Config $config
   * @param \Drupal\Core\Logger\LoggerChannel $logger
   * @param \Drupal\hms\User\Manager $hmsUserManager
   * @param \Drupal\hms\Helper\CookieHelper $cookieHelper
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
   * @param \Drupal\Core\Session\SessionConfigurationInterface $sessionConfiguration
   * @param \Drupal\Core\Session\SessionManagerInterface $sessionManager
   * @param \Drupal\Core\Database\Connection $connection
   */
  public function __construct(
    HarbourmasterClient $hmsClient,
    Config $config,
    LoggerChannel $logger,
    HmsUserManager $hmsUserManager,
    CookieHelper $cookieHelper,
    CacheBackendInterface $cache,
    SessionInterface $session,
    SessionConfigurationInterface $sessionConfiguration,
    SessionManagerInterface $sessionManager,
    Connection $connection
  ) {
    $this->hmsClient = $hmsClient;
    $this->cacheTtl = $config->get('hms_lookup_ttl');
    $this->cacheActive = $this->cacheTtl > 0;
    $this->tokenCookieName = $config->get('sso_cookie_name');
    $this->hmsUserHelper = $hmsUserManager;
    $this->logger = $logger;
    $this->cookieHelper = $cookieHelper;
    $this->cache = $cache;
    $this->session = $session;
    $this->sessionManager = $sessionManager;
    parent::__construct($sessionConfiguration, $connection);
  }

  /**
   * {@inheritdoc}
   *
   * As we have to use the same session that Drupal would use, we have to
   * "firewall" the standard Cookie auth with our provider in both our methods.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return bool
   *   TRUE if authentication credentials suitable for this provider are on the
   *   request, FALSE otherwise.
   */
  public function applies(Request $request) {
    return $this->cookieHelper->hasValidSsoCookie($request) || parent::applies($request);
  }

  /**
   * {@inheritdoc}
   *
   * As we have to use the same session that Drupal would use, we have to
   * "firewall" the standard Cookie auth with our provider in both our methods.
   *
   * TODO what happens with user data when the hms module is uninstalled?
   *
   * TODO error handling
   */
  public function authenticate(Request $request) {

    $token = $this->cookieHelper->getValidSsoCookie($request);

    // a session is already running
    if (parent::applies($request)) {
      if (!$request->getSession()->has('sso_token')) {
        // a running session without out token can be handled by Drupal's Cookie auth
        return parent::authenticate($request);
      }
      $currentSessionUid = $request->getSession()->get('uid');
      $currentSessionSsoToken = $request->getSession()->get('sso_token');
      if ($token != $currentSessionSsoToken) {
        // we COULD migrate the session to another token, but for now,
        // this is more secure
        // TODO is sso_token migration a use case to be handled?
        return $this->logout();
      }
      $hmsSessionData = $this->lookupHmsUser($token);
      if (!$hmsSessionData) {
        // if the user is logged out via sso, logout here too
        return $this->logout();
      }
      if (NULL === ($user = $this->hmsUserHelper->loadUserByHmsUserKey($hmsSessionData['userKey'])) || $user->id() != $currentSessionUid) {
        // if there is a token on a running session, but no associated user
        // exists, something's wrong
        // TODO is sso_token migration a use case to be handled?
        return $this->logout();
      }

      $this->hmsUserHelper->updateAssociatedUser($hmsSessionData, $user);

      // special role similar to "authenticated"
      $user->addRole('hms_user');
      return $user;
    }

    // no session running, need to "login" user
    if ($hmsSessionData = $this->lookupHmsUser($token)) {
      // look for a user that is associated with the HMS user key, create if needed
      if (NULL === ($user = $this->hmsUserHelper->loadUserByHmsUserKey($hmsSessionData['userKey']))) {
        $user = $this->hmsUserHelper->createAndAssociateUser($hmsSessionData);
      } else {
        $this->hmsUserHelper->updateAssociatedUser($hmsSessionData, $user);
      }
      $this->session->migrate();
      $this->session->set('uid', $user->id());
      $this->session->set('sso_token', $token);
      $user->addRole('hms_user');
      return $user;
    }

    return NULL;
  }

  protected function logout() {
    $this->sessionManager->destroy();
    return NULL;
  }

  protected function lookupHmsUser($token) {

    // no need to get too fancy with the cache id, this is our own cache bin
    $cid = 'hmsdata:' . $token;

    if ($this->cacheActive && ($cached = $this->cache->get($cid))) {
      return $cached->data;
    }

    if ($hmsSessionData = $this->hmsClient->setToken($token)->getSession()) {
      if ($this->cacheActive) {
        $this->cache->set($cid, $hmsSessionData, time() + $this->cacheTtl);
      }
      return $hmsSessionData;
    }

    $this->cookieHelper->triggerClearSsoCookie();
    return NULL;

  }

}