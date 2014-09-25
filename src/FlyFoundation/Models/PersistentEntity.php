<?php

namespace FlyFoundation\Models;

use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\SystemDefinitions\EntityDefinition;
use FlyFoundation\SystemDefinitions\EntityField;

class PersistentEntity implements Entity, Model
{
    protected  $columnValuePairs;
    protected $entityDefinition;

    public function __construct(EntityDefinition $entityDefinition, array $data = array())
    {
        $this->entityDefinition = $entityDefinition;
        $this->columnValuePairs = $data;
    }

    public function getDefinition()
    {
        return $this->entityDefinition;
    }

    public function getPersistentData()
    {
        $persistentFields = $this->getDefinition()->getPersistentFields();
        return $this->fieldsDataAsArray($persistentFields);
    }

    public function getPrimaryKeyValues()
    {
        $primaryKeyFields = $this->entityDefinition->getPrimaryKeyFields();
        $result = $this->fieldsDataAsArray($primaryKeyFields);
        if(count($primaryKeyFields) != count($result)){
            return false;
        }
        return $result;
    }

    protected function getPersistentValue($key)
    {
        if(!isset($this->columnValuePairs[$key])){
            return null;
        }
        return $this->columnValuePairs[$key];
    }

    protected function setPersistentValue($key, $value)
    {
        $field = $this->entityDefinition->getField($key);
        if(!$field instanceof \FlyFoundation\SystemDefinitions\PersistentField){
            throw new InvalidArgumentException("The field ".$key." in the entity ".$this->entityDefinition->getName()." is not persistent and cannot be set this way.");
        }
        if(!$this->valueMatchesType($value,$field->getType())){
            throw new InvalidArgumentException("The value ".$value." doesn not match the field type ".$field->getType()." for field ".$field->getName()." in the entity".$this->entityDefinition->getName());
        }
        $this->columnValuePairs[$key] = $value;
    }

    private function fieldsDataAsArray(array $entityFields)
    {
        $returnData = [];
        $data = $this->columnValuePairs;

        foreach($entityFields as $field){
            if(!$field instanceof EntityField){
                throw new InvalidArgumentException("The supplied array must only contain entries of type EntityField");
            }
            $columnName = $field->getname();
            $value = $this->getPersistentValue($columnName);
            if($value !== null){
                $returnData[$columnName] = $value;
            }
        }
        return $returnData;
    }

    /**
     * @return array
     */
    public function asArray()
    {
        // TODO: Implement asArray() method.
    }

    public function fromArray(array $data)
    {
        // TODO: Implement fromArray() method.
    }

    private function valueMatchesType($value, $type)
    {
        if($type=="integer"){
            return is_int($value);
        }
        if($type=="string"){
            return is_string($value);
        }
        if($type=="DateTime"){
            return $value instanceof \DateTime;
        }
        throw new \InvalidArgumentException("Unknown type ".$type." could not be checked");
    }
}