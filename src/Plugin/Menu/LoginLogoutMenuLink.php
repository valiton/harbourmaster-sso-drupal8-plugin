<?php

namespace Drupal\harbourmaster\Plugin\Menu;

use Drupal\Core\Menu\StaticMenuLinkOverridesInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\Plugin\Menu\LoginLogoutMenuLink as DrupalLoginLogoutMenuLink;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\harbourmaster\User\Manager as HmsUserManager;

/**
 *
 */
class LoginLogoutMenuLink extends DrupalLoginLogoutMenuLink {

  /**
   * @var \Drupal\harbourmaster\User\Manager
   */
  protected $harbourmasterUserManager;

  /**
   *
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, StaticMenuLinkOverridesInterface $static_override, AccountInterface $current_user, HmsUserManager $harbourmasterUserManager) {
    $this->harbourmasterUserManager = $harbourmasterUserManager;
    parent::__construct($configuration, $plugin_id, $plugin_definition, $static_override, $current_user);
  }

  /**
   *
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('menu_link.static.overrides'),
      $container->get('current_user'),
      $container->get('harbourmaster.user_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getRouteName() {
    if ($this->currentUser->isAuthenticated()) {
      return 'user.logout';
    }
    else {
      return 'harbourmaster.login_page';
    }
  }

}
