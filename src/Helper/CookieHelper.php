<?php

namespace Drupal\harbourmaster\Helper;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Drupal\Core\Config\Config;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

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
class CookieHelper implements EventSubscriberInterface {

  /**
   * @var object
   */
  protected $harbourmasterSettings;

  /**
   * @var string
   */
  protected $domain;

  /**
   * @var string
   */
  protected $ssoCookieName;

  /**
   * @var string
   */
  protected $ssoCookieDomain;

  /**
   * @var object
   */
  protected $logger;

  /**
   * @var bool
   */
  protected $clearTokenTriggered = FALSE;

  /**
   *
   */
  public function __construct(Config $harbourmasterSettings, $request_stack, $logger) {
    $this->harbourmasterSettings = $harbourmasterSettings;
    $this->domain = $request_stack->getCurrentRequest()->getHost();
    $this->logger = $logger;
    $this->ssoCookieName = $this->harbourmasterSettings->get('sso_cookie_name');
    $this->ssoCookieDomain = $this->getCookieDomain();
  }

  /**
   *
   */
  public function getDomain() {
    return $this->domain;
  }

  /**
   *
   */
  public function getCookieDomain() {
    return !empty($cookie_domain = $this->harbourmasterSettings->get('sso_cookie_domain'))
      ? $cookie_domain : '.' . $this->domain;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = ['onResponse', 512];
    return $events;
  }

  /**
   * Kernel.response subscriber that removes the SSO cookie if clearing has
   * been triggered at some point during the event.
   *
   * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
   */
  public function onResponse(FilterResponseEvent $event) {
    // TODO test this in conjunction with Drupal's own login.
    if ($this->clearTokenTriggered && $event->getRequest()->cookies->has($this->ssoCookieName)) {

      $event->getResponse()->headers->clearCookie($this->ssoCookieName, '/', $this->ssoCookieDomain);
    }
  }

  /**
   *
   */
  public function hasValidSsoCookie($request) {
    return (NULL !== $this->getValidSsoCookie($request));
  }

  /**
   * Extracts a "valid" SSO cookie.
   *
   * Valid <=> exists ∧ ¬anonymous.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return string|null
   */
  public function getValidSsoCookie(Request $request) {
    if (!$request->cookies->has($this->ssoCookieName)) {
      return NULL;
    }

    $cookie = $request->cookies->get($this->ssoCookieName);
    /*
     * ignore anonymous / fallback cookies:
     *   old version: starts with "err"
     *   new version: starts with "a" + 64 hexdec chars
     */
    return preg_match('/^(?:err|a[0-9a-f]{64}$)/', $cookie) ? NULL : $cookie;
  }

  /**
   *
   */
  public function triggerClearSsoCookie() {
    $this->clearTokenTriggered = TRUE;
  }

  /**
   *
   */
  public function setCookie($content) {
    $this->logger->info("Set cookie " . $this->ssoCookieName . " with content $content on domain $this->ssoCookieDomain");
    return setcookie(
      $this->ssoCookieName,
      $content,
      !empty($seconds = $this->harbourmasterSettings->get('sso_cookie_lifetime'))
        ? REQUEST_TIME + $seconds : 0,
      '/',
      "$this->ssoCookieDomain"
    // Domain and path should be set automatically.
    );
  }

}
