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

namespace Drupal\harbourmaster\Authentication\Provider;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\Config;
use Drupal\Core\Database\Connection;
use Drupal\Core\Logger\LoggerChannel;
use Drupal\Core\Session\SessionConfigurationInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Drupal\harbourmaster\Helper\CookieHelper;
use Drupal\harbourmaster\User\Manager as HmsUserManager;
use Drupal\user\Authentication\Provider\Cookie;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Drupal\harbourmaster\Client\Harbourmaster as HarbourmasterClient;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


/**
 * Implements an authorization provider for Harbourmaster (HMS) SSO authorization.
 *
 * TODO composition might be better than inheritance here
 */
class SsoCookie extends Cookie {

  use LoggerAwareTrait;

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
  protected $harbourmasterClient;

  /**
   * @var \Drupal\harbourmaster\User\Manager
   */
  protected $harbourmasterUserHelper;

  /**
   * @var \Drupal\Core\Session\SessionConfigurationInterface
   */
  protected $sessionConfiguration;

  /**
   * A kernel.response subscriber that can be triggered to clear our cookie
   * (and has some helper methods for convenience)
   *
   * @var \Drupal\harbourmaster\Helper\CookieHelper
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
   * @param \Drupal\harbourmaster\Client\Harbourmaster $harbourmasterClient
   * @param \Drupal\Core\Config\Config $config
   * @param \Drupal\Core\Logger\LoggerChannel $logger
   * @param \Drupal\harbourmaster\User\Manager $harbourmasterUserManager
   * @param \Drupal\harbourmaster\Helper\CookieHelper $cookieHelper
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
   * @param \Drupal\Core\Session\SessionConfigurationInterface $sessionConfiguration
   * @param \Drupal\Core\Session\SessionManagerInterface $sessionManager
   * @param \Drupal\Core\Database\Connection $connection
   */
  public function __construct(
    HarbourmasterClient $harbourmasterClient,
    Config $config,
    HmsUserManager $harbourmasterUserManager,
    CookieHelper $cookieHelper,
    CacheBackendInterface $cache,
    SessionInterface $session,
    SessionConfigurationInterface $sessionConfiguration,
    SessionManagerInterface $sessionManager,
    Connection $connection
  ) {
    $this->harbourmasterClient = $harbourmasterClient;
    $this->cacheTtl = $config->get('harbourmaster_lookup_ttl');
    $this->cacheActive = $this->cacheTtl > 0;
    $this->tokenCookieName = $config->get('sso_cookie_name');
    $this->harbourmasterUserHelper = $harbourmasterUserManager;
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
   * TODO what happens with user data when the harbourmaster module is uninstalled?
   *
   * TODO error handling
   */
  public function authenticate(Request $request) {

    $token = $this->cookieHelper->getValidSsoCookie($request);

    $context = [
      '@uri' => $request->getRequestUri(),
      '@cookie_token' => $token,
    ];

    // a session is already running
    if (parent::applies($request)) {
      $this->logger->debug('Authenticating request on @uri for existing session with token @cookie_token', $context);
      if (!$request->getSession()->has('sso_token')) {
        // a running session without token can be handled by Drupal's Cookie auth
        $this->logger->debug('Authenticating request on @uri for existing session with token @cookie_token: session has no associated SSO token, handing over to Drupal', $context);
        return parent::authenticate($request);
      }
      $currentSessionUid = $request->getSession()->get('uid');
      $currentSessionSsoToken = $request->getSession()->get('sso_token');
      $context += [
        '@uid' => $currentSessionUid,
        '@session_token' => $currentSessionSsoToken,
      ];
      if ($token != $currentSessionSsoToken) {
        // we COULD migrate the session to another token, but for now,
        // this is more secure
        $this->logger->debug('Failed authenticating request on @uri for existing session with token @cookie_token: session token @session_token mismatched, logging out user @uid', $context);
        // TODO is sso_token migration a use case to be handled?
        return $this->logout();
      }
      $harbourmasterSessionData = $this->lookupHmsUser($token);
      if (!$harbourmasterSessionData) {
        // if the user is logged out via sso, logout here too
        $this->logger->debug('Failed authenticating request on @uri for existing session with token @cookie_token: session expired, logging out user @uid', $context);
        return $this->logout();
      }
      if (NULL === ($user = $this->harbourmasterUserHelper->loadUserByHmsUserKey($harbourmasterSessionData['userKey'])) || $user->id() != $currentSessionUid) {
        // if there is a token on a running session, but no associated user
        // exists, something's wrong
        // TODO is sso_token migration a use case to be handled?
        $this->logger->warning('Failed authenticating request on @uri for existing session with token @cookie_token: user has no associated HMS user key, logging out user @uid', $context);
        return $this->logout();
      }

      $this->logger->debug('Authenticating request on @uri for existing session with token @cookie_token: success for user @uid', $context);
      $changed = $this->harbourmasterUserHelper->updateAssociatedUser($harbourmasterSessionData, $user);
      if ($changed) {
        $this->logger->debug('Authenticating request on @uri for existing session with token @cookie_token: updated user @uid', $context);
      }

      // special role similar to "authenticated"
      $user->addRole('harbourmaster_user');
      return $user;
    }

    // no session running, need to "login" user
    if ($harbourmasterSessionData = $this->lookupHmsUser($token)) {
      $context += [ '@user_key' => $harbourmasterSessionData['userKey']];
      $this->logger->debug('Authenticating request on @uri for new session with token @cookie_token: HMS session found with userKey @user_key', $context);
      // look for a user that is associated with the HMS user key, create if needed
      if (NULL === ($user = $this->harbourmasterUserHelper->loadUserByHmsUserKey($harbourmasterSessionData['userKey']))) {
        $user = $this->harbourmasterUserHelper->createAndAssociateUser($harbourmasterSessionData);
        $context += [ '@uid' => $user->id() ];
        $this->logger->debug('Authenticating request on @uri for new session with token @cookie_token: Created new Drupal user @uid for userKey @user_key', $context);
      } else {
        $changed = $this->harbourmasterUserHelper->updateAssociatedUser($harbourmasterSessionData, $user);
        $context += [
          '@uid' => $user->id(),
          '@changes' => $changed ? 'update' : 'no update',
        ];
        $this->logger->debug('Authenticating request on @uri for new session with token @cookie_token: Found existing Drupal user for userKey @user_key, @changes required', $context);
      }
      $this->logger->info('Authenticating request on @uri for new session with token @cookie_token: session opened for @uid', $context);
      $this->session->migrate();
      $this->session->set('uid', $user->id());
      $this->session->set('sso_token', $token);
      $user->addRole('harbourmaster_user');
      return $user;
    }

    $this->logger->debug('Authenticating request on @uri with token @cookie_token: no Drupal session, valid token or HMS session found', $context);

    return NULL;
  }

  protected function logout() {
    $this->sessionManager->destroy();
    return NULL;
  }

  protected function lookupHmsUser($token) {

    // no need to get too fancy with the cache id, this is our own cache bin
    $cid = 'harbourmasterdata:' . $token;

    if ($this->cacheActive && ($cached = $this->cache->get($cid))) {
      $this->logger->debug('HMS session lookup: cache HIT for token @token', [ '@token' => $token ]);
      return $cached->data;
    }

    if ($harbourmasterSessionData = $this->harbourmasterClient->setToken($token)->getSession()) {
      $this->logger->debug('HMS session lookup: HMS lookup succeeded for token @token', [ '@token' => $token ]);
      if ($this->cacheActive) {
        $this->cache->set($cid, $harbourmasterSessionData, time() + $this->cacheTtl);
      }
      return $harbourmasterSessionData;
    }

    $this->logger->debug('HMS session lookup: failed for token @token, triggered cookie deletion', [ '@token' => $token ]);
    $this->cookieHelper->triggerClearSsoCookie();
    return NULL;

  }

}