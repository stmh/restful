<?php
use Drupal\restful\Base\RestfulRateLimitInterface;

/**
 * @file
 * Contains RestfulRateLimitRequest.
 */

class RestfulRateLimitRequest implements RestfulRateLimitInterface {

  /**
   * {@inheritdoc}
   */
  public function isRequestedEvent(array $request = array()) {
    return TRUE;
  }
}

