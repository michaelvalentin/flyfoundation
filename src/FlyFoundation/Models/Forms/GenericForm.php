<?php

namespace FlyFoundation\Models\Forms;

use FlyFoundation\Models\Forms\FormFields\FormField;
use FlyFoundation\Models\Forms\FormValidations\FormValidation;
use FlyFoundation\Dependencies\AppContext;

class GenericForm implements Form
{
    use AppContext;

    /**
     * @var FormField[]
     */
    private $fields;

    /**
     * @var FormValidation[]
     */
    private $validations;

    /**
     * @var string[]
     */
    private $errors;

    public function __construct()
    {
        $this->fields = array();
        $this->validations = array();
        $this->errors = array();
    }

    /**
     * @param FormField $field
     * @return void
     */
    public function addField(FormField $field)
    {
        $this->fields[$field->getName()] = $field;
    }

    /**
     * @param string $fieldName
     * @return void
     */
    public function removeField($fieldName)
    {
        if(isset($this->fields[$fieldName])) unset($this->fields[$fieldName]);
    }

    /**
     * @return FormField[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param FormValidation $formValidation
     * @return void
     */
    public function addValidation(FormValidation $formValidation)
    {
        $this->validations[$formValidation->getName()] = $formValidation;
    }

    /**
     * @param string $validationName
     * @return void
     */
    public function removeValidation($validationName)
    {
        if(isset($this->validations[$validationName])) unset($this->validations[$validationName]);
    }

    /**
     * @return bool
     */
    public function validate()
    {
        foreach($this->validations as $validation){
            if(!$validation->validate()){
                $this->errors[] = $validation->getErrorText();
            }
        }

        if(!empty($this->errors)) return false;
        else return true;
    }

    /**
     * @return string[]
     */
    public function getData()
    {
        $context = $this->getAppContext();
        $postData = $context->getPostData();

        $data = array();

        foreach($this->getFields() as $field){
            $fieldName = $field->getName();
            if(isset($postData[$fieldName])) $data[$fieldName] = $postData[$fieldName];
        }

        return $data;
    }

    /**
     * @return string[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function asArray()
    {
        $output = array();

        $output['fields'] = array();
        if(!empty($this->fields)){
            foreach($this->fields as $field){
                $output['fields'][] = $field->asArray();
            }
        }

        $output['errors'] = array();
        if(!empty($this->errors)){
            foreach($this->errors as $error){
                $output['errors'][] = $error;
            }
        }

        return $output;
    }

}