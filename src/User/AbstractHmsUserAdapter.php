<?php

namespace Drupal\harbourmaster\User;

use Drupal\Core\Config\Config;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Psr\Log\LoggerAwareTrait;

/**
 *
 */
abstract class AbstractHmsUserAdapter implements AdapterInterface {

  use LoggerAwareTrait;

  /**
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var Config
   */
  protected $harbourmasterSettings;

  /**
   * AbstractHmsUserAdapter constructor.
   *
   * @param Config $harbourmasterSettings
   * @param EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(Config $harbourmasterSettings, EntityTypeManagerInterface $entityTypeManager) {
    $this->harbourmasterSettings = $harbourmasterSettings;
    $this->entityTypeManager = $entityTypeManager;
  }

}
