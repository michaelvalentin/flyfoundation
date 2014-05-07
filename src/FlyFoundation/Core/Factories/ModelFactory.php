<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\SystemDefinitions\EntityDefinition;

class ModelFactory extends AbstractFactory{

    private $defaultModel = "\\FlyFoundation\\Models\\OpenPersistentEntity";

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, array $arguments = array())
    {
        $implementation = $this->findImplementation($className,$this->getConfig()->modelSearchPaths);

        if($implementation){
            $arguments = $this->prepareArguments($implementation,$arguments);
            $model = $this->getFactory()->loadWithoutOverridesAndDecoration($implementation, $arguments);
        }else{
            $arguments = $this->prepareArguments($className, $arguments);
            $model = $this->getFactory()->load($this->defaultModel,$arguments);
        }

        return $model;
    }

    public function exists($className)
    {
        $implementation = $this->findImplementation($className,$this->getConfig()->modelSearchPaths);
        if($implementation){
            return true;
        }

        $entityName = $this->getEntityName($className);
        if($this->getAppDefinition()->hasEntity($entityName)){
            return true;
        }

        return false;
    }

    private function prepareArguments($className, $arguments)
    {

        $reflectionClass = new \ReflectionClass($className);
        $constructor = $reflectionClass->getConstructor();
        $constructorParameters = $constructor->getParameters();

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
            $entityName = $this->getEntityName($className);
            $entityDefinition = $this->getAppDefinition()->getEntity($entityName);
            array_unshift($arguments,$entityDefinition);
        }

        return $arguments;
    }

    private function getEntityName($className)
    {
        $modelSearchPaths = $this->getConfig()->modelSearchPaths;
        return $this->findPartialClassNameInPaths($className, $modelSearchPaths);
    }

}