<?php

/**
 * @file
 * Contains RestfulEntityBaseTaxonomyTerm.
 */

namespace Drupal\restful\Base;
use Drupal\Core\Entity\EntityInterface;

/**
 * A base implementation for "Taxonomy term" entity type.
 */
class RestfulEntityBaseTaxonomyTerm extends RestfulEntityBase {

  /**
   * Overrides \RestfulEntityBase::setPropertyValues().
   *
   * Set the "vid" property on new terms.
   */
  protected function setPropertyValues(EntityInterface $wrapper, $null_missing_fields = FALSE) {
    $term = $wrapper->value();
    if (!empty($term->tid)) {
      return;
    }

    $vocabulary = taxonomy_vocabulary_machine_name_load($term->vocabulary_machine_name);
    $term->vid = $vocabulary->vid;

    parent::setPropertyValues($wrapper, $null_missing_fields);
  }

  /**
   * Overrides \RestfulEntityBase::checkPropertyAccess().
   *
   * Allow user to create a label for the unsaved term, even if the user doesn't
   * have access to update existing terms, as required by the entity metadata
   * wrapper's access check.
   */
  protected function checkPropertyAccess(EntityInterface $property, $op = 'edit', EntityInterface $wrapper) {
    $info = $property->info();
    $term = $wrapper->value();
    if (!empty($info['name']) && $info['name'] == 'name' && empty($term->tid) && $op == 'edit') {
      return TRUE;
    }
    return parent::checkPropertyAccess($property, $op, $wrapper);
  }
}
