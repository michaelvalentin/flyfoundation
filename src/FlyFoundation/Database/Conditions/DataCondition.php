<?php

namespace FlyFoundation\Database\Conditions;

use FlyFoundation\Exceptions\InvalidArgumentException;

abstract class DataCondition
{
    private $fieldNames;
    protected $error;

    /**
     * @return string
     */
    public function setFieldNames(array $fieldNames)
    {
        foreach($fieldNames as $fieldName){
            if(!is_string($fieldName)){
                throw new InvalidArgumentException(
                    "Field names can only be of type string"
                );
            }
        }

        $this->fieldNames = $fieldNames;
    }

    /**
     * @return array
     */
    public function getFieldNames()
    {
        return $this->fieldNames;
    }

    /**
     * @return bool
     */
    public abstract function readyForUse();

    public function getError()
    {
        return $this->error;
    }
} 