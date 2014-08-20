<?php

namespace Drupal\restful\Controller;

use Drupal\restful\Base\RestfulAuthenticationInterface;
use Drupal\restful\Base\RestfulEntityInterface;
use Drupal\restful\Base\RestfulRateLimitInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Holds API function for the restful module.
 */
class Restful {

  CONST RESTFUL = "plugin.manager.restful.restful";
  CONST AUTHENTICATION = "plugin.manager.restful.authentication";
  CONST RATE_LIMIT = "plugin.manager.restful.rate_limit";

  /**
   * Get all restful plugins.
   *
   * @param string $plugin_name
   *   If provided this function only returns the selected plugin.
   * @param string $api
   *   When initialising a plugin, by providing a plugin name, you can select
   *   the number of the API number. Default set to 1.0
   *
   * @return array|RestfulEntityInterface
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
    return self::GetPlugins(self::RESTFUL, $plugin_name, $api);
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
   * @return array|RestfulAuthenticationInterface
   *   All plugins for restful authentication.
   *
   * @code
   *  // Get the restful authentication for 1.0 API.
   *  $handler = Restful::RestfulPlugins('restful_authentication');
   *  // Get the restful authentication for 1.1 API.
   *  $handler = Restful::RestfulPlugins('restful_authentication', '1.1');
   * @endcode
   */
  public static function AuthenticationPlugins($plugin_name = NULL, $api = "1.0") {
    return self::GetPlugins(self::AUTHENTICATION, $plugin_name, $api);
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
   * @return array|RestfulRateLimitInterface
   *   All plugins for restful authentication.
   *
   * @code
   *  // Get the restful authentication for 1.0 API.
   *  $handler = Restful::RestfulPlugins('request');
   *  // Get the restful authentication for 1.1 API.
   *  $handler = Restful::RestfulPlugins('request', '1.1');
   * @endcode
   */
  public static function RateLimitPlugins($plugin_name = NULL, $api = "1.0") {
    return self::GetPlugins(self::RATE_LIMIT, $plugin_name, $api);
  }

  /**
   * Private function for get all the type of plugins.
   *
   * @param $service
   *   The name of the service declaring the plugin.
   * @param null $plugin_name
   *   If provided this function only returns the selected plugin.
   * @param string $api
   *   When initialising a plugin, by providing a plugin name, you can select
   *   the number of the API number. Default set to 1.0
   */
  private static function GetPlugins($service, $plugin_name = NULL, $api = "1.0") {
    $service = \Drupal::service($service);

    if ($plugin_name) {
      return $service->createInstance($plugin_name . "-" . $api);
    }

    return $service->getDefinitions();
  }

  /**
   * Returns data in JSON format.
   *
   * We do not use drupal_json_output(), in order to maintain the "Content-Type"
   * header.
   *
   * @param $api
   *   The API version.
   * @param $resource
   *   The resource.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *
   * @see restful_menu_process_callback()
   */
  public static function JsonOutput($api, $resource) {
    $response = new Response();

    // Adhere to the API Problem draft proposal.
//    $response->headers->set('Status', $var['status']);
//    $response->headers->set('Content-Type', 'application/problem+json; charset=utf-8');

    return new JsonResponse('welcome!' . $api . $resource);

    // Original functionality.
    $major_version = intval(str_replace('v', '', $major_version));
    $minor_version = !empty($_SERVER['HTTP_X_RESTFUL_MINOR_VERSION']) && is_numeric($_SERVER['HTTP_X_RESTFUL_MINOR_VERSION']) ? $_SERVER['HTTP_X_RESTFUL_MINOR_VERSION'] : 0;
    $handler = restful_get_restful_handler($resource_name, $major_version, $minor_version);

    $path = func_get_args();
    unset($path[0], $path[1]);
    $path = implode('/', $path);

    $method = strtolower($_SERVER['REQUEST_METHOD']);

    if ($method == 'options') {
      // OPTIONS method is a special case that might be sent by the browser
      // before the actual method to check for CORS (known as preflight OPTIONS).
      drupal_add_http_header('Status', 204);
      drupal_add_http_header('Content-Type', 'application/hal+json; charset=utf-8');
      return;
    }

    $request = restful_parse_request();

    try {
      $result = $handler->{$method}($path, $request);
      // Allow the handler to change the HTTP headers.
      foreach ($handler->getHttpHeaders() as $key => $value) {
        drupal_add_http_header($key, $value);
      }

      drupal_add_http_header('Content-Type', 'application/hal+json; charset=utf-8');
      return $result;
    }
    catch (RestfulException $e) {
      $result = array(
        'type' => $e->getType(),
        'title' => $e->getMessage(),
        'status' => $e->getCode(),
        'detail' => $e->getDescription(),
      );

      if ($instance = $e->getInstance()) {
        $result['instance'] = $instance;
      }

      if ($errors = $e->getFieldErrors()) {
        $result['errors'] = $errors;
      }
    }
    catch (Exception $e) {
      $result = array(
        'type' => 'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.5.1',
        'title' => $e->getMessage(),
        'status' => 500,
      );
    }

    // Adhere to the API Problem draft proposal.
    drupal_add_http_header('Status', $result['status']);
    drupal_add_http_header('Content-Type', 'application/problem+json; charset=utf-8');
    return $result;
  }
}
