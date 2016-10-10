<?php

namespace Drupal\harbourmaster\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\harbourmaster\Responses\TransparentPixelResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\Core\Config\Config;

/**
 * Class CrossDomainAuthController.
 *
 * @package Drupal\harbourmaster\Controller
 */
class CrossDomainAuthController extends ControllerBase {

  protected $harbourmasterSettings;
  protected $cookieHelper;

  protected $sessionData;
  protected $sessionToken;
  protected $logger;

  const HARBOURMASTER_SESSION_DATA_PATH = '/session/crossdomain';

  /**
   * CrossDomainAuthController constructor.
   *
   * @param \Drupal\Core\Config\Config $harbourmaster_settings
   * @param $cookie_helper
   */
  public function __construct(Config $harbourmaster_settings, $cookie_helper) {
    $this->harbourmasterSettings = $harbourmaster_settings;
    $this->logger = $this->getLogger('harbourmaster');
    $this->cookieHelper = $cookie_helper;
  }

  /**
   *
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('harbourmaster.settings'),
      $container->get('harbourmaster.cookie_helper')
    );
  }

  /**
   *
   */
  public function login(Request $request) {

    $parameters = $request->query;
    if (empty($token = $parameters->get('onetimelogintoken'))) {
      throw new NotFoundHttpException();
    }
    $this->getSessionData($token);
    if ($this->validSession()) {
      $this->setSessionToken();
      $this->startSession();
    }
    return new TransparentPixelResponse();
  }

  /**
   *
   */
  protected function getSessionData($token) {
    $session_data_url = $this->harbourmasterSettings->get('harbourmaster_api_url')
      . '/' . $this->harbourmasterSettings->get('harbourmaster_api_version')
      . '/' . $this->harbourmasterSettings->get('harbourmaster_api_tenant')
      . self::HARBOURMASTER_SESSION_DATA_PATH
      . '?onetimelogintoken=' . $token
      . '&domain=' . $this->cookieHelper->getDomain();

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $session_data_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if (($session_data_string = curl_exec($ch)) === FALSE) {
      $this->logger->error("cURL failed with error @code: @message", [
        '@code' => curl_errno($ch),
        '@message' => curl_error($ch)
      ]);
    }
    curl_close($ch);
    $this->sessionData = json_decode($session_data_string);
  }

  /**
   *
   */
  protected function validSession() {
    if (!isset($this->sessionData->status)
      || !isset($this->sessionData->data->token)) {
      $this->logger->error("The session data retrieved from Usermanager has an unexpected format.");
      return FALSE;
    }
    return !empty($this->sessionData->status);
  }

  /**
   *
   */
  protected function setSessionToken() {
    $this->sessionToken = $this->sessionData->data->token;
  }

  /**
   *
   */
  protected function startSession() {
    return $this->cookieHelper->setCookie($this->sessionToken);
  }

  /**
   *
   */
  protected function invalidateSession() {
    return $this->cookieHelper->setCookie('deleted');
  }

  /**
   *
   */
  public function logout() {
    $this->invalidateSession();
    return new TransparentPixelResponse();
  }

}
