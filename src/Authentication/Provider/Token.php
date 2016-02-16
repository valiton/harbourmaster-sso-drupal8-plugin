<?php

namespace hms\src\Authentication\Provider;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Database\Connection;
use Drupal\user\Authentication\Provider\Cookie;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use drupal\hms\Client\Harbourmaster as HarbourmasterClient;
use Drupal\Core\Session\UserSession;

class Token extends Cookie {

  /**
   * @var int
   */
  protected $cacheTtl = 60;

  /**
   * @var string
   */
  protected $tokenCookieName = 'token';

  /**
   * @var CacheBackendInterface
   */
  protected $cache;

  /**
   * @var HarbourmasterClient
   */
  protected $hmsClient;

  /**
   * Constructs a new token authentication provider.
   *
   * @param Connection $connection
   *   The database connection.
   * @param CacheBackendInterface $cache
   * @param HarbourmasterClient $hmsClient
   * @param int $cache_ttl
   *   TTL during which token auth is cached
   * @param string $token_cookie_name
   *   Name of the cookie that contains the token
   *
   */
  public function __construct(Connection $connection, CacheBackendInterface $cache, HarbourmasterClient $hmsClient, $cache_ttl, $token_cookie_name) {
    $this->connection = $connection;
    $this->cacheTtl = $cache_ttl;
    $this->tokenCookieName = $token_cookie_name;
    $this->cache = $cache;
    $this->hmsClient = $hmsClient;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(Request $request) {
    return $request->cookies->has($this->tokenCookieName);
  }

  public function authenticate(Request $request) {
    $token = $request->cookies->get($this->tokenCookieName);

    if ($user = $this->cache->get($token)) {
      // return userdate from cache
    } else if ($hmsSessionData = $this->hmsClient->setToken($token)->getSession()) {

    }

    return $this->getUserFromSession($request->getSession());
  }

  protected function getUserFromSession(SessionInterface $session) {
    if ($uid = $session->get('uid')) {
      // @todo Load the User entity in SessionHandler so we don't need queries.
      // @see https://www.drupal.org/node/2345611
      $values = $this->connection
        ->query('SELECT * FROM {users_field_data} u WHERE u.uid = :uid AND u.default_langcode = 1', [':uid' => $uid])
        ->fetchAssoc();

      // Check if the user data was found and the user is active.
      if (!empty($values) && $values['status'] == 1) {
        // Add the user's roles.
        $rids = $this->connection
          ->query('SELECT roles_target_id FROM {user__roles} WHERE entity_id = :uid', [':uid' => $values['uid']])
          ->fetchCol();
        $values['roles'] = array_merge([AccountInterface::AUTHENTICATED_ROLE], $rids);

        return new UserSession($values);
      }
    }

    // This is an anonymous session.
    return NULL;
  }

}