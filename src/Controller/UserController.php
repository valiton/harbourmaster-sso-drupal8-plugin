<?php

namespace Drupal\harbourmaster\Controller;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Url;
use Drupal\user\Controller\UserController as DrupalUserController;
use Drupal\user\UserDataInterface;
use Drupal\user\UserStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\harbourmaster\User\Manager as HmsUserManager;
use Psr\Log\LoggerInterface;

/**
 *
 */
class UserController extends DrupalUserController {

  /**
   * @var HmsUserManager
   */
  protected $harbourmasterUserManager;

  /**
   * Constructs a UserController object.
   *
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   * @param \Drupal\user\UserStorageInterface $user_storage
   *   The user storage.
   * @param \Drupal\user\UserDataInterface $user_data
   *   The user data service.
   * @param \Drupal\harbourmaster\User\Manager $harbourmasterUserManager
   */
  public function __construct(
    DateFormatterInterface $date_formatter,
    UserStorageInterface $user_storage,
    UserDataInterface $user_data,
    LoggerInterface $logger,
    HmsUserManager $harbourmasterUserManager)
  {
    $this->harbourmasterUserManager = $harbourmasterUserManager;
    parent::__construct($date_formatter, $user_storage, $user_data, $logger);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('entity_type.manager')->getStorage('user'),
      $container->get('user.data'),
      $container->get('logger.factory')->get('user'),
      $container->get('harbourmaster.user_manager')
    );
  }

  /**
   *
   */
  public function userPage() {
    if ($this->currentUser()->isAuthenticated() && (NULL !== $this->harbourmasterUserManager->findHmsUserKeyForUid($this->currentUser()->id()))) {
      return [
        '#theme' => 'usermanager.profile_page',
      ];
    }
    // TODO will we have a separate profile for HMS users?
    // TODO: Change the autogenerated stub.
    return parent::userPage();
  }

  /**
   *
   */
  public function logout() {
    if ($this->currentUser()->isAuthenticated() && (NULL !== $this->harbourmasterUserManager->findHmsUserKeyForUid($this->currentUser()->id()))) {
      $userManagerUrl = $this->config('harbourmaster.settings')->get('user_manager_url');
      // TODO why is this considered a "weak" route?
      $queryString = http_build_query([
        'logout_redirect' => Url::fromRoute('<front>', [], ['absolute' => TRUE])->toString(TRUE)->getGeneratedUrl(),
      ]);

      return new TrustedRedirectResponse(
        $userManagerUrl . '/signout?' . $queryString
      );
    }
    return parent::logout();
  }

  /**
   *
   */
  public function harbourmasterLoginPage() {
    $ember = \Drupal::request()->query->get('ember') ?: 'default';
    if ($this->currentUser()->isAuthenticated() && $ember != 'confirmation') {
      // drupal_set_message(t('You have been logged in.'));.
      return $this->redirect('<front>');
    }
    return [
      '#theme' => 'usermanager.login_page',
    ];
  }

}
