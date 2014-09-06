<?php

/**
 * @file
 * Contains RestfulEntityBaseNode.
 */

namespace Drupal\restful\Base;

use Drupal\Core\Entity\EntityInterface;
use Drupal\node\NodeInterface;

/**
 * A base implementation for "Node" entity type.
 */
class RestfulEntityBaseNode extends RestfulEntityBase {

  /**
   * Overrides RestfulEntityBase::getQueryForList().
   *
   * Expose only published nodes.
   */
  public function getQueryForList() {
    $query = parent::getQueryForList();
    $query->condition('status', NODE_PUBLISHED);
    return $query;
  }

  /**
   * Overrides RestfulEntityBase::entityPreSave().
   *
   * Set the node author and other defaults.
   */
  public function entityPreSave(NodeInterface $node) {
    if (!$node->id()) {
      return;
    }

    $node->setOwner($this->getAccount());
  }

}
