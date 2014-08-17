<?php

/**
 * @file
 * Contains RestfulRateLimit.
 */

namespace Drupal\restful\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 *  @ContentEntityType(
 *    id = "rate_limit",
 *    label = @Translation("Rate limit"),
 *    bundle_label = @Translation("OG membership type"),
 *    module = "restful",
 *    base_table = "restful_rate_limit",
 *    fieldable = TRUE,
 *    bundle_entity_type = "og_membership_type",
 *    entity_keys = {
 *      "id" = "rlid",
 *      "label" = "identifier",
 *    },
 *    bundle_keys = {
 *      "bundle" = "type"
 *    }
 *  )
 */
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
