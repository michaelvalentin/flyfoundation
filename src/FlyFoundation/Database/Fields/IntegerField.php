<?php


namespace FlyFoundation\Database\Fields;


class IntegerField extends DataField{

    protected function validateTypeCompatibility($value)
    {
        $convertedValue = (int) $value;
        $cannotConvert = !is_int($value) && $convertedValue == 0 && $value !== "0";
        if($cannotConvert || !is_int($convertedValue)){
            $this->setValidationError(
                "The value ".$value." could not be converted to an integer in the field: ".$this->getName()
            );
            return false;
        }

        //TODO: Consider maximum values! https://alexander.kirk.at/2007/08/24/what-does-size-in-intsize-of-mysql-mean/

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