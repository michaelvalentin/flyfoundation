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
     * @var string
     */
    private $errorText;

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
    public function setAutoIncrement()
    {
        $this->autoIncrement = true;
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

    public function validateValue($value, $ignoreAutoIncrement=false){

        $ai = $this->isAutoIncrement() && !$ignoreAutoIncrement;

        if($value === null && $this->isRequired() && !$ai){
            $this->setValidationError("The field ".$this->getName()." is required, and cannot be null.");
            return false;
        }elseif($value === null){
            return true;
        }

        return $this->validateTypeCompatibility($value);
    }

    public function getErrorText()
    {
        return $this->errorText;
    }

    protected abstract function validateTypeCompatibility($value);

    protected function setValidationError($text)
    {
        $this->errorText = $text;
    }

    public abstract function convertToStorageFormat($value);

    public abstract function convertFromStorageFormat($value);
} 