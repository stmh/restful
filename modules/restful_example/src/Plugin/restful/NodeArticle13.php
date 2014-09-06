<?php
/**
 * @file
 * Contain Drupal\restful_example\Plugin\Restful\NodeArticle13
 */

namespace Drupal\restful_example\Plugin\Restful;

use Drupal\restful\Base\RestfulEntityBaseNode;

/**
 * @Restful(
 *  id = 'node-article-1.3',
 *  name = 'articles__1_3',
 *  label = @Translation('Articles'),
 *  description = @Translation('Export the article content type with "token" authentication.'),
 *  resource = 'articles',
 *  entity_type = 'node',
 *  bundle = 'article',
 *  minor_version = 3,
 *  authentication_types = {
 *    'token',
 *  }
 * )
 */
class NodeArticle13 extends RestfulEntityBaseNode {


  /**
   * Overrides RestfulEntityBaseNode::access().
   */
  public function access() {
    return \Drupal::moduleHandler()->moduleExists('restful_token_auth');
  }
}
