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

namespace Drupal\hms\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Exception\ClientException as HttpClientException;
use GuzzleHttp\ClientInterface as HttpClientInterface;

class StatusPageController extends ControllerBase {

  /**
   * @var HttpClientInterface
   */
  protected $httpClient;

  public function __construct(ConfigFactoryInterface $config_factory, HttpClientInterface $httpClient) {
    $this->httpClient = $httpClient;
    $this->configFactory = $config_factory;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client'),
      $container->get('config.factory')
    );
  }

  public function status() {

    $hmsConfig = $this->config('hms.settings');
    $hmsApiUrl = $hmsConfig->get('hms_api_url');
    $hmsApiVersion = empty($hmsConfig->get('hms_api_version')) ? 'v1' : $hmsConfig->get('hms_api_version');
    $hmsApiTenant = $hmsConfig->get('hms_api_tenant');
    $userManagerUrl = $hmsConfig->get('user_manager_url');

    $messages = [];

    // Connect to HMS API Server
    if (empty($hmsApiUrl)) {
      $messages['warning'][] = $this->t('HMS API Server: not configured');
    } else {
      try {
        $response = $this->httpClient->request('get', $hmsApiUrl);
        if ($response->getBody() != '<h1>Harbourmaster</h1>') {
          $messages['error'][] = $this->t('HMS API Server: connect successful, but received wrong body');
        } else {
          $messages['status'][] = $this->t('HMS API Server: connect successful');
        }
      } catch (HttpClientException $e) {
        $messages['error'][] = $this->t('HMS API Server: could not connect (:message)', [':message' => $e->getMessage()]);
      }

      // connect to HMS REST endpoint
      if (empty($hmsApiTenant)) {
        $messages['warning'][] = $this->t('HMS API REST Endpoint: not configured');
      } else {
        // HMS has no status endpoint, so we use /login and expect the correct error (as we send no credentials)
        try {
          $response = $this->httpClient->request('post', implode('/', [$hmsApiUrl, $hmsApiVersion, $hmsApiTenant, 'login']));
          $messages['warning'][] = $this->t('HMS API REST Endpoint: connect successful, but unexpected status code (:code)', [':code' => $response->getStatusCode()]);
        } catch (HttpClientException $e) {
          if ($e->getCode() === 409) {
            $messages['status'][] = $this->t('HMS API REST Endpoint: connect successful');
          } else {
            $messages['error'][] = $this->t('HMS API REST Endpoint: could not connect (:message)', [':message' => $e->getMessage()]);
          }
        }
      }
    }

    // Connect to User Manager Endpoint
    if (empty($userManagerUrl) || empty($hmsApiTenant)) {
      $messages['warning'][] = $this->t('HMS User Manager: not configured');
    } else {
      try {
        $response = $this->httpClient->request('get', $userManagerUrl . '/' . $hmsApiTenant);
        if ($response->getStatusCode() !== 200) {
          $messages['error'][] = $this->t('HMS User Manager: connect successful, but unexpected status code (:code)', [':code' => $response->getStatusCode()]);
        } else {
          $messages['status'][] = $this->t('HMS User Manager: connect successful');
        }
      } catch (HttpClientException $e) {
        $messages['error'][] = $this->t('HMS User Manager: could not connect (:message)', [':message' => $e->getMessage()]);
      }
    }

    // TODO check plausibility of HMS / User Manager / Cookie domains

    return [
      '#theme' => 'status_messages',
      '#message_list' => $messages,
      '#status_headings' => [
        'status' => t('Status message'),
        'error' => t('Error message'),
        'warning' => t('Warning message'),
      ],
    ];

  }

}