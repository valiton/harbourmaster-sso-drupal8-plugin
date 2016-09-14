<?php

namespace Drupal\harbourmaster\PageCache;

use Drupal\Core\Config\Config;
use Drupal\Core\PageCache\RequestPolicyInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
class DisallowTokenRequests implements RequestPolicyInterface {

  /**
   * @var string
   */
  protected $tokenName;

  /**
   *
   */
  public function __construct(Config $config) {
    $this->tokenName = $config->get('sso_cookie_name');
  }

  /**
   * {@inheritdoc}
   */
  public function check(Request $request) {
    if ($request->cookies->has($this->tokenName) && !preg_match('/^err/', $request->cookies->get($this->tokenName))) {
      return self::DENY;
    }
    return NULL;
  }

}
