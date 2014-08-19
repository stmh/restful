<?php

/**
 * @file
 * Contains RestfulUserLoginCookie.
 */

namespace Drupal\restful\Plugin\Restful;

use Drupal\restful\Base\RestfulEntityBase;
use Drupal\restful\Base\RestfulInterface;

/**
 * @Restful(
 *  id = "user_login-1.0"
 *  label = @Translation("Login"),
 *  description = @Translation("Login a user and return a JSON along with the authentication cookie.."),
 *  resource = "login_cookie",
 *  entity_type = "user",
 *  bundle = "user",
 *  authentication_types = {
 *    'basic_auth',
 *  },
 *  hook_menu = FALSE
 * )
 */
class RestfulUserLoginCookie extends RestfulEntityBase {

  /**
   * Overrides \RestfulEntityBase::controllers
   *
   * @var array
   */
  protected $controllers = array(
    '' => array(
      RestfulInterface::GET => 'loginAndRespondWithCookie',
    ),
  );

  /**
   * Login a user and return a JSON along with the authentication cookie.
   *
   * @return array
   *   Array with the public fields populated.
   */
  public function loginAndRespondWithCookie() {
    // Login the user.
    $account = $this->getAccount();
    $this->loginUser($account);

    $version = $this->getVersion();
    $handler = restful_get_restful_handler('users', $version['major'], $version['minor']);

    $output = $handler->viewEntity($account->uid);
    $output += restful_csrf_session_token();
    return $output;
  }

  /**
   * Log the user.
   *
   * @param $account
   *   The user object that was retrieved by the \RestfulAuthenticationManager.
   */
  public function loginUser($account) {
    global $user;
    // Override the global user.
    $user = user_load($account->uid);

    $login_array = array ('name' => $account->name);
    user_login_finalize($login_array);
  }
}
