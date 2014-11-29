<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Util\Enum;

class ValidationDefinition extends DefinitionComponent{
    protected $validationType;
    protected $fieldNames = [];

    public function setType($validationType)
    {
        $this->requireOpen();
        if(!ValidationType::isValidValue($validationType)){
            throw new InvalidArgumentException(
                $validationType." is not a valid type for a Validation. Use the ValidationType enum, eg. ValidationType::Required, to properly set the type of the validation"
            );
        }
        $this->validationType = $validationType;
    }

    public function getType()
    {
        return $this->validationType;
    }

    public function setFieldNames(array $fieldNames)
    {
        $this->requireOpen();
        foreach($fieldNames as $fieldName){
            if(!is_string($fieldName)){
                throw new InvalidArgumentException(
                    "Field names for supplied for a validation must be strings only"
                );
            }
        }
        $this->fieldNames = array_values($fieldNames);
    }

    public function getFieldNames()
    {
        return $this->fieldNames;
    }
}

abstract class ValidationType extends Enum{
    const Required = 1;
    const GreaterThan = 2;
    const LessThan = 3;
    const Equals = 4;
    const GreaterThanOrEqual = 5;
    const LessThanOrEqual = 6;
}