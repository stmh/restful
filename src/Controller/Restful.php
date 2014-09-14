<?php

namespace Drupal\restful\Controller;

use Drupal\Core\Plugin\PluginBase;
use Drupal\restful\Base\RestfulAuthenticationInterface;
use Drupal\restful\Base\RestfulEntityInterface;
use Drupal\restful\Base\RestfulInterface;
use Drupal\restful\Base\RestfulRateLimitInterface;
use Drupal\restful\Exception\RestfulException;
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
   *
   * @return Array|PluginBase
   *   A restful/rate limit/authentication plugin instance.
   */
  private static function GetPlugins($service, $plugin_name = NULL, $api = "1.0") {
    $service = \Drupal::service($service);
    $definitions = $service->getDefinitions();

    if ($plugin_name) {
      return in_array($plugin_name . "-" . $api, array_keys($definitions)) ? $service->createInstance($plugin_name . "-" . $api) : NULL;
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

    $major_version = intval(str_replace('v', '', $api));
    $minor_version = !empty($_SERVER['HTTP_X_RESTFUL_MINOR_VERSION']) && is_int($_SERVER['HTTP_X_RESTFUL_MINOR_VERSION']) ? $_SERVER['HTTP_X_RESTFUL_MINOR_VERSION'] : 0;
    $plugin = Restful::RestfulPlugins($resource, $major_version . '.' . $minor_version);

    $path = func_get_args();
    unset($path[0], $path[1]);
    $path = implode('/', $path);

    $method = strtolower($_SERVER['REQUEST_METHOD']);

    if ($method == 'options') {
      // OPTIONS method is a special case that might be sent by the browser
      // before the actual method to check for CORS (known as preflight
      // OPTIONS).
      $response->headers->set('Status', 204);
      $response->headers->set('Content-Type', 'application/hal+json; charset=utf-8');
      return;
    }

    $request = self::parseRequest();

    try {
      $result = $plugin->{$method}($path, $request);
      // Allow the handler to change the HTTP headers.
      foreach ($plugin->getHttpHeaders() as $key => $value) {
        $response->headers->set($key, $value);
      }

      $response->headers->set('Content-Type', 'application/hal+json; charset=utf-8');
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
    catch (\Exception $e) {
      $result = array(
        'type' => 'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.5.1',
        'title' => $e->getMessage(),
        'status' => 500,
      );
    }

    // Adhere to the API Problem draft proposal.
    // todo: fix.
//    $response->headers->set('Status', $var['status']);
    $response->headers->set('Content-Type', 'application/problem+json; charset=utf-8');
    return new JsonResponse($result);
  }

  /**
   * Build the request array from PHP globals and input stream.
   *
   * @return array
   *   The request array.
   */
  public static function parseRequest() {
    $request = NULL;
    $method = strtoupper($_SERVER['REQUEST_METHOD']);

    if ($method == RestfulInterface::GET) {
      $request = $_GET;
    }
    elseif ($method == RestfulInterface::POST) {
      $request = $_POST;
    }

    if (!$request && $query_string = file_get_contents('php://input')) {
      // When trying to POST using curl on simpleTest it doesn't reach
      // $_POST, so we try to re-grab it here.
      parse_str($query_string, $request);
    }

    // This flag is used to identify if the request is done "via Drupal" or "via
    // CURL";
    $request['__application'] = array(
      'rest_call' => TRUE,
      'csrf_token' => !empty($_SERVER['HTTP_X_CSRF_TOKEN']) ? $_SERVER['HTTP_X_CSRF_TOKEN'] : NULL,
    );

    // Allow implementing modules to alter the request.
    \Drupal::moduleHandler()->alter('restful_parse_request', $request);

    return $request;
  }
}
