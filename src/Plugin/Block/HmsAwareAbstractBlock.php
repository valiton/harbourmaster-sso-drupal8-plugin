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
use Drupal\hms\User\Manager as HmsUserManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class HmsAwareAbstractBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\hms\User\Manager
   */
  protected $hmsUserManager;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  public function __construct(
    array $configuration, $plugin_id, $plugin_definition,
    AccountInterface $currentUser, HmsUserManager $hmsUserManager) {

    $this->hmsUserManager = $hmsUserManager;
    $this->currentUser = $currentUser;
    parent::__construct($configuration, $plugin_id, $plugin_definition);

  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('hms.user_manager')
    );
  }

}