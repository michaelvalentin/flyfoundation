<?php


namespace FlyFoundation\Models\EntityFields;


class DateTimeField extends PersistentField{

    /**
     * @param mixed $data
     * @return bool
     */
    public function acceptsValue($data)
    {
        if($data instanceof \DateTime){
            return true;
        }
        return false;
    }
}