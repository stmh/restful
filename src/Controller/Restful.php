<?php

namespace Drupal\restful\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Holds API function for the restful module.
 */
class Restful {

  /**
   * Get all restful plugins.
   *
   * @param string $plugin_name
   *   If provided this function only returns the selected plugin.
   *
   * @return array
   *   All plugins for restful resources.
   */
  public static function RestfulPlugins($plugin_name = NULL) {
  }

  /**
   * Get all authentication plugins.
   *
   * @param string $plugin_name
   *   If provided this function only returns the selected plugin.
   *
   * @return array
   *   All plugins for restful authentication.
   */
  public static function AuthenticationPlugins($plugin_name = NULL) {
  }

  /**
   * Get all rate_limit plugins.
   *
   * @param string $plugin_name
   *   If provided this function only returns the selected plugin.
   *
   * @return array
   *   All plugins for restful authentication.
   */
  public static function LimitPlugins($plugin_name = NULL) {
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
