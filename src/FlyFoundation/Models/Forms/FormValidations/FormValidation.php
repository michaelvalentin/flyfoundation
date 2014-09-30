<?php

namespace FlyFoundation\Models\Forms\FormValidations;


use FlyFoundation\Models\Model;
use FlyFoundation\Models\Forms\FormFields\FormField;

abstract class FormValidation implements Model
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var FormField[]
     */
    protected $fields;

    /**
     * @var string
     */
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
     * @return array
     */
    public function asArray()
    {
        $output = array();

        $output['name'] = $this->getName();
        $output['errorText'] = $this->getErrorText();

        return $output;
    }

    /**
     * @return bool
     */
    abstract public function validate();
}