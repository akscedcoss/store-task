<?php

namespace App\Listener;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use Permissions;
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
        try {
          $decoded = JWT::decode($value->token, new Key($key, 'HS256'));
          $role = $decoded->role_type;
          echo $role;
          // die;
          // Acl Code  Starts here 
          $aclfile = APP_PATH . '/security/acl.cache';
          if (is_file($aclfile) == true) {
    
            $acl = unserialize(
                file_get_contents($aclfile)
            ); 
            if (!$role || true !== $acl->isAllowed($role,   $controller, $action)) {
              echo " </h1> Access denied 404 </h1><br>" ; 
              echo "You are ".$role."  And Only Allowed For ";
              $permissons = new Permissions();
              $permissons = Permissions::find(['role_type'=>$role]);
          
              foreach ($permissons as $key=>$value)
              {   echo "<br>";
                echo $this->tag->linkTo(
                  [
                    strtolower($value->component) ."/".$value->action,
                    $value->component."+".$value->action,
                
                   ] );
                   echo "<br>";


              }


              die();
            } 
          }
          else{
            echo "No acl File";
            die;
          }

          // Implement ACL here //////////////////////////
        } catch (\Exception $e) {
          echo $e->getMessage();
          die;
        }
      } else {
        // We are on Login Page  Go  with The Flow 
      }
    } else {
      // When Cookie is not Set   Redirect to Login 
      // echo "Enter Berare";
      // die;
      return $this->response->redirect('/login');
    }
  }
}
