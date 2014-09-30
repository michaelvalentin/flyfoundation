<?php


namespace FlyFoundation\Models\EntityFields;


class TextField extends PersistentField{

    /**
     * @param mixed $data
     * @return bool
     */
    public function acceptsValue($data)
    {
        if(is_string($data) || is_int($data) || is_float($data)){
            return true;
        }
        return false;
    }
}