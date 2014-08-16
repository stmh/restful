<?php

/**
 * @file
 * Contains RestfulRateLimit.
 */

namespace Drupal\restful\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

class RestfulRateLimit extends ContentEntityBase {

  /**
   * Saves an extra hit.
   */
  public function hit() {
    $this->hits++;
    $this->save();
  }

  /**
   * Checks if the entity is expired.
   */
  public function isExpired() {
    return REQUEST_TIME > $this->expiration;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = array();

    $fields['rlid'] = BaseFieldDefinition::create('integer');
    $fields['event'] = BaseFieldDefinition::create('string');
    $fields['identifier'] = BaseFieldDefinition::create('string');
    $fields['timestamp'] = BaseFieldDefinition::create('created');
    $fields['expiration'] = BaseFieldDefinition::create('integer');
    $fields['hits'] = BaseFieldDefinition::create('integer');

    return $fields;
  }
}
