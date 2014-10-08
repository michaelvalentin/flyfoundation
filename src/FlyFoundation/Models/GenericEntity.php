<?php


namespace FlyFoundation\Models;


use FlyFoundation\Models\EntityFields\EntityField;
use FlyFoundation\Models\EntityValidations\EntityValidation;
use FlyFoundation\Util\Map;
use string;

abstract class GenericEntity implements Entity{
    private $data;
    /** @var \FlyFoundation\Util\Map */
    protected $fields;
    protected $validations;
    private $validationErrors;

    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->fields = new Map();
        $this->validations = new Map();
        $this->validationErrors = [];
    }

    public function getPersistentData($mustBeCalledFromDB)
    {
        // TODO: Implement getPersistentData() method.
    }

    /**
     * @return string[]
     */
    public function getPrimaryKeyNames()
    {
        // TODO: Implement getPrimaryKeyNames() method.
    }

    public function addField(EntityField $field)
    {
        $this->fields->put($field->getName(), $field);
    }

    public function removeField($fieldName)
    {
        $this->fields->remove($fieldName);
    }

    public function addValidation(EntityValidation $validation)
    {
        $this->validations->put($validation->getName(),$validation);
    }

    public function removeValidation($validationName)
    {
        $this->validations->remove($validationName);
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $this->validationErrors = [];
        $success = true;
        foreach($this->validations->asArray() as $validation){
            /** @var EntityValidation $validation */
            if(!$validation->validate($this)){
                $success = false;
                $this->validationErrors[] = $validation->getErrorText();
            }
        }
        return $success;
    }

    /**
     * @return string[]
     */
    public function getErrors()
    {
        return $this->validationErrors;
    }
}