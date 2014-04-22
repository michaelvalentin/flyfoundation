<?php


namespace FlyFoundation\SystemDefinitions;


use Aws\Common\Exception\InvalidArgumentException;
use FlyFoundation\Core\Environment;
use FlyFoundation\Exceptions\InvalidOperationException;

abstract class DefinitionComponent {

    use Environment;

    private $finalized;

    public function applyOptions(array $options)
    {
        if($this->isFinalized()){
            throw new InvalidOperationException("The definition is all ready finalized, and can not be changed");
        }

        foreach($options as $optionName => $value)
        {
            $this->applyOption($optionName, $value);
        }
    }

    public function finalize()
    {
        $instanceVariableNames = array_keys(get_object_vars($this));
        $this->finalizeVariables($instanceVariableNames);
        $this->finalized = true;
    }

    public function isFinalized()
    {
        return $this->finalized;
    }

    public function requireFinalized()
    {
        if(!$this->finalized){
            throw new InvalidOperationException("The definition must be finalized before usage to
            provide the best possible validation and quality of the definition");
        }
    }

    private function finalizeVariables($variableNames)
    {
        foreach($variableNames as $variable){
            $this->$variable = $this->finalizeRecursive($this->$variable);
        }
    }

    private function finalizeRecursive($potentialComponent)
    {
        if($potentialComponent instanceof DefinitionComponent){
            $potentialComponent->finalize();
        }
        if(is_array($potentialComponent)){
            foreach($potentialComponent as $index => $value){
                $potentialComponent[$index] = $this->finalizeRecursive($value);
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


}