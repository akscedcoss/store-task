<?php

namespace App\Listener;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Phalcon\Events\Event;
use Phalcon\Di\Injectable;

/**
 * Undocumented class
 */
class NotificationsListener extends Injectable
{
  public function beforeHandleRequest(Event $event, $application)
  {
    $req = explode("/", $_SERVER['REQUEST_URI']);
    $controller = ucwords($req[1]) ?? 'index';
    $action = $req[2] ?? 'index';
    // Check Cookie Exists
    if ($this->cookies->has("login-action") ||  $controller == 'Login') {
      if ($this->cookies->has("login-action")) {
        //  Cookie is Set process Cookie 
        $loginCookie = $this->cookies->get("login-action");
        // Get the JWT Token From Cookie 
        $value = $loginCookie->getValue();
        $value = json_decode($value);
        // Decoding JWT Token 
        $key = "cedcossKey";
        try{
          $decoded = JWT::decode($value->token, new Key($key, 'HS256'));
          $role=$decoded->role_type;
          echo $role;
          // die;
          // Implement ACL here 
        }
        catch(\Exception $e)
        {
        echo $e->getMessage();
        die;
        }

      } else {
        // We are on Login Page  Go  with The Flow 
      }
    } else {
      // When Cookie is not Set   Redirect to Login 
      return $this->response->redirect('/login');
    }
   
  }
}
