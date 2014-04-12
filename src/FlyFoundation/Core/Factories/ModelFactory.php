<?php


namespace FlyFoundation\Core\Factories;


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

        $arguments = $this->prepareArguments($className,$arguments);

        if(class_exists($className)){
            $model = $this->getFactory()->loadWithoutOverridesAndDecoration($className, $arguments);
        }else{
            $model = $this->getFactory()->load($this->defaultModel,$arguments);
        }

        return $model;
    }

    private function prepareArguments($className, $arguments)
    {
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

        if($constructorFirstParameterClass == "FlyFoundation\\SystemDefinitions\\EntityDefinition"){
            $partialClassName = $this->findPartialClassNameInPaths($className, $this->getConfig()->modelSearchPaths);
            $entityDefinition = $this->getFactory()->loadEntityDefinition($partialClassName);
            array_unshift($arguments,$entityDefinition);
        }

        return $arguments;
    }
}