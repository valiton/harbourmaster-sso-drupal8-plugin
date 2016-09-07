<?php

namespace Drupal\harbourmaster\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\harbourmaster\User\Manager as HmsUserManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 */
abstract class HmsAwareAbstractBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\harbourmaster\User\Manager
   */
  protected $harbourmasterUserManager;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   *
   */
  public function __construct(
    array $configuration,
  $plugin_id,
  $plugin_definition,
    AccountInterface $currentUser,
  HmsUserManager $harbourmasterUserManager) {

    $this->harbourmasterUserManager = $harbourmasterUserManager;
    $this->currentUser = $currentUser;
    parent::__construct($configuration, $plugin_id, $plugin_definition);

  }

  /**
   *
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('harbourmaster.user_manager')
    );
  }

}
