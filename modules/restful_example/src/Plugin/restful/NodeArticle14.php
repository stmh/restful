<?php
/**
 * @file
 * Contain Drupal\restful_example\Plugin\Restful\NodeArticle14
 */

namespace Drupal\restful_example\Plugin\Restful;

use Drupal\restful\Base\RestfulEntityBaseNode;
use Drupal\restful\Base\RestfulRateLimitManager;

/**
 * @Restful(
 *  id = 'node-article-1.4',
 *  label = @Translation('Articles'),
 *  description = @Translation('Export the article content type with rate limit for anonymous users.'),
 *  name = 'articles__1_4',
 *  entity_type = 'node',
 *  bundle = 'article',
 *  minor_version = '4',
 *  rate_limit = {
 *    request = {
 *      # The 'request' event is the basic event. You can declare your own
 *      # events.
 *      event = 'request',
 *      # Rate limit is cleared every day.
 *      period = new \DateInterval('P1D'),
 *      limits = {
 *        authenticated user = 3,
 *        anonymous user = 2,
 *        administrator = RestfulRateLimitManager::UNLIMITED_RATE_LIMIT
 *      }
 *    }
 *  }
 * )
 */
class NodeArticle14 extends RestfulEntityBaseNode {

}
