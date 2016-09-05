<?php

namespace Drupal\harbourmaster\User;

use Drupal\Component\Utility\Random;
use Drupal\user\UserInterface;

/**
 *
 */
class DefaultUserAdapter extends AbstractHmsUserAdapter {

  protected $logger;

  public function __construct(
    \Drupal\Core\Config\Config $harbourmasterSettings,
    \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager,
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
     * @var UserStorageInterface
     */
    $userStorage = $this->entityTypeManager->getStorage('user');
    $users = $userStorage->loadByProperties(['name' => $harbourmasterSessionData['user']['login']]);
    if (count($users) > 0) {
      $harbourmasterSessionData['user']['login'] = $this->fixUserNameCollision($harbourmasterSessionData['user']['login']);
    }

    /**
     * @var $user UserInterface
     */
    $user = $userStorage->create();

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
   *
   */
  protected function fixUserNameCollision($name) {
    return 'harbourmaster.' . $name;
  }

  /**
   * @param array $harbourmasterSessionData
   * @param \Drupal\user\UserInterface $user
   * @return \Drupal\user\UserInterface
   */
  protected function setUserData(array $harbourmasterSessionData, UserInterface $user) {
    $user->setEmail($harbourmasterSessionData['user']['email']);
    $user->setUsername($harbourmasterSessionData['user']['login']);

    if (user_picture_enabled()) {
      // TODO this should not be done on every login. maybe.
      $avatar = isset($harbourmasterSessionData['user']['avatarImage']) ? $harbourmasterSessionData['user']['avatarImage'] : '';
      if (!empty($avatar)) {
        // HMS always returns the 75px derivate, we want a bigger one.
        $avatar = preg_replace('#/75_75\.jpg$#', '/150_150.jpg', $avatar);
        $this->logger->debug('Settings user data: custom picture, trying to retrieve @uri', ['@uri' => $avatar]);
      }
      else {
        // Build default URL.
        $avatar = $this->harbourmasterSettings->get('user_manager_url') . '/usermanager/avatar/150_150/' . urlencode($harbourmasterSessionData['user']['login']) . '.jpg';
        $this->logger->debug('Settings user data: default picture, trying to retrieve @uri', ['@uri' => $avatar]);
      }

      $dir = 'public://harbourmaster_pictures';

      if (file_prepare_directory($dir, FILE_CREATE_DIRECTORY | FILE_MODIFY_PERMISSIONS)) {
        $path = $dir . '/' . $harbourmasterSessionData['userKey'] . '.jpg';
        /**
         * @var FileInterface $file
         */
        if ($file = system_retrieve_file($avatar, $path, TRUE, FILE_EXISTS_REPLACE)) {
          $this->logger->debug('Settings user data: retrieved @uri', ['@uri' => $avatar]);
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
