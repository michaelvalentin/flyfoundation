<?php


namespace FlyFoundation\Database\Fields;


class TextField extends DataField{

    protected function validateTypeCompatibility($value)
    {
        if(!is_string($value)){
            $this->setValidationError(
                "The value ".var_dump($value)." is not a string, and can not be used as such in field: ".$this->getName()
            );
            return false;
        }

        return true;
    }

    public function convertToStorageFormat($value)
    {
        return $value;
    }

    public function convertFromStorageFormat($value)
    {
        return $value;
    }
}