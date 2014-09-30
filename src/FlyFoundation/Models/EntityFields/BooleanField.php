<?php


namespace FlyFoundation\Models\EntityFields;


class BooleanField extends PersistentField{

    /**
     * @param mixed $data
     * @return bool
     */
    public function acceptsValue($data)
    {
        if(is_bool($data)){
            return true;
        }
        return false;
    }
}