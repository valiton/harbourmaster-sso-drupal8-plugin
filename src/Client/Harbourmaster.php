<?php

/**
 * Copyright © 2016 Valiton GmbH
 *
 * This file is part of Harbourmaster Drupal Plugin.
 *
 * Harbourmaster Drupal Plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * Harbourmaster Drupal Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Harbourmaster Drupal Plugin.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Drupal\hms\Client;

use Drupal\Core\Config\Config;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerAwareTrait;


class Harbourmaster {

  use LoggerAwareTrait;

  /** @var  ClientInterface */
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
    $this->apiEndpointUrl = $config->get('hms_api_url');
    $this->tenant = $config->get('hms_api_tenant');
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

  public function setToken($token) {
    $this->token = $token;
    return $this;
  }

  /**
   * @return array|null
   */
  public function getSession() {

    $this->logger->debug('HMS API call: looking up session for token @token', [ '@token' => $this->token ]);

    try {
      $response = $this->client->request(
        'GET', $this->getApiPrefix() . 'sessions/mine', [
          'headers' => [
            'x-api-key' => $this->token,
          ]
        ]
      );
    } catch (ClientException $e) {
      $this->logger->debug('HMS API call: exception while looking up session for token @token, message @message', [ '@token' => $this->token, '@message' => $e->getMessage() ]);
      return NULL;
    }

    switch ($response->getStatusCode()) {
      case 401:
      case 409:
        $this->logger->debug('HMS API call: session lookup denied with status code @code for token @token', [ '@token' => $this->token, '@code' => $response->getStatusCode() ]);
        return NULL;
    }

    $this->logger->debug('HMS API call: session lookup success with status code @code for token @token', [ '@token' => $this->token, '@code' => $response->getStatusCode() ]);

    return json_decode($response->getBody(), TRUE)['data'];

  }

}