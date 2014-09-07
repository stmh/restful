<?php
/**
 * @file
 * Contain Drupal\restful_example\Plugin\Restful\PerRoleContent
 */

namespace Drupal\restful_example\Plugin\Restful;

use Drupal\restful\Base\RestfulEntityBase;
use Drupal\restful\Base\RestfulInterface;

/**
 * @Restful(
 *  id = "pre_role_content",
 *  label = @Translation("Content per role"),
 *  description = @Translation("Get a list of all the nodes authored by users with the administration role."),
 *  resource = "per_role_content",
 *  name = "per_role_content__1_0",
 *  entity_type = "node",
 *  bundle = "article",
 *  authentication_types = {
 *    "cookie"
 *  },
 *  options = {
 *    "roles" = {
 *    }
 *  },
 *  get_children = "PerRoleContent::getChildren",
 *  get_child = "PerRoleContent::getChild"
 * )
 */
class PerRoleContent extends RestfulEntityBase {

  /**
   * Overrides \RestfulEntityBase::controllers.
   */
  protected $controllers = array(
    '' => array(
      RestfulInterface::GET => 'getList',
    ),
  );

  /**
   * Overrides \RestfulEntityBase::publicFields().
   */
  public function getPublicFields() {
    $public_fields = parent::getPublicFields();
    $public_fields['type'] = array(
      'property' => 'type',
      'wrapper_method' => 'getBundle',
      'wrapper_method_on_entity' => TRUE,
    );
    $public_fields['roles'] = array(
      'property' => 'author',
      'sub_property' => 'roles',
      'wrapper_method' => 'label',
    );
    return $public_fields;
  }

  /**
   * Overrides \RestfulEntityBase::getQueryForList().
   */
  public function getQueryForList() {
    $query = parent::getQueryForList();
    // Get the configured roles.
    $options = $this->getPluginInfo('options');

    // Get a list of role ids for the configured roles.
    $roles_list = user_roles();
    $selected_rids = array();
    foreach ($roles_list as $rid => $role) {
      if (in_array($role, $options['roles'])) {
        $selected_rids[] = $rid;
      }
    }
    if (empty($selected_rids)) {
      return $query;
    }

    // Get the list of user ids belonging to the selected roles.
    $uids = db_query('SELECT uid FROM {users_roles} WHERE rid IN (:rids)', array(
      ':rids' => $selected_rids,
    ))->fetchAllAssoc('uid');

    // Restrict the list of entities to the nodes authored by any user on the
    // list of users with the administrator role.
    if (!empty($uids)) {
      $query->propertyCondition('uid', array_keys($uids), 'IN');
    }

    return $query;
  }

  /**
   * Get children implementation.
   */
  static public function getChildren($plugin, $parent) {
    // In PHP 5.3 we can do something like this and defer the logic to a static
    // method. Sadly Drupal's autoloader won't deal with this too good.
    // forward_static_call_array($plugin['class'] . '::getChildren', func_get_args());

    $plugins = array();
    foreach (user_roles() as $role_name) {
      // Child plugins should be named parent:child, with the : being the
      // separator, so that it knows which parent plugin to ask for the child.
      $plugins[$parent . ':' . $role_name] = $plugin;
      $plugins[$parent . ':' . $role_name]['options'] = array(
        'roles' => array($role_name),
      );
      // Create endpoints like api/v1/administrator, api/v1/authenticated, etc'.
      $plugins[$parent . ':' . $role_name]['resource'] = str_replace(' ', '-', drupal_strtolower($role_name));
    }

    // Return the array of plugins available for this parent plugin. This is the
    // same concept as D8 plugin derivatives.
    return $plugins;
  }

  /**
   * Get children implementation.
   */
  static public function getChild($plugin, $parent, $child) {
    // Avoid getting all the children when possible. This is to avoid unneeded
    // expensive operations. If this callback was not provided, then we would call
    // the 'get children' callback and return the plugin for $child.
    $plugin['options'] = array(
      'roles' => array($child),
    );
    return $plugin;
  }
}
