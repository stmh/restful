<?php
/**
 * @file
 * Contain Drupal\restful_example\Plugin\Restful\NodeArticle14
 */

namespace Drupal\restful_example\Plugin\Restful;

use Drupal\restful\Base\RestfulEntityBaseNode;
use Drupal\restful\Base\RestfulRateLimitManager;

$plugin = array(
  'label' => t('Articles'),
  'resource' => 'articles',
  'name' => 'articles__1_4',
  'entity_type' => 'node',
  'bundle' => 'article',
  'description' => t('Export the article content type with rate limit for anonymous users.'),
  'class' => 'RestfulExampleArticlesResource',
  // Set the minor version.
  'minor_version' => 4,
  'rate_limit' => array(
    // The 'request' event is the basic event. You can declare your own events.
    'request' => array(
      'event' => 'request',
      // Rate limit is cleared every day.
      'period' => new \DateInterval('P1D'),
      'limits' => array(
        'authenticated user' => 3,
        'anonymous user' => 2,
        'administrator' => RestfulRateLimitManager::UNLIMITED_RATE_LIMIT,
      ),
    ),
  ),
);


/**
 * @Restful(
 *  id = "node-article-1.4",
 * )
 */
class NodeArticle14 extends RestfulEntityBaseNode {

}
