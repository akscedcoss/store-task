<?php

use Phalcon\Mvc\Model;
/**
 * Users Model 
 */
class Users extends Model
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $role_type;
}