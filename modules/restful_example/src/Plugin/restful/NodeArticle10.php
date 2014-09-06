<?php
/**
 * @file
 * Contain Drupal\restful_example\Plugin\Restful\NodeArticle10
 */

namespace Drupal\restful_example\Plugin\Restful;

use Drupal\restful\Base\RestfulEntityBaseNode;

/**
 * @Restful(
 *  id = 'node-article-1.0',
 *  name = 'articles__1_0',
 *  label = @Translation('Articles'),
 *  description = @Translation('Export the article content type with "cookie" authentication.'),
 *  resource = 'articles',
 *  entity_type = 'node',
 *  bundle = 'article',
 *  authentication_types = TRUE,
 *  authentication_optional = TRUE
 * )
 */
class NodeArticle10 extends RestfulEntityBaseNode {

}
