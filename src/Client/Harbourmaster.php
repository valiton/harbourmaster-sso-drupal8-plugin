<?php

namespace Drupal\hms\Client;

use Drupal\Core\Config\Config;
use GuzzleHttp\ClientInterface;


class Harbourmaster {

  /** @var  ClientInterface */
  protected $client;

  /**
   * @var string
   */
  protected $tenant;

  /**
   * @var string
   */
  protected $server;

  /**
   * @var string
   */
  protected $port;

  /**
   * @var bool
   */
  protected $insecure;

  /**
   * @var string
   */
  protected $token;

  const version = 'v1';

  /**
   * Harbourmaster constructor.
   *
   * @param ClientInterface $client
   * @param Config $config
   */
  public function __construct(ClientInterface $client, Config $config) {
    $this->tenant = $config->get('tenant');
    $this->server = $config->get('server');
    $this->port   = $config->get('port');
    $this->insecure = $config->get('insecure');
    $this->client = $client;
  }

  /**
   * @return string
   */
  protected function getApiPrefix() {
    return implode('', [
      $this->insecure ? 'http://' : 'https://',
      $this->server,
      ':',
      $this->port,
      '/',
      self::version,
      '/',
      $this->tenant,
      '/',
    ]);
  }

  public function setToken($token) {
    $this->token = $token;
    return $this;
  }

  /**
   * @return array|null
   */
  public function getSession() {
    $response = $this->client->request(
      'GET', $this->getApiPrefix() . 'sessions/mine', [
        'headers' => [
          'x-api-key' => $this->token,
        ]
      ]
    );

    if ($response->getStatusCode() === 401) {
      return null;
    }

    return json_decode($response->getBody(), true);

  }

}