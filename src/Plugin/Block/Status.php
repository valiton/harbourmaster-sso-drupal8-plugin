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

namespace Drupal\hms\Plugin\Block;


use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\hms\User\Helper as HmsUserHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Status' block.
 *
 * @Block(
 *   id = "hms_status_block",
 *   admin_label = @Translation("HMS Status block"),
 * )
 */
class Status extends BlockBase implements ContainerFactoryPluginInterface {



  /**
   * @var \Drupal\hms\User\Helper
   */
  protected $hmsUserHelper;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountInterface $currentUser, HmsUserHelper $hmsUserHelper) {
    $this->hmsUserHelper = $hmsUserHelper;
    $this->currentUser = $currentUser;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * @inheritdoc
   */
  public function build() {

    if ($this->currentUser->isAnonymous()) {
      $markup = $this->t('not logged in');
    } else {
      $markup = $this->t('Hello :name', [ ':name' => $this->currentUser->getDisplayName()]);
      $userKey = $this->hmsUserHelper->findHmsUserKeyForUid($this->currentUser->id());
      if ($userKey) {
        $markup .= ' (' . $this->t('logged in via HMS #:userkey', [':userkey' => $userKey])  . ')';
      } else {
        $markup .= ' (' . $this->t('logged in via another method')  . ')';
      }
    }

    return [
      '#markup' => $markup,
    ];
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('hms.userhelper')
    );
  }


}