<?php

namespace FlyFoundation\Database\Fields;


use FlyFoundation\Exceptions\InvalidArgumentException;

abstract class DataField
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $required;

    /**
     * @var mixed
     */
    private $defaultValue;

    /**
     * @var bool
     */
    private $autoIncrement;

    /**
     * @var bool
     */
    private $inIdentifier;

    /**
     * @var integer
     */
    private $maxLength;

    /**
     * @return boolean
     */
    public function isAutoIncrement()
    {
        return $this->autoIncrement;
    }

    /**
     * @param boolean $autoIncrement
     */
    public function setAutoIncrement($autoIncrement)
    {
        $this->autoIncrement = $autoIncrement;
    }

    /**
     * @return string
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * @param string $dataType
     */
    public function setDataType($dataType)
    {
        $this->dataType = $dataType;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return boolean
     */
    public function isInIdentifier()
    {
        return $this->inIdentifier === true;
    }

    public function setInIdentifier()
    {
        $this->inIdentifier = true;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required === true;
    }

    public function setRequired()
    {
        $this->required = true;
    }

    public function getMaxLength()
    {
        if(!$this->maxLength){
            return 0;
        }
        return $this->maxLength;
    }

    public function setMaxLength($maxLength)
    {
        if(!is_int($maxLength)){
            throw new InvalidArgumentException(
                "Max length must be of type integer"
            );
        }
        $this->maxLength = $maxLength;
    }

    public abstract function validateValue($value);

    public function getErrorText()
    {
        return "The given value did not match the criterion
                for the datatype: ".get_class($this);
    }
} 