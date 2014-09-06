<?php
/**
 * @file
 * Contain Drupal\restful_example\Plugin\Restful\NodeArticle11
 */

namespace Drupal\restful_example\Plugin\Restful;

use Drupal\restful\Base\RestfulEntityBaseNode;

$plugin = array(
  'label' => t('Articles'),
  'resource' => 'articles',
  'name' => 'articles__1_1',
  'entity_type' => 'node',
  'bundle' => 'article',
  'description' => t('Export the article content type.'),
  'class' => 'RestfulExampleArticlesResource__1_1',
  // Set the minor version.
  'minor_version' => 1,
);


/**
 * @Restful(
 *  id = "node-article-1.1",
 * )
 */
class NodeArticle11 extends RestfulEntityBaseNode {

  /**
   * Overrides RestfulExampleArticlesResource::getPublicFields().
   */
  public function getPublicFields() {
    $public_fields = parent::getPublicFields();
    unset($public_fields['self']);
    return $public_fields;
  }

}
