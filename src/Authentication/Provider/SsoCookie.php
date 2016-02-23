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

use Drupal\Component\Utility\Random;
use Drupal\Core\Authentication\AuthenticationProviderInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\Config;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Logger\LoggerChannel;
use Drupal\Core\Session\SessionConfigurationInterface;
use Drupal\hms\EventSubscriber\ClearInvalidTokenCookie;
use Drupal\user\Entity\User;
use Drupal\user\UserDataInterface;
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
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * @var \Drupal\user\UserDataInterface
   */
  protected $userDataService;

  /**
   * @var \Drupal\Core\Session\SessionConfigurationInterface
   */
  protected $sessionConfiguration;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

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
   * @param \Drupal\hms\EventSubscriber\ClearInvalidTokenCookie $responseSubscriber
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   * @param \Drupal\user\UserDataInterface $userDataService
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   * @param \Drupal\Core\Session\SessionConfigurationInterface $sessionConfiguration
   */
  public function __construct(HarbourmasterClient $hmsClient, Config $config, LoggerChannel $logger, ClearInvalidTokenCookie $responseSubscriber, CacheBackendInterface $cache, UserDataInterface $userDataService, EntityTypeManager $entityTypeManager, SessionConfigurationInterface $sessionConfiguration) {
    $this->hmsClient = $hmsClient;
    $this->cacheTtl = $config->get('hms_lookup_ttl');
    $this->cacheActive = $this->cacheTtl > 0;
    $this->tokenCookieName = $config->get('sso_cookie_name');
    $this->logger = $logger;
    $this->responseSubscriber = $responseSubscriber;
    $this->cache = $cache;
    $this->userDataService = $userDataService;
    $this->entityTypeManager = $entityTypeManager;
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

    if ($this->cacheActive && ($data = $this->cache->get($cid))) {
      $this->logger->debug('Login from cached');
      $this->logger->debug('{data}', ['data' => var_export($data, true)]);
    } else if ($data = $this->hmsClient->setToken($token)->getSession()) {
      $this->logger->debug('Login from HMS');
      $this->logger->debug('{data}', ['data' => var_export($data, true)]);
      if ($this->cacheActive) {
        $this->cache->set($cid, $data, time() + $this->cacheTtl);
      }
    } else {
      $this->responseSubscriber->triggerClearCookie();
      $this->logger->debug('No such session, triggering clear cookie');

    }

    if ($data) {
      // look for a uid that is associated with the HMS user key
      $uid = $this->findUidForHmsUserKey($data['userKey']);
      if (!$uid) {
        $user = $this->createDrupalUser($data);
        // associate uid with userKey
        $this->userDataService->set('hms', $user->id(), 'userKey', $data['userKey']);
      } else {
        $user = $this->entityTypeManager->getStorage('user')->load($uid);
      }

      return $user;
    }

    return null;

  }

  /**
   * Fetches a Drupal uid for a given HMS userKey.
   *
   * @param string $userKey HMS userKey
   *
   * @return string|null
   */
  protected function findUidForHmsUserKey($userKey) {
    // return an array of the form $uid => $userKey
    $userKeysByUid = $this->userDataService->get('hms', null, 'userKey');
    $uidsByUserKey = array_flip($userKeysByUid);
    return isset($uidsByUserKey[$userKey]) ? $uidsByUserKey[$userKey] : null;
  }

  /**
   * Creates a Drupal user from HMS data struct.
   *
   * TODO maybe make this an extra adapter class
   *
   * @param array $hmsUserData
   *
   * @return \Drupal\user\Entity\User
   */
  protected function createDrupalUser(array $hmsUserData) {
    $r = new Random();

    /**
     * @var $user User
     */
    $user = $this->entityTypeManager->getStorage('user')->create();
    $user->setPassword($r->string(32));
    $user->enforceIsNew();
    $user->setEmail($hmsUserData['email']);
    // TODO handle username collision?
    $user->setUsername($hmsUserData['user']['login']);
    $user->activate();
    $user->save();
    return $user;
  }

}