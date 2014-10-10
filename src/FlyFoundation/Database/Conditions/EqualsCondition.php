<?php


namespace FlyFoundation\Database\Conditions;


use FlyFoundation\Exceptions\InvalidArgumentException;

class EqualsCondition extends DataCondition{

    private $requiredValue;
    private $requiredValueSet;

    public function setRequiredValue($value)
    {
        if(is_array($value) || (is_object($value) && (!$value instanceof \DateTime))){
            throw new InvalidArgumentException(
                "Required value in equals condition must not be of type array or object."
            );
        }

        $this->requiredValueSet = true;
        $this->requiredValue = $value;
    }

    public function getRequiredValue()
    {
        return $this->requiredValue;
    }

    /**
     * @return bool
     */
    public function readyForUse()
    {
        if(!isset($this->getFieldNames()[0])){
            $this->error = "An equals condition must work on exactly one field";
            return false;
        }
        if(!$this->requiredValueSet){
            $this->error = "An equals condition must contain a required value";
            return false;
        }
        if(count($this->getFieldNames()) != 1){
            $this->error = "An equals condition must work on exactly one field";
            return false;
        }
        return true;
    }
}