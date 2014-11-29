<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Exceptions\InvalidArgumentException;

class EntityDefinition extends DefinitionComponent{
    protected $fieldDefinitions = [];
    protected $validationDefinitions = [];

    public function setFieldDefinitions(array $fieldDefinitions)
    {
        $this->requireOpen();
        $this->fieldDefinitions = [];
        foreach($fieldDefinitions as $fieldDefinition){
            if(!$fieldDefinition instanceof FieldDefinition){
                throw new InvalidArgumentException(
                    "A value supplied as a field definition for the entity ".$this->getName()." was not a field definition"
                );
            }
            $this->fieldDefinitions[$fieldDefinition->getName()] = $fieldDefinition;
        }
    }

    public function getFieldDefinitions()
    {
        return $this->fieldDefinitions;
    }

    public function getPersistentFieldDefinitions()
    {
        $result = [];
        foreach($this->fieldDefinitions as $fieldDefinition){
            if($fieldDefinition instanceof PersistentFieldDefinition){
                $result[$fieldDefinition->getName()] = $fieldDefinition;
            }
        }

        return $result;
    }

    public function containsFieldDefinition($fieldName)
    {
        return array_key_exists($fieldName,$this->fieldDefinitions);
    }

    public function getFieldDefinition($fieldName)
    {
        if(!$this->containsFieldDefinition($fieldName)){
            throw new InvalidArgumentException(
                "No field named $fieldName exists in the Entity: ".$this->getName()
            );
        }
        return $this->fieldDefinitions[$fieldName];
    }

    public function setValidationDefinitions(array $validationDefinitions)
    {
        $this->requireOpen();
        $this->validationDefinitions = [];
        foreach($validationDefinitions as $validationDefinition){
            if(!$validationDefinition instanceof ValidationDefinition){
                throw new InvalidArgumentException(
                    "A value supplied as a validation definition for the entity ".$this->getName()." was not a validation definition"
                );
            }
            $this->validationDefinitions[] = $validationDefinition;
        }
    }

    public function getValidationDefinitions()
    {
        return $this->validationDefinitions;
    }
} 