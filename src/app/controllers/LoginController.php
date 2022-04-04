<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Phalcon\Http\Response\Cookies;

class LoginController extends Controller
{
    /**
     * Login Controller  function
     * In this First we will check if cookie exists or not
     * If Not then  make User Login  
     * @return void
     */
    public function indexAction()
    {
        // Check if cookie avialble

        // If Post Request process the form 
        // Step 1. Authorize the User 
        // Step 2. Genrate  Jwt token For the user 
        // Step 3. Store Token in the Cookie .
        if ($this->request->isPost()) {
            $user = new Users();
            $user->assign(
                $this->request->getPost(),
                [
                    'email',
                    'password'
                ]
            );
            $check=Users::find([
                'conditions' => 'password = :password: and email = :email:' ,
                'bind'       => [
                    'password' =>$user->password ,
                    'email' => $user->email,
                ]
            ]);
            //if Wrong credentiols
            if($check->count()==0) {
                $response = new Response();
                $response->setStatusCode(403, 'Not Found');
                $response->setContent("Sorry, Authecation Failed");
                $response->send();
                die();
            }
            // Credentials are correct Genrate JWT token 
            $key = "cedcossKey";
            $payload = array(
                "name"=>$check[0]->name,
                "email"=>$check[0]->email,
                "role_type" =>$check[0]->role_type,
                "aud" => "http://Cedcoss.com",
                "iat" => 1356999524,
                "nbf" => 1357000000
            );
            $jwt = JWT::encode($payload, $key, 'HS256');
            //  Now we will store the jwt Token In cookie 
            $this->cookies->set(   
                "login-action",   
                json_encode(["token"=>$jwt]),   
                time() + 15 * 86400   
             );          
            // redirect to the dashboard 
            return $this->response->redirect('/');
            die;
        }
    }
}
