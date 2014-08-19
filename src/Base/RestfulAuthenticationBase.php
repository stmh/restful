<?php

/**
 * @file
 * Contains RestfulAuthenticationBase.
 */

namespace Drupal\restful\Base;

use Drupal\Core\Plugin\PluginBase;

abstract class RestfulAuthenticationBase extends PluginBase implements RestfulAuthenticationInterface {

  /**
   * Settings from the plugin definition.
   *
   * @var array
   */
  protected $settings;

  /**
   * The plugin definition.
   *
   * @var array
   */
  protected $plugin;

  /**
   * Constructor.
   */
  public function ___construct($plugin) {
    $this->settings = $plugin['settings'];
    $this->plugin = $plugin;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(array $request = array(), $method = RestfulInterface::GET) {
    // By default assume that the request can be checked for authentication.
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->plugin['name'];
  }

}
