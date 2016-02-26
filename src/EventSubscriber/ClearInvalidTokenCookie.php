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

namespace Drupal\hms\EventSubscriber;


use Drupal\Core\Config\Config;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ClearInvalidTokenCookie implements EventSubscriberInterface {

  /**
   * @var string
   */
  protected $ssoCookieName;

  /**
   * @var bool
   */
  protected $clearTokenTriggered = FALSE;

  /**
   * @var string
   */
  protected $ssoCookieDomain;

  /**
   * ClearInvalidTokenCookie constructor.
   *
   * @param \Drupal\Core\Config\Config $config
   */
  public function __construct(Config $config) {
    $this->ssoCookieName = $config->get('sso_cookie_name');
    $this->ssoCookieDomain = $config->get('sso_cookie_domain');
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
    // TODO test this in conjunction with Drupal's own login
    if ($this->clearTokenTriggered && $event->getRequest()->cookies->has($this->ssoCookieName)) {
      $event->getResponse()->headers->clearCookie($this->ssoCookieName, '/', $this->ssoCookieDomain);
    }
  }

  public function triggerClearCookie() {
    $this->clearTokenTriggered = TRUE;
  }

}