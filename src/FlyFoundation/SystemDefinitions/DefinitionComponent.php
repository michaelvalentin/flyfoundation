<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidOperationException;

abstract class DefinitionComponent {
    private $settings = [];
    private $name;
    protected $nameValidation = "/^[A-Za-z][A-Za-z0-9]*$/";
    private $locked = false;
    private $origin;

    public function setOrigin($originString)
    {
        $this->requireOpen();
        $this->origin = $originString;
    }

    public function getOrigin()
    {
        return $this->origin;
    }

    public function setSettings(array $settings)
    {
        $this->settings = $settings;
    }

    public function setSetting($name, $value)
    {
        $this->settings[$name] = $value;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getSetting($name, $default = null)
    {
        if(isset($this->settings[$name])){
            return $this->settings[$name];
        }else{
            return $default;
        }
    }

    public function setName($name)
    {
        $this->requireOpen();
        $legalName = preg_match($this->nameValidation,$name);
        if(!$legalName){
            throw new InvalidArgumentException(
                "The value '$name' is not a valid name for the '".get_called_class()."' definition component"
            );
        }
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function validate()
    {
        $instanceVariableNames = $this->getInstanceVariables();
        $this->executeOnVariables($instanceVariableNames, "validate");
    }

    public function lock()
    {
        $this->locked = true;
    }

    public function isLocked()
    {
        return $this->locked;
    }

    protected function requireOpen()
    {
        if($this->isLocked()){
           throw new InvalidOperationException(
               "The '".get_called_class()."' definition component is locked, and cannot be modified"
           );
        }
    }

    private function executeOnVariables($variableNames, $method)
    {
        foreach($variableNames as $variable){
            $this->$variable = $this->executeRecursive($this->$variable, $method);
        }
    }

    private function executeRecursive($potentialComponent, $method)
    {
        if($potentialComponent instanceof DefinitionComponent){
            $potentialComponent->$method();
        }
        if(is_array($potentialComponent)){
            foreach($potentialComponent as $index => $value){
                $potentialComponent[$index] = $this->executeRecursive($value, $method);
            }
        }
        return $potentialComponent;
    }

    private function getInstanceVariables()
    {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
        $result = array();
        foreach($properties as $p){
            $result[] = $p->getName();
        }
        return $result;
    }
} 