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

namespace Drupal\harbourmaster\Form;


use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Asset\LibraryDiscoveryInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Settings extends ConfigFormBase {

  /**
   * @var LibraryDiscoveryInterface
   */
  protected $libraryDiscovery;
  protected $pathValidator;

  public function __construct(ConfigFactoryInterface $config_factory, LibraryDiscoveryInterface $libraryDiscovery, $path_validator) {
    $this->libraryDiscovery = $libraryDiscovery;
    $this->pathValidator = $path_validator;
    parent::__construct($config_factory);
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('library.discovery'),
      $container->get('path.validator')
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
    return ['harbourmaster.settings'];
  }

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'harbourmaster.admin_config_page_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $harbourmasterConfig = $this->config('harbourmaster.settings');

    $form['harbourmaster_endpoint'] = [
      '#type' => 'details',
      '#title' => $this->t('HMS API Configuration'),
      '#open' => TRUE,
    ];

    $form['harbourmaster_endpoint']['harbourmaster_api_url'] = [
      '#type' => 'url',
      '#title' => $this->t('URL to Harbourmaster endpoint'),
      '#default_value' => $harbourmasterConfig->get('harbourmaster_api_url'),
      '#required' => TRUE,
      '#description' => $this->t('This includes protocol and domain (optionally port and/or path prefix).'),
    ];

    $form['harbourmaster_endpoint']['harbourmaster_api_tenant'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Harbourmaster tenant to use'),
      '#default_value' => $harbourmasterConfig->get('harbourmaster_api_tenant'),
      '#required' => TRUE,
      '#description' => $this->t('May only contain %allowed.', ['%allowed' => 'a-z A-Z 0-9 .-_.']),
    ];

    $form['harbourmaster_endpoint']['harbourmaster_lookup_ttl'] = [
      '#type' => 'number',
      '#title' => $this->t('HMS lookup cache TTL'),
      '#default_value' => $harbourmasterConfig->get('harbourmaster_lookup_ttl'),
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
      '#default_value' => $harbourmasterConfig->get('user_manager_url'),
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
      '#default_value' => $harbourmasterConfig->get('sso_cookie_name'),
      '#required' => TRUE,
      '#description' => $this->t('Name of the cookie that contains the HMS token (usually "%default"). May only contain %allowed.', [
        '%default' => 'token',
        '%allowed' => 'a-z A-Z 0-9 .-_.'
      ]),
    ];

    $form['sso_cookie']['sso_cookie_domain'] = [
      '#type' => 'textfield',
      '#title' => $this->t('SSO cookie domain'),
      '#default_value' => $harbourmasterConfig->get('sso_cookie_domain'),
      '#required' => FALSE,
      '#description' => $this->t('Name of the domain which the SSO cookie is set on. Leave empty for this domain.'),
    ];

    $form['sso_cookie']['sso_cookie_lifetime'] = [
      '#type' => 'number',
      '#min' => 0,
      '#title' => $this->t('SSO cookie lifetime'),
      '#default_value' => $harbourmasterConfig->get('sso_cookie_lifetime'),
      '#required' => TRUE,
      '#description' => $this->t('Duration in seconds in which the cookie stays valid. When set to 0, the cookie will expire after browser close.'),
    ];

    $form['cross_domain_auth'] = [
      '#type' => 'details',
      '#title' => $this->t('Cross domain authentication configuration'),
      '#description' => $this->t("The Usermanager sends requests to this Drupal installation to authenticate the user with this domain. The paths set here must match the Usermanager's cross domain login/logout path settings. These are local Drupal paths and must therefore start with a '/'."),
      '#open' => TRUE,
    ];

    $form['cross_domain_auth']['cross_domain_login_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Cross domain login path'),
      '#default_value' => $harbourmasterConfig->get('cross_domain_login_path'),
      '#required' => TRUE,
      '#description' => $this->t('Set this path to what the Usermanager uses to authenticate the user with this domain.'),
    ];

    $form['cross_domain_auth']['cross_domain_logout_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Cross domain logout path'),
      '#default_value' => $harbourmasterConfig->get('cross_domain_logout_path'),
      '#required' => TRUE,
      '#description' => $this->t("Set this path to what the Usermanager uses to invalidate the user's session with this domain."),
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

    if (($value = $form_state->getValue('harbourmaster_api_tenant')) && !preg_match('/[a-zA-Z0-9-_.]+/', $value)) {
      $form_state->setErrorByName('harbourmaster_api_tenant', $this->t('API Tenant %tenant contains invalid characters ', ['%tenant' => $value]));
    }

    // UrlHelper::allowedProtocols seems to depend on some global state? Better use Regex instead of settings that...
    if (($value = $form_state->getValue('harbourmaster_api_url')) && !(UrlHelper::isValid($value, TRUE) && preg_match('#^https?://#', $value))) {
      $form_state->setErrorByName('harbourmaster_api_url', $this->t('API Endpoint %endpoint must be an absolute URL (allowed protocols: @protocols)', [
        '%endpoint' => $form_state->getValue('harbourmaster_api_url'),
        '@protocols' => join(', ', ['http', 'https'])
      ]));
    }

    if (($value = $form_state->getValue('user_manager_url')) && !(UrlHelper::isValid($value, TRUE) && preg_match('#^https?://#', $value))) {
      $form_state->setErrorByName('user_manager_url', $this->t('Usermanager URL %endpoint must be an absolute URL (allowed protocols: @protocols)', [
        '%endpoint' => $form_state->getValue('user_manager_url'),
        '@protocols' => join(', ', ['http', 'https'])
      ]));
    }

    if (($value = $form_state->getValue('sso_cookie_name')) && !preg_match('/[a-zA-Z0-9-_.]+/', $value)) {
      $form_state->setErrorByName('sso_cookie_name', $this->t('SSO cookie name %cookie_name contains invalid characters ', ['%cookie_name' => $value]));
    }

    foreach(['cross_domain_login_path', 'cross_domain_logout_path'] as $field) {
      if (($value = $form_state->getValue($field))) {
        if (!preg_match('/[a-zA-Z0-9-_.]+/', $value)) {//todo Adjust regex to what relative paths can have
          $form_state->setErrorByName($field, $this->t('The relative path @path contains invalid characters.', ['@path' => $value]));
        }
        // Make sure paths start with a slash.
        if (trim($value)[0] != '/') {
          $form_state->setErrorByName($field, $this->t('The relative path @path must start with a slash.', ['@path' => $value]));
        }
        // Make sure paths are not taken.
        if ($url_object = $this->pathValidator->getUrlIfValidWithoutAccessCheck($value)) {
          if ($url_object->getRouteName() != 'hms.cross_domain_login' && $url_object->getRouteName() != 'hms.cross_domain_logout') {
            $form_state->setErrorByName($field, $this->t('The relative path @path already exists.', ['@path' => $value]));
          }
        }
      }
    }

    // Make sure paths are not identical.
    if ($form_state->getValue('cross_domain_login_path') == $form_state->getValue('cross_domain_logout_path')) {
      $error_message = $this->t('The login and logout paths cannot be identical.');
      $form_state->setErrorByName('cross_domain_login_path', $error_message);
      $form_state->setErrorByName('cross_domain_logout_path', $error_message);
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $oldUserManagerUrl = $this->config('harbourmaster.settings')->get('user_manager_url');

    // FIXME libraryDiscovery->clearCachedDefinitions() does not work as expected
    $this->config('harbourmaster.settings')
      ->set('harbourmaster_api_url', rtrim($form_state->getValue('harbourmaster_api_url'), '/'))
      ->set('harbourmaster_api_tenant', $form_state->getValue('harbourmaster_api_tenant'))
      ->set('harbourmaster_lookup_ttl', $form_state->getValue('harbourmaster_lookup_ttl'))
      ->set('sso_cookie_name', $form_state->getValue('sso_cookie_name'))
      ->set('sso_cookie_domain', $form_state->getValue('sso_cookie_domain'))
      ->set('sso_cookie_lifetime', $form_state->getValue('sso_cookie_lifetime'))
      ->set('user_manager_url', rtrim($form_state->getValue('user_manager_url'), '/'))
      ->set('cross_domain_login_path', trim($form_state->getValue('cross_domain_login_path')))
      ->set('cross_domain_logout_path', trim($form_state->getValue('cross_domain_logout_path')))
      ->save();

    if ($this->config('harbourmaster.settings')->get('user_manager_url') !== $oldUserManagerUrl) {
      $this->libraryDiscovery->clearCachedDefinitions();
    }

    parent::submitForm($form, $form_state);
  }
}
