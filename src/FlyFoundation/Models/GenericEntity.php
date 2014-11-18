<?php


namespace FlyFoundation\Models;

use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Models\EntityFields\EntityField;
use FlyFoundation\Models\EntityValidations\EntityValidation;
use FlyFoundation\Util\Map;

abstract class GenericEntity implements Entity{
    private $name;

    /** @var \FlyFoundation\Util\Map */
    protected $fields;
    protected $validations;
    private $validationErrors;

    public function __construct()
    {
        $this->fields = new Map();
        $this->validations = new Map();
        $this->validationErrors = [];
    }

    public function setName($name)
    {
        $namePattern = "/^[A-Za-z][A-Za-z0-9]*$/";
        if(!preg_match($namePattern,$name)){
            throw new InvalidArgumentException("The name '".$name."' is not a valid entity name.");
        }
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPersistentData($mustBeCalledFromDataStore)
    {
        if($mustBeCalledFromDataStore != "This is called from data store"){

            $notFromDataStoreText = "The get persistent data
            function can only be called from the data store (persistence layer).
            If you need to access properties of an entity, use the relevant
            accessor functions (eg. get(fieldName)).";

            throw new InvalidOperationException($notFromDataStoreText);
        }

        $result = [];

        foreach($this->fields as $field)
        {
            /** @var $field \FlyFoundation\Models\EntityFields\EntityField */
            $result[$field->getName()] = $field->getValue();
        }

        return $result;
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