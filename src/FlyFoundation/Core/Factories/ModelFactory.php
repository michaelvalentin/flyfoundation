<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Dependencies\AppDefinition;
use FlyFoundation\Exceptions\InvalidClassException;
use FlyFoundation\Factory;
use FlyFoundation\SystemDefinitions\EntityDefinition;

class ModelFactory {

    use AppConfig, AppDefinition;

    private $defaultModel = "\\FlyFoundation\\Models\\OpenPersistentEntity";

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, array $arguments = array())
    {
        $implementation = FactoryTools::findImplementation($className,$this->getAppConfig()->modelSearchPaths);

        if($implementation){
            $arguments = $this->prepareArguments($implementation,$arguments);
            $model = Factory::loadAndDecorateWithoutSpecialization($implementation, $arguments);
        }else{
            $entityName = $this->getEntityName($className);
            $arguments = $this->prepareArguments($this->defaultModel, $arguments, $entityName);
            $model = Factory::load($this->defaultModel,$arguments);
        }

        return $model;
    }

    public function exists($className)
    {
        $implementation = FactoryTools::findImplementation($className,$this->getAppConfig()->modelSearchPaths);
        if($implementation){
            return true;
        }

        $entityName = $this->getEntityName($className);
        if($this->getAppDefinition()->hasEntity($entityName)){
            return true;
        }

        return false;
    }

    private function prepareArguments($className, $arguments, $entityName = false)
    {
        if(!$entityName){
            $entityName = $this->getEntityName($className);
        }


        $reflectionClass = new \ReflectionClass($className);
        $constructor = $reflectionClass->getConstructor();
        if($constructor){
            $constructorParameters = $constructor->getParameters();
        }else{
            $constructorParameters = [];
        }

        if(count($constructorParameters) < 1){
            return $arguments;
        }

        $constructorFirstParameterClass = $constructorParameters[0]->getClass()->getName();
        $takesEntityDefinitionAsFirstParameter = $constructorFirstParameterClass == "FlyFoundation\\SystemDefinitions\\EntityDefinition";

        $firstParameterIsEntityDefinition = false;
        if(isset($arguments[0])){
            $firstParameterIsEntityDefinition = $arguments[0] instanceof EntityDefinition;
        }

        if($takesEntityDefinitionAsFirstParameter && !$firstParameterIsEntityDefinition){
            if(!$this->getAppDefinition()->hasEntity($entityName)){
                throw new InvalidClassException("No entity definition '".$entityName."' exists, and the class '".$className."' can not be loaded.");
            }
            $entityDefinition = $this->getAppDefinition()->getEntity($entityName);
            array_unshift($arguments,$entityDefinition);
        }

        return $arguments;
    }

    private function getEntityName($className)
    {
        $modelSearchPaths = $this->getAppConfig()->modelSearchPaths;
        return FactoryTools::findPartialClassNameInPaths($className, $modelSearchPaths);
    }

}