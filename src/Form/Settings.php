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

namespace Drupal\hms\Form;


use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Asset\LibraryDiscovery;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Settings extends ConfigFormBase {

  /**
   * @var LibraryDiscovery
   */
  protected $libraryDiscovery;

  public function __construct(ConfigFactoryInterface $config_factory, LibraryDiscovery $libraryDiscovery) {
    $this->libraryDiscovery = $libraryDiscovery;
    parent::__construct($config_factory);
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('library.discovery')
    );
  }


  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return ['hms.settings'];
  }

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'hms.config_page_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $hmsConfig = $this->config('hms.settings');

    $form['hms_endpoint'] = [
      '#type' => 'details',
      '#title' => $this->t('HMS API Configuration'),
      '#open' => TRUE,
    ];

    $form['hms_endpoint']['hms_api_url'] = [
      '#type' => 'url',
      '#title' => $this->t('URL to Harbourmaster endpoint'),
      '#default_value' => $hmsConfig->get('hms_api_url'),
      '#required' => TRUE,
      '#description' => $this->t('This includes protocol and domain (optionally port and/or path prefix).'),
    ];

    $form['hms_endpoint']['hms_api_tenant'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Harbourmaster tenant to use'),
      '#default_value' => $hmsConfig->get('hms_api_tenant'),
      '#required' => TRUE,
      '#description' => $this->t('May only contain %allowed.', ['%allowed' => 'a-z A-Z 0-9 .-_.']),
    ];

    $form['hms_endpoint']['hms_lookup_ttl'] = [
      '#type' => 'number',
      '#title' => $this->t('HMS lookup cache TTL'),
      '#default_value' => $hmsConfig->get('hms_lookup_ttl'),
      '#required' => TRUE,
      '#description' => $this->t('Duration during which the HMS session lookup is cached.'),
    ];

    $form['usermanager_url'] = [
      '#type' => 'details',
      '#title' => $this->t('Usermanager Configuration'),
      '#open' => TRUE,
    ];

    $form['usermanager_url']['user_manager_url'] = [
      '#type' => 'url',
      '#title' => $this->t('URL to Usermanager'),
      '#default_value' => $hmsConfig->get('user_manager_url'),
      '#required' => TRUE,
      '#description' => $this->t('This includes protocol and domain (optionally port and/or path prefix).'),
    ];

    $form['sso_cookie'] = [
      '#type' => 'details',
      '#title' => $this->t('HMS Token Configuration'),
      '#open' => TRUE,
    ];

    $form['sso_cookie']['sso_cookie_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('SSO cookie name'),
      '#default_value' => $hmsConfig->get('sso_cookie_name'),
      '#required' => TRUE,
      '#description' => $this->t('Name of the cookie that contains the HMS token (usually "%default"). May only contain %allowed.', [
        '%default' => 'token',
        '%allowed' => 'a-z A-Z 0-9 .-_.'
      ]),
    ];

    $form['sso_cookie']['sso_cookie_domain'] = [
      '#type' => 'textfield',
      '#title' => $this->t('SSO cookie domain'),
      '#default_value' => $hmsConfig->get('sso_cookie_domain'),
      '#required' => TRUE,
      '#description' => $this->t('Name of the domain which the SSO cookie is set on.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {


    if (($value = $form_state->getValue('sso_cookie_name')) && !preg_match('/[a-zA-Z0-9-_.]+/', $value)) {
      $form_state->setErrorByName('sso_cookie_name', $this->t('SSO cookie name %cookie_name contains invalid characters ', ['%cookie_name' => $value]));
    }

    if (($value = $form_state->getValue('hms_api_tenant')) && !preg_match('/[a-zA-Z0-9-_.]+/', $value)) {
      $form_state->setErrorByName('hms_api_tenant', $this->t('API Tenant %tenant contains invalid characters ', ['%tenant' => $value]));
    }

    // TODO Try connecting to configured HMS endpoint here
    // UrlHelper::allowedProtocols seems to depend on some global state? Better use Regex instead of settings that...
    if (($value = $form_state->getValue('hms_api_url')) && !(UrlHelper::isValid($value, TRUE) && preg_match('#^https?://#', $value))) {
      $form_state->setErrorByName('hms_api_url', $this->t('API Endpoint %endpoint must be an absolute URL (allowed protocols: @protocols)', [
        '%endpoint' => $form_state->getValue('hms_api_url'),
        '@protocols' => join(', ', ['http', 'https'])
      ]));
    }

    // TODO Validate this is a valid subdomain of the Drupal domain and try connecting
    if (($value = $form_state->getValue('user_manager_url')) && !(UrlHelper::isValid($value, TRUE) && preg_match('#^https?://#', $value))) {
      $form_state->setErrorByName('user_manager_url', $this->t('Usermanager URL %endpoint must be an absolute URL (allowed protocols: @protocols)', [
        '%endpoint' => $form_state->getValue('user_manager_url'),
        '@protocols' => join(', ', ['http', 'https'])
      ]));
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $oldUserManagerUrl = $this->config('hms.settings')->get('user_manager_url');

    // TODO clear library discovery service cache
    $this->config('hms.settings')
      ->set('hms_api_url', rtrim($form_state->getValue('hms_api_url'), '/'))
      ->set('hms_api_tenant', $form_state->getValue('hms_api_tenant'))
      ->set('token_ttl', $form_state->getValue('token_ttl'))
      ->set('token_name', $form_state->getValue('token_name'))
      ->set('user_manager_url', rtrim($form_state->getValue('user_manager_url'), '/'))
      ->save();

    if ($this->config('hms.settings')
        ->get('user_manager_url') !== $oldUserManagerUrl
    ) {
      $this->libraryDiscovery->clearCachedDefinitions();
    }

    parent::submitForm($form, $form_state);
  }

}