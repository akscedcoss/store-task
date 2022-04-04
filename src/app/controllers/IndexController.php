<?php

use Phalcon\Mvc\Controller;


class IndexController extends Controller
{
    public function indexAction()
    {
 
        // return '<h1>Hello World!</h1>';
    }
    public function logOutAction()
    {
        $this->cookies->get('login-action')->delete();
        return $this->response->redirect('/');
    }
}