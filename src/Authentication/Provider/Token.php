<?php

namespace Drupal\hms\Authentication\Provider;

use Drupal\Core\Authentication\AuthenticationProviderInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\Config;
use Drupal\Core\Database\Connection;
use Drupal\Core\Logger\LoggerChannel;
use Symfony\Component\HttpFoundation\Request;
use Drupal\hms\Client\Harbourmaster as HarbourmasterClient;
use Drupal\Core\Session\UserSession;

class Token implements AuthenticationProviderInterface {

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
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a new token authentication provider.
   *
   * @param \Drupal\Core\Database\Connection $connection
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   * @param \Drupal\hms\Client\Harbourmaster $hmsClient
   * @param \Drupal\Core\Config\Config $config
   * @param \Drupal\Core\Logger\LoggerChannel $logger
   */
  public function __construct(Connection $connection, CacheBackendInterface $cache, HarbourmasterClient $hmsClient, Config $config, LoggerChannel $logger) {
    $this->connection = $connection;
    $this->cacheTtl = $config->get('token_ttl');
    $this->tokenCookieName = $config->get('token_name');
    $this->cache = $cache;
    $this->hmsClient = $hmsClient;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(Request $request) {
    return $request->cookies->has($this->tokenCookieName);
  }

  /**
   * {@inheritdoc}
   */
  public function authenticate(Request $request) {
    $token = $request->cookies->get($this->tokenCookieName);
    if (!$token) {
      return null;
    }

    $cid = 'hmsdata:' . $token;
    if ($data = $this->cache->get($cid)) {
      $this->logger->debug('Login from cached');
      $this->logger->debug('{data}', ['data' => $data]);
    } else if ($data = $this->hmsClient->setToken($token)->getSession()) {
      $this->logger->debug('Login from HMS');
      $this->logger->debug('{data}', ['data' => $data]);
      $this->cache->set($cid, $data, time() + $this->cacheTtl);
    } else {
      $this->logger->debug('No such session');
    }

  }

}