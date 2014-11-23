<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Util\Enum;

class ValidationDefinition extends DefinitionComponent{
    private $validationType;
    private $fieldNames = [];

    public function setType($validationType)
    {
        $this->requireOpen();
        if(!ValidationType::isValidValue($validationType)){

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
        $this->fieldNames = $fieldNames;
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