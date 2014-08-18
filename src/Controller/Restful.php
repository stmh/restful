<?php

namespace Drupal\restful\Controller;
use Drupal\restful\Base\RestfulEntityBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Holds API function for the restful module.
 */
class Restful {

  CONST RESTFUL = "plugin.manager.restful.restful";

  /**
   * Get all restful plugins.
   *
   * @param string $plugin_name
   *   If provided this function only returns the selected plugin.
   * @param string $api
   *   When initialising a plugin, by providing a plugin name, you can select
   *   the number of the API number. Default set to 1.0
   *
   * @return array|RestfulEntityBase
   *   All plugins for restful resources.
   *
   * @code
   *  // Get the file upload for 1.0 API.
   *  $handler = Restful::RestfulPlugins('file_upload');
   *  // Get the file upload for 1.1 API.
   *  $handler = Restful::RestfulPlugins('file_upload', '1.1');
   * @endcode
   */
  public static function RestfulPlugins($plugin_name = NULL, $api = "1.0") {
    $service = \Drupal::service(self::RESTFUL);

    if ($plugin_name) {
      return $service->createInstance($plugin_name . "-" . $api);
    }

    return $service->getDefinitions();
  }

  /**
   * Get all authentication plugins.
   *
   * @param string $plugin_name
   *   If provided this function only returns the selected plugin.
   * @param string $api
   *   When initialising a plugin, by providing a plugin name, you can select
   *   the number of the API number. Default set to 1.0
   *
   * @return array
   *   All plugins for restful authentication.
   */
  public static function AuthenticationPlugins($plugin_name = NULL, $api = "1.0") {
  }

  /**
   * Get all rate_limit plugins.
   *
   * @param string $plugin_name
   *   If provided this function only returns the selected plugin.
   * @param string $api
   *   When initialising a plugin, by providing a plugin name, you can select
   *   the number of the API number. Default set to 1.0
   *
   * @return array
   *   All plugins for restful authentication.
   */
  public static function LimitPlugins($plugin_name = NULL, $api = "1.0") {
  }

  /**
   * Returns data in JSON format.
   *
   * We do not use drupal_json_output(), in order to maintain the "Content-Type"
   * header.
   *
   * @param $var
   *   (optional) If set, the variable will be converted to JSON and output.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *
   * @see restful_menu_process_callback()
   */
  public static function JsonOutput($var) {
    $response = new Response();

    // Adhere to the API Problem draft proposal.
    $response->headers->set('Status', $var['status']);
    $response->headers->set('Content-Type', 'application/problem+json; charset=utf-8');

    return new JsonResponse($matches);
  }
}
