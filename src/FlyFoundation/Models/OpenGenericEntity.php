<?php


namespace FlyFoundation\Models;


use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Models\EntityFields\EntityField;

class OpenGenericEntity extends GenericEntity{
    public function get($fieldName)
    {
        $field = $this->getFieldsMap()->get($fieldName);
        if(!$field instanceof EntityField){
            throw new InvalidArgumentException("The field '".$fieldName."' does not exist");
        }
        return $field->getValue();
    }

    public function set($fieldName, $value)
    {
        $field = $this->getFieldsMap()->get($fieldName);
        if(!$field instanceof EntityField){
            throw new InvalidArgumentException("The field '".$fieldName."' does not exist");
        }
        $field->setValue($value);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $result = [];
        foreach($this->getFields() as $field){
            $result[$field->getName()] = $this->get($field->getName());
        }
        return $result;
    }

    /**
     * @param array $data
     */
    public function setAll(array $data)
    {
        foreach($data as $name => $value){
            $this->set($name, $value);
        }
    }
} 