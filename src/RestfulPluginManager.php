<?php

/**
 * @file
 * Contains \Drupal\restful\RestfulFieldsPluginManager.
 */

namespace Drupal\restful;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Manages restful plugins.
 */
class RestfulPluginManager extends DefaultPluginManager {

  /**
   * Constructs restful manager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/restful', $namespaces, $module_handler, 'Drupal\restful\Annotation\Restful');
    $this->alterInfo('restful_restful_alter');
    $this->setCacheBackend($cache_backend, 'restful_restful');
  }

}