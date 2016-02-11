<?php

namespace hms\src\Authentication\Provider;

use Drupal\Core\Database\Connection;
use Drupal\user\Authentication\Provider\Cookie;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;

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
   * Constructs a new token authentication provider.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   * @param int $cache_ttl
   *   TTL during which token auth is cached
   * @param string $token_cookie_name
   *   Name of the cookie that contains the token
   *
   */
  public function __construct(Connection $connection, $cache_ttl, $token_cookie_name) {
    $this->connection = $connection;
    $this->cacheTtl = $cache_ttl;
    $this->tokenCookieName = $token_cookie_name;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(Request $request) {
    return $request->cookies->has($this->tokenCookieName);
  }


}