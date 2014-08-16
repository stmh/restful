<?php

/**
 * @file
 * Contains Drupal\restful\Exception\RestfulGoneException
 */
namespace Drupal\restful\Exception;

class RestfulGoneException extends \Exception {

  /**
   * Defines the HTTP error code.
   *
   * @var int
   */
  protected $code = 410;

  /**
   * Defines the description.
   *
   * @var string
   */
  protected $description = 'The resource at this end point is no longer available.';

  /**
   * Defines the problem instance.
   *
   * @var string
   */
  protected $instance = 'help/restful/problem-instances-gone';

}
