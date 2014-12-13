<?php

namespace FlyFoundation\Forms\FormValidations;


class SameValue extends FormValidation {

    /**
     * @return bool
     */
    public function validate()
    {
        foreach($this->fields as $field){
            if(!isset($firstValue)) $firstValue = $field->getValue();
            if($field->getValue() !== $firstValue){
                return false;
            }
        }

        return true;
    }
}