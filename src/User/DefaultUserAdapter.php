<?php

namespace Drupal\harbourmaster\User;

use \Drupal\Core\Entity\EntityTypeManagerInterface;
use \Drupal\Core\Config\Config;
use Drupal\Component\Utility\Random;
use Drupal\user\UserInterface;

/**
 *
 */
class DefaultUserAdapter extends AbstractHmsUserAdapter {

  protected $logger;

  /**
   *
   */
  public function __construct(
    Config $harbourmasterSettings,
    EntityTypeManagerInterface $entityTypeManager,
    $logger
  ) {
    $this->logger = $logger;
    parent::__construct($harbourmasterSettings, $entityTypeManager);
  }

  /**
   * @param array $harbourmasterSessionData
   *    The HMS data struct for the current session.
   * @return \Drupal\user\UserInterface
   *    The updated and saved User entity
   */
  public function createUser(array $harbourmasterSessionData) {

    $r = new Random();

    /**
     * @var $user UserInterface
     */
    $user = $this->userStorage->create();

    $user = $this->setUserData($harbourmasterSessionData, $user);

    // Random password so a user can never log in via Drupal.
    $user->setPassword($r->string(32));
    $user->enforceIsNew();
    $user->activate();
    $user->save();

    return $user;
  }

  /**
   * @param array $harbourmasterSessionData
   *    The HMS data struct for the current session.
   * @param \Drupal\user\UserInterface $user
   *    The user associated with the current session userKey.
   * @return bool
   *    Whether the user was updated
   */
  public function updateUser(array $harbourmasterSessionData, UserInterface &$user) {
    if ($user->getChangedTime() >= intval($harbourmasterSessionData['user']['modifiedAt'] / 1000)) {
      return FALSE;
    }

    $user = $this->setUserData($harbourmasterSessionData, $user);
    $user->save();
    return TRUE;
  }

  /**
   * @param $desired_name
   * @return string
   */
  protected function usernameRemoveCollisions($desired_name) {
    $name = $desired_name;
    $i = 1;
    while (!empty($this->userStorage->loadByProperties(['name' => $name]))) {
      // todo: Check USERNAME_MAX_LENGTH before adding suffix.
      $name = $desired_name . '_hms_' . $i++;
    }
    if ($name != $desired_name) {
      $this->logger->warning("Creating user @old_name from Usermanager but changing their username to @name, as there already is a user named @old_name.", ['@name' => $name, '@old_name' => $desired_name]);
    }
    return $name;
  }

  /**
   * @param array $harbourmasterSessionData
   * @param \Drupal\user\UserInterface $user
   * @return \Drupal\user\UserInterface
   */
  protected function setUserData(array $harbourmasterSessionData, UserInterface $user) {

    // Add harbourmaster role.
    if (isset(user_roles()['harbourmaster'])) {
      $user->addRole('harbourmaster');
    }

    // Update email.
    $users = $this->userStorage->loadByProperties(['mail' => $harbourmasterSessionData['user']['email']]);
    if (($user->isNew() && empty($users)) || (!$user->isNew() && (empty($users) || isset($users[$user->getOriginalId()])))) {
      $user->setEmail($harbourmasterSessionData['user']['email']);
    }
    else {
      $this->logger->warning("Creating or updating user @name from Usermanager without saving their email address @email, as there already is a user with the email address @email.", ['@name' => $harbourmasterSessionData['user']['login'], '@email' => $harbourmasterSessionData['user']['email']]);
    }

    // Update username only when new account is being created.
    if ($user->isNew()) {
      $user->setUsername($this->usernameRemoveCollisions($harbourmasterSessionData['user']['login']));
    }

    if (user_picture_enabled()) {
      // TODO this should not be done on every login. maybe.
      $avatar = isset($harbourmasterSessionData['user']['avatarImage']) ? $harbourmasterSessionData['user']['avatarImage'] : '';
      if (!empty($avatar)) {
        // HMS always returns the 75px derivate, we want a bigger one.
        $avatar = preg_replace('#/75_75\.jpg$#', '/150_150.jpg', $avatar);
        $this->logger->info('Settings user data: custom picture, trying to retrieve @uri', ['@uri' => $avatar]);
      }
      else {
        // Build default URL.
        $avatar = $this->harbourmasterSettings->get('user_manager_url') . '/usermanager/avatar/150_150/' . urlencode($harbourmasterSessionData['user']['login']) . '.jpg';
        $this->logger->info('Settings user data: default picture, trying to retrieve @uri', ['@uri' => $avatar]);
      }

      $dir = 'public://harbourmaster_pictures';

      if (file_prepare_directory($dir, FILE_CREATE_DIRECTORY | FILE_MODIFY_PERMISSIONS)) {
        $path = $dir . '/' . $harbourmasterSessionData['userKey'] . '.jpg';
        /**
         * @var FileInterface $file
         */
        if ($file = system_retrieve_file($avatar, $path, TRUE, FILE_EXISTS_REPLACE)) {
          $this->logger->info('Settings user data: retrieved @uri', ['@uri' => $avatar]);
          $user->set('user_picture', $file->id());
          image_path_flush($path);
        }
        else {
          $this->logger->warning('Settings user data: error retrieving @uri', ['@uri' => $avatar]);
        }
      }
    }

    // TODO find out whether this actually works.
    $user->setChangedTime(intval($harbourmasterSessionData['user']['modifiedAt'] / 1000));
    return $user;
  }

}
