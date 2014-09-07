<?php
/**
 * @file
 * Contain Drupal\restful_example\Plugin\Restful\NodeArticle11
 */

namespace Drupal\restful_example\Plugin\Restful;

use Drupal\restful\Base\RestfulEntityBaseNode;

/**
 * @Restful(
 *  id = "node-article-1.1",
 *  name = "articles__1_1",
 *  label = @Translation("Articles"),
 *  description = @Translation("Export the article content type."),
 *  resource = "articles",
 *  entity_type = "node",
 *  bundle = "articles",
 *  minor_version = 1
 * )
 */
class NodeArticle11 extends RestfulEntityBaseNode {

  /**
   * Overrides RestfulExampleArticlesResource::getPublicFields().
   */
  public function getPublicFields() {
    $public_fields = parent::getPublicFields();
    unset($public_fields["self"]);
    return $public_fields;
  }

}
