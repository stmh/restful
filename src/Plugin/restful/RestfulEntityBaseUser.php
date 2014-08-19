<?php

/**
 * @file
 * Contains RestfulEntityBaseUser.
 */

namespace Drupal\restful\Plugin\Restful;

use Drupal\restful\Base\RestfulEntityBase;
use Drupal\restful\Exception\RestfulForbiddenException;

/**
 * @Restful(
 *  id = "user-1.0",
 *  label = @Translation("User"),
 *  description = @Translation("Export the 'User' entity."),
 *  resource = "users",
 *  entity_type = "user",
 *  bundle = "user",
 *  authentication_types = TRUE,
 *  authentication_optional = TRUE,
 *  hook_menu = FALSE
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
