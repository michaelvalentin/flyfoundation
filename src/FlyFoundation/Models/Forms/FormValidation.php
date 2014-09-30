<?php

namespace FlyFoundation\Models\Forms;


use FlyFoundation\Models\Model;

abstract class FormValidation implements Model
{
    protected $name;

    /**
     * @var FormField[]
     */
    protected $fields;
    protected $errorText;

    /**
     * @param string $name
     */
    public function setName($name)
    {
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
     * @param FormField[] $fields
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param string $errorText
     */
    public function setErrorText($errorText)
    {
        $this->errorText = $errorText;
    }

    /**
     * @return string
     */
    public function getErrorText()
    {
        return $this->errorText;
    }

    /**
     * @return bool
     */
    abstract public function validate();
}