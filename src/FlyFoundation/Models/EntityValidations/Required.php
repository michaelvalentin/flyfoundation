<?php


namespace FlyFoundation\Models\EntityValidations;


use FlyFoundation\Models\Entity;

class Required extends EntityValidation{

    /**
     * @param Entity $entity
     * @return bool
     */
    public function validate(Entity $entity)
    {
        /** @var $fields */
        $fields = $this->getFields();
        foreach($fields as $field){
            $value = $field->getValue();
            if(empty($value)){
                return false;
            }
            if($value == ""){
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
        $fields = $this->getFields();
        $fieldNames = [];
        foreach($fields as $field){
            $fieldNames[] = $field->getName();
        }
        return "The fields '".implode(", ",$fieldNames)."' are required, but appeared to be empty.";
    }
}