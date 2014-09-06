<?php
/**
 * @file
 * Contain Drupal\restful_example\Plugin\Restful\NodeArticle10
 */

namespace Drupal\restful_example\Plugin\Restful;

use Drupal\restful\Base\RestfulEntityBaseNode;

$plugin = array(
  'label' => t('Articles'),
  'resource' => 'articles',
  'name' => 'articles__1_0',
  'entity_type' => 'node',
  'bundle' => 'article',
  'description' => t('Export the article content type with "cookie" authentication.'),
  'class' => 'RestfulExampleArticlesResource',
  'authentication_types' => TRUE,
  'authentication_optional' => TRUE,
);


/**
 * @Restful(
 *  id = "node-article-1.0",
 * )
 */
class NodeArticle10 extends RestfulEntityBaseNode {

}
