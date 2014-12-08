<?php


namespace FlyFoundation\Models;

use FlyFoundation\Core\Generic;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Models\EntityFields\EntityField;
use FlyFoundation\Models\EntityValidations\EntityValidation;
use FlyFoundation\Util\Map;

abstract class GenericEntity implements Entity, Generic{
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

    public function getPersistentData($mustBeCalledFromDataMapper)
    {
        if($mustBeCalledFromDataMapper != "This is called from the data mapper"){

            $notFromDataMapperText = "The get persistent data
            function can only be called from the data store (persistence layer).
            If you need to access properties of an entity, use the relevant
            accessor functions (eg. get(fieldName)).";

            throw new InvalidOperationException($notFromDataMapperText);
        }

        $result = [];

        foreach($this->fields->asArray() as $field)
        {
            /** @var $field \FlyFoundation\Models\EntityFields\EntityField */
            $result[$field->getName()] = $field->getValue();
        }

        return $result;
    }

    public function setPersistentData(array $data, $mustBeCalledFromDataMapper)
    {
        if($mustBeCalledFromDataMapper != "This is called from the data mapper"){

            $notFromDataMapperText = "The set persistent data
            function can only be called from the data mapper (persistence layer).
            If you need to modify properties of an entity, use the relevant
            mutator functions (eg. set(fieldName, value)).";

            throw new InvalidOperationException($notFromDataMapperText);
        }

        foreach($this->fields->asArray() as $field)
        {
            /** @var $field \FlyFoundation\Models\EntityFields\EntityField */
            if(isset($data[$field->getName()])){
                $field->setValue($data[$field->getName()]);
            }else{
                $field->setValue(null);
            }
        }
    }

    public function addField(EntityField $field)
    {
        $this->fields->put($field->getName(), $field);
    }

    public function addValidation(EntityValidation $validation)
    {
        $this->validations->put($validation->getName(),$validation);
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