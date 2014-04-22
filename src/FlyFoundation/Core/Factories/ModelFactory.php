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
    public function load($className, $arguments = array())
    {
        $className = $this->findImplementation($className,$this->getConfig()->modelSearchPaths);


        if(class_exists($className)){
            $arguments = $this->prepareArguments($className,$arguments);
            $model = $this->getFactory()->loadWithoutOverridesAndDecoration($className, $arguments);
        }else{
            $arguments = $this->prepareArguments($className,$arguments);
            $model = $this->getFactory()->load($this->defaultModel,$arguments);
        }

        return $model;
    }

    private function prepareArguments($className, $arguments)
    {
        $requestedClassName = $className;

        if(!class_exists($className)){
            $className = $this->defaultModel;
        }

        $reflectionClass = new \ReflectionClass($className);
        $constructor = $reflectionClass->getConstructor();
        $constructorParameters = $constructor->getParameters();

        if(count($constructorParameters) < 1){
            return $arguments;
        }

        $constructorFirstParameterClass = $constructorParameters[0]->getClass()->getName();
        $takesEntityDefinitionAsFirstParameter = $constructorFirstParameterClass == "FlyFoundation\\SystemDefinitions\\EntityDefinition";

        $firstArgumentIsEntityDefinition = false;
        if(isset($arguments[0])){
            $firstArgumentIsEntityDefinition = $arguments[0] instanceof EntityDefinition;
        }

        if($takesEntityDefinitionAsFirstParameter && !$firstArgumentIsEntityDefinition){
            $requestedPartialClassName = $this->findPartialClassNameInPaths($requestedClassName, $this->getConfig()->modelSearchPaths);
            $entityDefinition = $this->getFactory()->loadEntityDefinition($requestedPartialClassName);
            array_unshift($arguments,$entityDefinition);
        }

        return $arguments;
    }
}