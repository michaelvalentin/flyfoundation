<?php


namespace FlyFoundation\Models\EntityValidations;


use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Models\Entity;
use FlyFoundation\Util\Set;

abstract class EntityValidation {
    private $name;
    /** @var \FlyFoundation\Util\Set  */
    private $fields;

    public function __construct()
    {
        $this->fields = new Set();
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addFieldName($fieldName)
    {
        if(!is_string($fieldName)){
            throw new InvalidArgumentException(
                "Validation fields should be given as strings"
            );
        }
        $this->fields->add($fieldName);
    }

    public function setFieldNames(array $fieldNames)
    {
        $this->fields = new Set();
        foreach($fieldNames as $field){
            $this->addFieldName($field);
        }
    }

    public function getFieldNames()
    {
        return $this->fields->asArray();
    }

    /**
     * @param array $entityData
     * @return bool
     */
    abstract public function validate(array $entityData);

    /**
     * @return string
     */
    abstract public function getErrorText();
} 