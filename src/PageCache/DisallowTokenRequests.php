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

namespace Drupal\harbourmaster\PageCache;

use Drupal\Core\Config\Config;
use Drupal\Core\PageCache\RequestPolicyInterface;
use Symfony\Component\HttpFoundation\Request;

class DisallowTokenRequests implements RequestPolicyInterface {

  /**
   * @var string
   */
  protected $tokenName;

  public function __construct(Config $config) {
    $this->tokenName = $config->get('token_name');
  }

  /**
   * {@inheritdoc}
   */
  public function check(Request $request) {
    if ($request->cookies->has($this->tokenName) && !preg_match('/^err/', $request->cookies->get($this->tokenName))) {
      return self::DENY;
    }
    return NULL;
  }

}
