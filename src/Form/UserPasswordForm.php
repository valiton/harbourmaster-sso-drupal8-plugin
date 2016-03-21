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

namespace Drupal\hms\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\user\Form\UserPasswordForm as DrupalUserPasswordForm;
use Drupal\user\UserStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\hms\User\Manager as HmsUserManager;
use Drupal\Core\Link;

class UserPasswordForm extends DrupalUserPasswordForm {

  /**
   * @var HmsUserManager
   */
  protected $hmsUserManager;

  public function __construct(UserStorageInterface $user_storage, LanguageManagerInterface $language_manager, HmsUserManager $hmsUserManager) {
    $this->hmsUserManager = $hmsUserManager;
    parent::__construct($user_storage, $language_manager);
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager')->getStorage('user'),
      $container->get('language_manager'),
      $container->get('hms.user_manager')
    );
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $user = $this->currentUser();
    if ($user->isAuthenticated() && (NULL !== $this->hmsUserManager->findHmsUserKeyForUid($user->id()))) {
      // parent::buildForm would allow for a logged in user to request a pw reset, override for HMS user
      return $this->redirect('hms.login_page');
    }
    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    if ($form_state->hasAnyErrors()) {
      // don't waste time if there's errors anyway
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
      if (NULL !== $this->hmsUserManager->findHmsUserKeyForUid($account->id())) {
        $form_state->setErrorByName(
          'name',
          $this->t('%name is externally registered via HMS. Please use the :link to request your password reset.', array('%name' => $name, ':link' => Link::createFromRoute('appropriate page', 'hms.login_page'))));
      }
    }

  }

}