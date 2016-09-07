<?php

namespace Drupal\harbourmaster\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\user\Form\UserPasswordForm as DrupalUserPasswordForm;
use Drupal\user\UserStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\harbourmaster\User\Manager as HmsUserManager;

/**
 *
 */
class UserPasswordForm extends DrupalUserPasswordForm {

  /**
   * @var HmsUserManager
   */
  protected $harbourmasterUserManager;

  /**
   *
   */
  public function __construct(UserStorageInterface $user_storage, LanguageManagerInterface $language_manager, HmsUserManager $harbourmasterUserManager) {
    $this->harbourmasterUserManager = $harbourmasterUserManager;
    parent::__construct($user_storage, $language_manager);
  }

  /**
   *
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager')->getStorage('user'),
      $container->get('language_manager'),
      $container->get('harbourmaster.user_manager')
    );
  }

  /**
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $user = $this->currentUser();
    if ($user->isAuthenticated() && (NULL !== $this->harbourmasterUserManager->findHmsUserKeyForUid($user->id()))) {
      // parent::buildForm would allow for a logged in user to request a pw reset, override for HMS user.
      return $this->redirect('harbourmaster.login_page');
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   *
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    if ($form_state->hasAnyErrors()) {
      // don't waste time if there's errors anyway.
      return;
    }
    $name = trim($form_state->getValue('name'));
    // Try to load by email.
    $users = $this->userStorage->loadByProperties(array('mail' => $name));
    if (empty($users)) {
      // No success, try to load by name.
      $users = $this->userStorage->loadByProperties(array('name' => $name));
    }
    $account = reset($users);
    if ($account && $account->id()) {
      if (NULL !== $this->harbourmasterUserManager->findHmsUserKeyForUid($account->id())) {
        $form_state->setErrorByName(
          'name',
          $this->t("@name is externally registered via Harbourmaster. Please use the <a href='@link'>appropriate page</a> to request your password reset.", ['@link' => $GLOBALS['base_url'] . '/harbourmaster/login', '@name' => $name]));
      }
    }

  }

}
