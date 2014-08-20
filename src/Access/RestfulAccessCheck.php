<?php

/**
 * @file
 * Contains \Drupal\node\Access\NodeAddAccessCheck.
 */

namespace Drupal\restful\Access;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeTypeInterface;

/**
 * Determines access to for node add pages.
 */
class RestfulAccessCheck implements AccessInterface {

  /**
   * Checks access to the node add page for the node type.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The currently logged in account.
   * @param \Drupal\node\NodeTypeInterface $node_type
   *   (optional) The node type. If not specified, access is allowed if there
   *   exists at least one node type for which the user may create a node.
   *
   * @return string
   *   A \Drupal\Core\Access\AccessInterface constant value.
   */
  public function access(AccountInterface $account, NodeTypeInterface $node_type = NULL) {
    return static::ALLOW;
    return static::DENY;

    // The original functionality.
    if ($major_version[0] != 'v') {
      // Major version not prefixed with "v".
      return;
    }

    if (!$major_version = intval(str_replace('v', '', $major_version))) {
      // Major version is not an integer.
      return;
    }

    $minor_version = !empty($_SERVER['HTTP_X_RESTFUL_MINOR_VERSION']) && is_int($_SERVER['HTTP_X_RESTFUL_MINOR_VERSION']) ? $_SERVER['HTTP_X_RESTFUL_MINOR_VERSION'] : 0;
    if (!$handler = restful_get_restful_handler($resource_name, $major_version, $minor_version)) {
      return;
    }

    if (!\RestfulBase::isValidMethod($_SERVER['REQUEST_METHOD'], FALSE)) {
      return;
    }

    return $handler->access();
  }
}
