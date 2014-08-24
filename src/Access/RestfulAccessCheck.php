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
use Drupal\restful\Base\RestfulBase;
use Drupal\restful\Controller\Restful;
use Drupal\restful\Exception\RestfulBadRequestException;
use SebastianBergmann\Exporter\Exception;
use Symfony\Component\CssSelector\Node\NodeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

/**
 * Determines access to for node add pages.
 */
class RestfulAccessCheck implements AccessInterface {

  /**
   * Checks access to the form mode.
   *
   * @param \Symfony\Component\Routing\Route $route
   *   The route to check against.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The currently logged in account.
   * @param $api
   *   The API version of the plugin.
   * @param $resource
   *   The type of the resource. i.e: users, files etc. etc.
   *
   * @return string
   *   A \Drupal\Core\Access\AccessInterface constant value.
   */
  public function access(Route $route, Request $request, AccountInterface $account, $api, $resource) {
    if ($api[0] != 'v') {
      // Major version not prefixed with "v".
      return static::DENY;
    }

    if (!$major_version = intval(str_replace('v', '', $api))) {
      // Major version is not an integer.
      return static::DENY;
    }

    $minor_version = !empty($_SERVER['HTTP_X_RESTFUL_MINOR_VERSION']) && is_int($_SERVER['HTTP_X_RESTFUL_MINOR_VERSION']) ? $_SERVER['HTTP_X_RESTFUL_MINOR_VERSION'] : 0;
    if (!RestfulBase::isValidMethod($_SERVER['REQUEST_METHOD'], FALSE)) {
      return static::DENY;
    }

    if (!$plugin = Restful::RestfulPlugins($resource, $major_version . '.' . $minor_version)) {
      return static::DENY;
    }

    return $plugin->access();
  }

}
