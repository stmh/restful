<?php

/**
 * @file
 * Contains \Drupal\restful\RateLimitPluginManager.
 */

namespace Drupal\restful;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Manages restful rate limit plugins.
 */
class RateLimitPluginManager extends DefaultPluginManager {

  /**
   * Constructs restful rate limit manager object.
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
    parent::__construct('Plugin/rate_limit', $namespaces, $module_handler, 'Drupal\restful\Annotation\RateLimit');
    $this->alterInfo('restful_rate_limit_alter');
    $this->setCacheBackend($cache_backend, 'restful_rate_limit');
  }

}