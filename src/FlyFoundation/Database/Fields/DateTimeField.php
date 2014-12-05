<?php


namespace FlyFoundation\Database\Fields;


use DateTime;

class DateTimeField extends DataField{

    protected function validateTypeCompatibility($value)
    {
        if(!$value instanceof DateTime){
            $this->setValidationError(
                "The value ".var_dump($value)." is not a DateTime object, and can not be used as such in field: ".$this->getName()
            );
            return false;
        }

        return true;
    }

    public function convertToStorageFormat($value)
    {
        if(!$value instanceof DateTime){
            return null;
        }
        return $value->format('Y-m-d H:i:s');
    }

    public function convertFromStorageFormat($value)
    {
        if($value === null){
            return null;
        }
        return new DateTime($value);
    }
}