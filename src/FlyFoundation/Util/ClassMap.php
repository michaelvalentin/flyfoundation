<?php


namespace FlyFoundation\Util;


use FlyFoundation\Exceptions\InvalidArgumentException;

class ClassMap extends Map{
    public function put($key, $value)
    {
        if(!class_exists($value)){
            throw new InvalidArgumentException('"'.$value.'" is not a valid class name in the current scope '.
            'and can not be used as a class mapping');
        }

        parent::put($key, $value);
    }

} 