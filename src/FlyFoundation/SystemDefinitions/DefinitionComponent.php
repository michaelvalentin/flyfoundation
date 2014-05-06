<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Core\Environment;
use FlyFoundation\Exceptions\InvalidOperationException;

abstract class DefinitionComponent {

    use Environment;

    private $finalized;
    private $settings;

    public function applyOptions(array $options)
    {
        if($this->isFinalized()){
            throw new InvalidOperationException("The definition is allready finalized, and can not be changed");
        }

        foreach($options as $optionName => $value)
        {
            $this->applyOption($optionName, $value);
        }
    }

    public function applySettings(array $settings)
    {
        $this->settings = $settings;
    }

    public function getSetting($name, $default = null)
    {
        $this->requireFinalized();
        if(!is_array($this->settings)){
            return $default;
        }
        if(!isset($this->settings[$name])){
            return $default;
        }
        return $this->settings[$name];
    }

    public function finalize()
    {
        $this->finalized = true;

        $instanceVariableNames = $this->getInstanceVariables();
        $this->executeOnVariables($instanceVariableNames, "finalize");

        $this->validate(); //Validation is after finalization to allow access to parent and children
    }

    public function validate()
    {
        $instanceVariableNames = $this->getInstanceVariables();
        $this->executeOnVariables($instanceVariableNames, "validate");
    }

    public function isFinalized()
    {
        return $this->finalized == true;
    }

    public function requireFinalized()
    {
        if(!$this->finalized){
            throw new InvalidOperationException("The definition must be finalized before usage to
            provide the best possible validation and quality of the definition");
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

    private function applyOption($optionName, $value)
    {
        $methodName = "apply".ucfirst($optionName);
        if(method_exists($this,$methodName)){
            $this->$methodName($value);
        }else{
            throw new InvalidArgumentException("The option '".$optionName."' could not be found in the definition.");
        }
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