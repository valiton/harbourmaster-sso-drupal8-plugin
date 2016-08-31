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

namespace Drupal\harbourmaster\Responses;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class TransparentPixelResponse
 * @package Drupal\harbourmaster\Responses
 */
class TransparentPixelResponse extends Response
{
  /**
   * Base 64 encoded contents for 1px transparent gif and png.
   * @var string
   */
  const IMAGE_CONTENT =
    'R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw=='
  ;

  /**
   * The response content type.
   * @var string
   */
  const CONTENT_TYPE = 'image/gif';

  /**
   * TransparentPixelResponse constructor.
   */
  public function __construct()
  {
    $content = base64_decode(self::IMAGE_CONTENT);
    parent::__construct($content);
    $this->headers->set('Content-Type', self::CONTENT_TYPE);
    $this->setPrivate();
    $this->headers->addCacheControlDirective('no-cache', true);
    $this->headers->addCacheControlDirective('must-revalidate', true);
  }
}
