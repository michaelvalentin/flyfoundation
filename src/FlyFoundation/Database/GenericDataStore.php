<?php

namespace FlyFoundation\Database;

use FlyFoundation\Core\Generic;
use FlyFoundation\Database\Fields\DataField;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Util\NameManipulator;
use PDO;
use PDOException;
use FlyFoundation\Exceptions\InvalidArgumentException;

abstract class GenericDataStore implements DataStore, Generic
{
    /** @var string */
    private $entityName;

    /** @var string */
    private $storageName;

    /** @var DataField[] */
    private $fields = array();


    private $validationError;

    /**
     * @param string $name
     */
    public function setStorageName($name)
    {
        $this->storageName = $name;
    }

    public function getStorageName()
    {
        if($this->storageName){
            return $this->storageName;
        }else{
            $nameManipulator = new NameManipulator();
            return $nameManipulator->toUnderscored($this->entityName);
        }
    }

    public function setEntityName($name)
    {
        $this->entityName = $name;
    }

    public function getEntityName()
    {
        return $this->entityName;
    }

    public function addField(DataField $field)
    {
        $this->fields[$field->getName()] = $field;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getField($name)
    {
        if(!isset($this->fields[$name])){
            return null;
        }

        return $this->fields[$name];
    }


    public function isValidData(array $data)
    {
        foreach($this->getFields() as $fieldName => $field){
            /** @var $field DataField */

            $dataExists = isset($data[$fieldName]);
            $value = $dataExists ? $data[$fieldName] : null;

            if(!$field->validateValue($value)){
                $this->validationError = "The value for the field: '".$field->getName()."' in the ".get_called_class().": '".$this->getEntityName()."' could not be validated for writing to persistent storage, and gave the following message: ".$field->getErrorText();
                return false;
            }
        }

        foreach(array_keys($data) as $fieldName){
            if(!$this->getField($fieldName)){
                $this->validationError = "The data supplied for the ".get_called_class().": ".$this->getEntityName()." contained a value labeled: ".$fieldName.", which does not correspond to any known fields in this DataStore.";
                return false;
            }
        }
        return true;
    }

    public function isValidIdentity(array $identity)
    {
        $identifyFields = $this->getIdentityFields();

        if(count(array_diff_key($identifyFields,$identity))){
            $this->validationError = "The identity of the ".get_called_class().": ".$this->getEntityName()." expected the fields (".implode(",",array_keys($identifyFields)).") but insted got (".implode(",",array_keys($identity)).")";
            return false;
        }

        foreach($identifyFields as $fieldName => $field){
            /** @var $field DataField */
            if(!$field->validateValue($identity[$fieldName],true)){
                $this->validationError = "The value for the field: '".$field->getName()."' in the ".get_called_class().": '".$this->getEntityName()."' could not be validated for writing to persistent storage, and gave the following message: ".$field->getErrorText();
                return false;
            }
        }
        return true;
    }

    protected function validateData(array $data)
    {
        if(!$this->isValidData($data)){
            throw new InvalidArgumentException($this->validationError);
        }
    }

    protected function validateIdentity(array $identity)
    {
        if(!$this->isValidIdentity($identity)){
            throw new InvalidArgumentException($this->validationError);
        }
    }

    public function extractIdentity(array $data)
    {
        $identifyFields = $this->getIdentityFields();

        $identity = [];
        foreach($identifyFields as $fieldName => $field){
            $identity[$fieldName] = isset($data[$fieldName]) ? $data[$fieldName] : null;
        }

        return $identity;
    }

    private function getIdentityFields()
    {
        $identifyFields = [];
        foreach($this->getFields() as $field){
            if($field->isInIdentifier()){
                $identifyFields[$field->getName()] = $field;
            }
        }

        return $identifyFields;
    }

    protected function convertToStorageFormat(array $data)
    {
        foreach($this->getFields() as $field){
            if(isset($data[$field->getName()])){
                $data[$field->getName()] = $field->convertToStorageFormat($data[$field->getName()]);
            }
        }
        return $data;
    }

    protected function convertFromStorageFormat(array $data)
    {
        foreach($this->getFields() as $field){
            if(isset($data[$field->getName()])){
                $data[$field->getName()] = $field->convertFromStorageFormat($data[$field->getName()]);
            }
        }
        return $data;
    }
} 