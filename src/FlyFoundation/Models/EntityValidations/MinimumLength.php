<?php


namespace FlyFoundation\Models\EntityValidations;


use FlyFoundation\Exceptions\InvalidArgumentException;

class MinimumLength extends EntityValidation{

    private $limit;

    public function setLimit($limit)
    {
        $limit = (int) $limit;
        if(!is_int($limit) || $limit < 1){
            throw new InvalidArgumentException("The supplied argument for the MinimumLength validation must be greater than 0 and convertible to an integer.");
        }
        $this->limit = $limit;
    }

    /**
     * @param array $entityData
     * @return bool
     */
    public function validate(array $entityData)
    {
        $fieldNames = $this->getFieldNames();
        foreach($fieldNames as $fieldName){
            $value = isset($entityData[$fieldName]) ? $entityData[$fieldName] : null;
            if(strlen($value) < $this->limit){
                return false;
            }
        }
        return true;
    }

    /**
     * @return string
     */
    public function getErrorText()
    {
        $fieldNames = $this->getFieldNames();
        return "The fields '".implode(", ",$fieldNames)."' requires a minimum length of ".$this->limit.", which they do not appear to meet.";
    }
}