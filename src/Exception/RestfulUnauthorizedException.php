<?php

/**
 * @file
 * Contains Drupal\restful\Exception\RestfulUnauthorizedException.
 */

namespace Drupal\restful\Exception;

class RestfulUnauthorizedException extends RestfulException {

  /**
   * Defines the HTTP error code.
   *
   * @var int
   */
  protected $code = 401;

  /**
   * Defines the description.
   *
   * @var string
   */
  protected $description = 'Unauthorized.';

  /**
   * Defines the problem instance.
   *
   * @var string
   */
  protected $instance = 'help/restful/problem-instances-unauthorized';

}
