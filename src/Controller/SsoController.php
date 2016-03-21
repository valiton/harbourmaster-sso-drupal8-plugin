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

namespace Drupal\hms\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Url;


class SsoController extends ControllerBase {

  public function login() {
//    if ($this->currentUser()->isAuthenticated()) {
//      return $this->redirect('user.page');
//    }
    return [
      '#theme' => 'usermanager.login_page',
    ];
  }

  public function logout() {
    $userManagerUrl = $this->config('hms.settings')->get('user_manager_url');
    // TODO why is this considered a "weak" route?
    $queryString = http_build_query([
      'logout_redirect' => Url::fromRoute('<front>', [], ['absolute' => TRUE])->toString(TRUE)->getGeneratedUrl(),
    ]);

    return new TrustedRedirectResponse(
      $userManagerUrl . '/signout?' . $queryString
    );
  }

}

