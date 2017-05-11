<?php

namespace Drupal\harbourmaster\Client;

use Drupal\Core\Config\Config;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerAwareTrait;

/**
 *
 */
class Harbourmaster {

  use LoggerAwareTrait;

  /**
   * @var  ClientInterface */
  protected $client;

  /**
   * @var string
   */
  protected $apiEndpointUrl;

  /**
   * @var string
   */
  protected $tenant;

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
    $this->apiEndpointUrl = $config->get('harbourmaster_api_url');
    $this->tenant = $config->get('harbourmaster_api_tenant');
    $this->client = $client;
  }

  /**
   * @return string
   */
  protected function getApiPrefix() {
    return implode('', [
      $this->apiEndpointUrl,
      '/',
      self::version,
      '/',
      $this->tenant,
      '/',
    ]);
  }

  /**
   *
   */
  public function setToken($token) {
    $this->token = $token;
    return $this;
  }

  /**
   * @return array|null
   */
  public function getSession() {

    $this->logger->info('HMS API call: looking up session for token @token', ['@token' => $this->token]);

    try {
      $response = $this->client->request(
        'GET', $this->getApiPrefix() . 'sessions/mine', [
          'headers' => [
            'x-api-key' => $this->token,
          ],
        ]
      );
    }
    catch (GuzzleException $e) {
      $this->logger->warning('HMS API call: exception while looking up session for token @token, message @message', ['@token' => $this->token, '@message' => $e->getMessage()]);
      return NULL;
    }

    switch ($response->getStatusCode()) {
      case 401:
      case 409:
        $this->logger->info('HMS API call: session lookup denied with status code @code for token @token', ['@token' => $this->token, '@code' => $response->getStatusCode()]);
        return NULL;
    }

    $this->logger->info('HMS API call: session lookup success with status code @code for token @token', ['@token' => $this->token, '@code' => $response->getStatusCode()]);

    return json_decode($response->getBody(), TRUE)['data'];

  }

}
