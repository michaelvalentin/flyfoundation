<?php

namespace FlyFoundation\Models\Forms;


class Required extends FormValidation{

    /**
     * @return bool
     */
    public function validate()
    {
        foreach($this->fields as $field){
            if(empty($field->getValue())){
                $this->setErrorText('A required field is empty');
                break;
            }
        }

        if(!empty($this->getErrorText())) return false;
        else return true;
    }

    /**
     * @return array
     */
    public function asArray()
    {
        $output = array();

        $output['name'] = $this->getName();
        $output['errorText'] = $this->getErrorText();

        return $output;
    }
}