<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Exceptions\InvalidClassException;
use FlyFoundation\Factory;

class ModelFactory {

    use AppConfig;

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

        return $arguments;
    }

    private function getEntityName($className)
    {
        $modelSearchPaths = $this->getAppConfig()->modelSearchPaths;
        return FactoryTools::findPartialClassNameInPaths($className, $modelSearchPaths);
    }

}