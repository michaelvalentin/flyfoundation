<?php


namespace FlyFoundation\Models\EntityValidations;


class Required extends EntityValidation{

    /**
     * @param array $entityData
     * @return bool
     */
    public function validate(Array $entityData)
    {
        $fieldNames = $this->getFieldNames();
        foreach($fieldNames as $fieldName){
            $value = isset($entityData[$fieldName]) ? $entityData[$fieldName] : null;
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
        $fieldNames = $this->getFieldNames();
        return "The fields '".implode(", ",$fieldNames)."' are required, but appeared to be empty.";
    }
}