<?php

namespace FlyFoundation\Forms\FormValidations;

class Required extends FormValidation{

    /**
     * @return bool
     */
    public function validate()
    {
        foreach($this->fields as $field){
            if(empty($field->getValue())) return false;
        }

        return true;
    }
}