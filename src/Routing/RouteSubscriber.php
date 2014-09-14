<?php

/**
 * @file
 * Contains \Drupal\restful\Routing\RouteSubscriber.
 */

namespace Drupal\restful\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\restful\Controller\Restful;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Subscriber for restful plugins routes.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * We need to declare the routing items in the routing alter method since
   * restful declare the routing items according to the plugin definition.
   *
   * @param \Symfony\Component\Routing\RouteCollection $collection
   *   The route collection for adding routes.
   */
  protected function alterRoutes(RouteCollection $collection) {
    $plugins = Restful::RestfulPlugins();
    $config = \Drupal::config('restful.restful');

    foreach ($plugins as $plugin) {

      if (!isset($plugin['hook_menu'])) {
        continue;
      }

      if ($plugin['hook_menu'] && empty($plugin['menu_item'])) {
        // Set a default menu item.
        // todo: set to 'api/%'.
        $base_path = $config->get('hook_menu_base_path') ? : 'api/{api}/{resource}';
      }
      else {
        $base_path = $plugin['menu_item'];
      }

      $route = new Route(
        $base_path,
        array('_content' => 'Drupal\restful\Controller\Restful::JsonOutput'),
        array('_restful_restful' => 'TRUE')
      );

      $collection->add('restful.' . $plugin['id'], $route);
    }

    return;

    // A special login endpoint, that returns a JSON output along with the Drupal
    // authentication cookie.
    if (variable_get('restful_user_login_menu_item', TRUE)) {
      $items['api/login'] = array(
        'title' => 'Login',
        'description' => 'Login using base auth and recieve a JSON response along with an authentication cookie.',
        'access callback' => 'user_is_anonymous',
        'page callback' => 'restful_menu_process_callback',
        'page arguments' => array('1', 'login_cookie'),
        'delivery callback' => 'restful_json_output',
      );
    }

    // A special file upload endpoint, that returns a JSON with the newly saved
    // files.
    if (variable_get('restful_file_upload', FALSE)) {
      $items['api/file-upload'] = array(
        'title' => 'File upload',
        'access callback' => 'restful_menu_access_callback',
        'access arguments' => array('v1', 'files'),
        'page callback' => 'restful_menu_process_callback',
        'page arguments' => array('1', 'files'),
        'delivery callback' => 'restful_json_output',
      );
    }

    $items['api/session/token'] = array(
      'page callback' => 'restful_csrf_session_token',
      'access callback' => 'user_is_logged_in',
      'delivery callback' => 'restful_json_output',
      'type' => MENU_CALLBACK,
    );

    return $items;
  }
}
