<?php


use Phalcon\Mvc\Model;
/**
 * Products Model 
 */
class Products extends Model
{    public $id;
    public $Name;
    public $Description;
    public $Tags;
    public $Price;
    public $Stock;	
}