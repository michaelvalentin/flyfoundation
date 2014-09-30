<?php


namespace FlyFoundation\Models\EntityValidations;


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

    public function setFields(array $fields)
    {
        foreach($fields as $field){
            $this->fields->add($field);
        }
    }

    public function getFields()
    {
        return $this->fields->asArray();
    }

    /**
     * @param Entity $entity
     * @return bool
     */
    abstract public function validate(Entity $entity);

    /**
     * @return string
     */
    abstract public function getErrorText();
} 