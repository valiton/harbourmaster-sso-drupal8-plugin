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

use Drupal\Core\Authentication\AuthenticationProviderInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\Config;
use Drupal\Core\Logger\LoggerChannel;
use Drupal\Core\Session\SessionConfigurationInterface;
use Drupal\hms\EventSubscriber\ClearInvalidTokenCookie;
use Drupal\hms\User\Manager as HmsUserManager;
use Symfony\Component\HttpFoundation\Request;
use Drupal\hms\Client\Harbourmaster as HarbourmasterClient;


/**
 * Implements an authorization provider for Harbourmaster (HMS) SSO authorization.
 */
class SsoCookie implements AuthenticationProviderInterface {

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
  protected $cacheActive;

  /**
   * Name of the HMS SSO cookie in the request. Defaults to "token".
   *
   * @var string
   */
  protected $tokenCookieName = 'token';

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
   *
   * @var \Drupal\hms\EventSubscriber\ClearInvalidTokenCookie
   */
  protected $responseSubscriber;

  /**
   * Constructs a new token authentication provider.
   *
   * TODO do we need to inject the whole EntityTypeManager or can we inject the UserStorage only?
   *
   * @param \Drupal\hms\Client\Harbourmaster $hmsClient
   * @param \Drupal\Core\Config\Config $config
   * @param \Drupal\Core\Logger\LoggerChannel $logger
   * @param \Drupal\hms\User\Manager $hmsUserManager
   * @param \Drupal\hms\EventSubscriber\ClearInvalidTokenCookie $responseSubscriber
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   * @param \Drupal\Core\Session\SessionConfigurationInterface $sessionConfiguration
   */
  public function __construct(HarbourmasterClient $hmsClient, Config $config, LoggerChannel $logger, HmsUserManager $hmsUserManager, ClearInvalidTokenCookie $responseSubscriber, CacheBackendInterface $cache, SessionConfigurationInterface $sessionConfiguration) {
    $this->hmsClient = $hmsClient;
    $this->cacheTtl = $config->get('hms_lookup_ttl');
    $this->cacheActive = $this->cacheTtl > 0;
    $this->tokenCookieName = $config->get('sso_cookie_name');
    $this->hmsUserHelper = $hmsUserManager;
    $this->logger = $logger;
    $this->responseSubscriber = $responseSubscriber;
    $this->cache = $cache;
    $this->sessionConfiguration = $sessionConfiguration;
  }

  /**
   * Checks whether suitable authentication credentials are on the request.
   *
   * Note that this handler only applies if
   * - there is no active Drupal session
   * - the current request is not against the login url
   * - and naturally, our cookie exists on the request
   *
   * The first two requirements make it possible to have a Drupal login "override"
   * our HMS login. Returning NULL in {@see authenticate()} wouldn't help as only
   * one provider can apply on any given request (they don't "chain" so the Cookie-Provider
   * would never get a chance).
   *
   * TODO There might be more URLs that need to be excluded
   * TODO Looking up in router should work better than just matching the URI
   * TODO What to do with anonymous sessions?
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return bool
   *   TRUE if authentication credentials suitable for this provider are on the
   *   request, FALSE otherwise.
   */
  public function applies(Request $request) {
    $activeSession = $request->hasSession() && $this->sessionConfiguration->hasSession($request);
    $isLogin = preg_match('#^/user/login#', $request->getRequestUri());
    $cookieExists = $request->cookies->has($this->tokenCookieName);
    return !($activeSession || $isLogin) && $cookieExists;
  }

  /**
   * {@inheritdoc}
   *
   * TODO what happens with user data when the hms module is uninstalled?
   * TODO handle errors
   */
  public function authenticate(Request $request) {

    $token = $request->cookies->get($this->tokenCookieName);

    // no need to get too fancy with the cache id, this is our own cache bin
    $cid = 'hmsdata:' . $token;
    $fromCache = false;

    if ($this->cacheActive && ($cached = $this->cache->get($cid))) {
      $hmsSessionData = $cached->data;
      $fromCache = true;
      $this->logger->debug('Login from cached');
      $this->logger->debug('{data}', ['data' => var_export($hmsSessionData, TRUE)]);
    }
    else {
      if ($hmsSessionData = $this->hmsClient->setToken($token)->getSession()) {
        $this->logger->debug('Login from HMS');
        $this->logger->debug('{data}', ['data' => var_export($hmsSessionData, TRUE)]);
        if ($this->cacheActive) {
          $this->cache->set($cid, $hmsSessionData, time() + $this->cacheTtl);
        }
      }
      else {
        $this->responseSubscriber->triggerClearCookie();
        $this->logger->debug('No such session, triggering clear cookie');
      }
    }

    if ($hmsSessionData) {
      // look for a user that is associated with the HMS user key
      if (NULL === ($user = $this->hmsUserHelper->loadUserByHmsUserKey($hmsSessionData['userKey']))) {
        $user = $this->hmsUserHelper->createAndAssociateUser($hmsSessionData);
      } else if (!$fromCache) {
        $user = $this->hmsUserHelper->updateAssociatedUser($hmsSessionData, $user);
      }
      $user->addRole('hms_user');
      return $user;
    }

    return NULL;

  }

}