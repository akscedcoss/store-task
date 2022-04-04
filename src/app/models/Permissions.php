<?php


use Phalcon\Mvc\Model;
/**
 * Permissions Model 
 */
class Permissions extends Model
{       	
public $id;
public $role;
public $component;
public $action;
}