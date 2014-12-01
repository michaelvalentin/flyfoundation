<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Exceptions\InvalidArgumentException;

class EntityDefinition extends DefinitionComponent{
    /** @var FieldDefinition[] */
    protected $fieldDefinitions = [];
    /** @var ValidationDefinition[]  */
    protected $validationDefinitions = [];
    /** @var  SystemDefinition */
    protected $systemDefinition;

    /**
     * @param SystemDefinition $systemDefinition
     */
    public function setSystemDefinition(SystemDefinition &$systemDefinition)
    {
        $this->systemDefinition = $systemDefinition;
    }

    /**
     * @return SystemDefinition
     */
    public function getSystemDefinition()
    {
        return $this->systemDefinition;
    }

    /**
     * @param FieldDefinition[] $fieldDefinitions
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     */
    public function setFieldDefinitions(array $fieldDefinitions)
    {
        $this->requireOpen();
        $this->fieldDefinitions = [];
        foreach($fieldDefinitions as $fieldDefinition){
            $this->addFieldDefinition($fieldDefinition);
        }
    }

    public function addFieldDefinition(FieldDefinition $field)
    {
        $this->requireOpen();
        $this->fieldDefinitions[$field->getName()] = $field;
    }

    /**
     * @return FieldDefinition[]
     */
    public function getFieldDefinitions()
    {
        return $this->fieldDefinitions;
    }

    /**
     * @return PersistentFieldDefinition[]
     */
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

    /**
     * @param string $fieldName
     * @return bool
     */
    public function containsFieldDefinition($fieldName)
    {
        return array_key_exists($fieldName,$this->fieldDefinitions);
    }

    /**
     * @param string $fieldName
     * @return FieldDefinition
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     */
    public function getFieldDefinition($fieldName)
    {
        if(!$this->containsFieldDefinition($fieldName)){
            throw new InvalidArgumentException(
                "No field named $fieldName exists in the Entity: ".$this->getName()
            );
        }
        return $this->fieldDefinitions[$fieldName];
    }

    /**
     * @param ValidationDefinition[] $validationDefinitions
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     */
    public function setValidationDefinitions(array $validationDefinitions)
    {
        $this->requireOpen();
        $this->validationDefinitions = [];
        foreach($validationDefinitions as $validationDefinition){
            $this->addValidationDefinition($validationDefinition);
        }
    }

    public function addValidationDefinition(ValidationDefinition $validation)
    {
        $this->requireOpen();
        $this->validationDefinitions[] = $validation;
    }

    public function getValidationDefinitions()
    {
        return $this->validationDefinitions;
    }
} 