<?php

/**
 * @file
 * Contains RestfulRateLimitRequest.
 */

namespace Drupal\restful\Plugin\RateLimit;

use Drupal\restful\Base\RestfulRateLimitInterface;

/**
 * @RateLimit(
 *  id = "request-1.0",
 *  label = @Translation("Any request"),
 *  description = @Translation("The basic rate limit plugin. Every call to a resource is counted."),
 *  name = "request"
 * )
 */
class RestfulRateLimitRequest implements RestfulRateLimitInterface {

  /**
   * {@inheritdoc}
   */
  public function isRequestedEvent(array $request = array()) {
    return TRUE;
  }
}

