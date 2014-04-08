<?php


namespace FlyFoundation\Util;


use FlyFoundation\Exceptions\InvalidArgumentException;

class DirectoryList extends ValueList{
    public function add($element)
    {
        if(!is_dir($element)){
            $error = '"'.$element.'" is not a directory and hence can not be added to the directory list';
            throw new InvalidArgumentException($error);
        }
        parent::add($element);
    }

}