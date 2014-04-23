<?php


namespace FlyFoundation\Util;


use FlyFoundation\Controllers\BaseController;
use FlyFoundation\Exceptions\InvalidArgumentException;

class BaseControllerList extends ValueList{
    public function add($element)
    {
        //TODO: Think about implementing a check like this..
        /*if(!$element instanceof AbstractBaseController){
            throw new InvalidArgumentException("Base controllers must be of type AbstractBaseController");
        }*/
        parent::add($element);
    }

} 