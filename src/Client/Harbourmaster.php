<?php

namespace drupal\hms\Client;

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
   * @param \GuzzleHttp\ClientInterface $client
   * @param $tenant
   * @param $server
   * @param $port
   * @param $insecure
   */
  public function __construct(ClientInterface $client, $tenant, $server, $port, $insecure) {
    $this->tenant = $tenant;
    $this->server = $server;
    $this->port = $port;
    $this->insecure = $insecure;
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