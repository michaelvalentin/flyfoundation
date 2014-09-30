<?php


namespace FlyFoundation\Models\EntityFields;


use FlyFoundation\Exceptions\InvalidArgumentException;

abstract class PersistentField implements EntityField{

    private $name;
    private $value;

    /**
     * @param string $name
     */
    public function setName($name)
    {
        if(!preg_match("/^[a-zA-Z]+[a-zA-Z0-9]*$/",$name)){
            throw new InvalidArgumentException("Field names should not contain spaces or special characters, can not start with a number, and can not be empty");
        }

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $data
     */
    public function setValue($data)
    {
        if(!$this->acceptsValue($data)){
            throw new InvalidArgumentException("The value supplied for the entity field '".$this->name."' is not a compatible type for the field.");
        }
        $this->value = $data;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}