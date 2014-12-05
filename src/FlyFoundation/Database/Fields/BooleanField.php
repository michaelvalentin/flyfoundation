<?php


namespace FlyFoundation\Database\Fields;


use FlyFoundation\Exceptions\InvalidArgumentException;

class BooleanField extends DataField{

    protected function validateTypeCompatibility($value)
    {
        if($value === true || $value === false){
            return true;
        }

        $this->setValidationError("Boolean field ".$this->getName()." is not compatible with the value ".$value);
        return false;
    }

    public function convertToStorageFormat($value)
    {
        if($value === true){
            return 1;
        }elseif($value === false){
            return 0;
        }else{
            return $value;
        }
    }

    public function convertFromStorageFormat($value)
    {
        if($value == 1){
            return true;
        }elseif($value == 0){
            return false;
        }else{
            return $value;
        }
    }
}