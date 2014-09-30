<?php


namespace FlyFoundation\Models\EntityFields;


class FloatField extends PersistentField{

    /**
     * @param mixed $data
     * @return bool
     */
    public function acceptsValue($data)
    {
        if(is_int($data) || is_float($data)){
            return true;
        }
        return false;
    }
}