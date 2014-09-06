<?php
/**
 * @file
 * Contain Drupal\restful_example\Plugin\Restful\NodeArticle12
 */

namespace Drupal\restful_example\Plugin\Restful;

use Drupal\restful\Base\RestfulEntityBaseNode;

/**
 * @Restful(
 *  id = 'node-article-1.2',
 *  name = 'articles__1_2',
 *  label = @Translation('Articles'),
 *  description = @Translation('Export the article content type with "cookie" authentication.'),
 *  resource = 'articles',
 *  entity_type = 'node',
 *  bundle = 'article',
 *  minor_version = 2,
 *  authentication_types = {
 *    'cookie'
 *  }
 * )
 */
class NodeArticle12 extends RestfulEntityBaseNode {

}
