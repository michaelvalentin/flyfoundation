<?php


namespace FlyFoundation\Models\EntityFields;


class IntegerField extends PersistentField{

    /**
     * @param mixed $data
     * @return bool
     */
    public function acceptsValue($data)
    {
        if(is_int($data)){
            return true;
        }
        return false;
    }
}