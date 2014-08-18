<?php

/**
 * @file
 * Contains RestfulEntityBaseUser.
 */

use Drupal\restful\Base\RestfulEntityBase;
use Drupal\restful\Exception\RestfulForbiddenException;

$plugin = array(
  'label' => t('User'),
  'description' => t('Export the "User" entity.'),
  'resource' => 'users',
  'class' => 'RestfulEntityBaseUser',
  'entity_type' => 'user',
  'bundle' => 'user',
  // Try to authenticate users with all available authentication types.
  'authentication_types' => TRUE,
  // Allow anonymous users to access the resource, given they have the right
  // permissions.
  'authentication_optional' => TRUE,
);

/**
 * @Restful(
 *  id = "user-1.0"
 * )
 */
class RestfulEntityBaseUser extends RestfulEntityBase {

  /**
   * Overrides \RestfulEntityBase::getPublicFields().
   */
  public function getPublicFields() {
    $public_fields = parent::getPublicFields();
    $public_fields['id'] = array(
      'property' => 'uid',
    );

    $public_fields['mail'] = array(
      'property' => 'mail',
    );

    return $public_fields;
  }

  /**
   * Overrides \RestfulEntityBase::getList().
   *
   * Make sure only privileged users may see a list of users.
   */
  public function getList() {
    $account = $this->getAccount();
    if (!user_access('administer users', $account) && !user_access('access user profiles', $account)) {
      throw new RestfulForbiddenException('You do not have access to listing of users.');
    }
    return parent::getList();
  }

  /**
   * Overrides \RestfulEntityBase::getQueryForList().
   *
   * Skip the anonymous user in listing.
   */
  public function getQueryForList() {
    $query = parent::getQueryForList();
    $query->entityCondition('entity_id', 0, '>');
    return $query;
  }
}
