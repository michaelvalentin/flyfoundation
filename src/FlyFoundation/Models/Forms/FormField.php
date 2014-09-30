<?php

namespace FlyFoundation\Models\Forms;


use FlyFoundation\Models\Model;
use FlyFoundation\Util\Set;

abstract class FormField implements Model
{
    protected $name;
    protected $label;
    protected $value;
    protected $classes;

    public function __construct()
    {
        $this->classes = new Set();
    }

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
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $data
     */
    public function setValue($data)
    {
        $this->value = $data;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $class
     */
    public function addClass($class)
    {
            $this->classes->add($class);
    }

    /**
     * @param string $class
     */
    public function removeClass($class)
    {
        $this->classes->remove($class);
    }

    /**
     * @return string[]
     */
    public function getClasses()
    {
        return $this->classes->asArray();
    }

    /**
     * @return string
     */
    abstract public function getFieldHTML();
} 