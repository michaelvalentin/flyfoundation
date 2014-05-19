<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Dependencies\AppDefinition;
use FlyFoundation\Exceptions\InvalidClassException;
use FlyFoundation\Exceptions\UnknownClassException;
use FlyFoundation\Factory;
use FlyFoundation\SystemDefinitions\EntityDefinition;

class DatabaseFactory {

    use AppConfig, AppDefinition;

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, array $arguments = array())
    {
        $partialClassName = FactoryTools::findPartialClassNameInPaths($className, $this->getAppConfig()->databaseSearchPaths);
        $dbPrefix = $this->getAppConfig()->get("database_data_object_prefix");

        $dataObjectNaming = "/^((.*)\\\\)?(".$dbPrefix.")?(.*)(DataMapper|DataFinder|DataMethods)$/";
        $matches = [];
        $hasDataObjectNaming = preg_match($dataObjectNaming, $partialClassName, $matches);

        if(!$hasDataObjectNaming){
            $implementation = FactoryTools::findImplementation($className,$this->getAppConfig()->databaseSearchPaths);
            return Factory::loadAndDecorateWithoutSpecialization($implementation,$arguments);
        }

        $entityName = $matches[1].$matches[4];
        $dataObjectType = $matches[5];
        $appliedDbPrefix = $matches[3];

        if($appliedDbPrefix == ""){
            $className = FactoryTools::prefixActualClassName($className, $dbPrefix);
        }

        $implementation = FactoryTools::findImplementation($className,$this->getAppConfig()->databaseSearchPaths);

        if($implementation){
            $arguments = $this->prepareArguments($implementation, $arguments, $entityName);
            return Factory::loadAndDecorateWithoutSpecialization($implementation,$arguments);
        }elseif($this->getAppDefinition()->hasEntity($entityName)){
            $dynamicClassName = $this->getGenericDatabaseClassName($className, $dataObjectType);
            $arguments = $this->prepareArguments($dynamicClassName, $arguments, $entityName);
            return Factory::load($dynamicClassName, $arguments);
        }else{
            throw new UnknownClassException("The class '".$className."' could not be found neither as concrete implementation or generic implementation through definitions.");
        }
    }

    public function exists($className)
    {
        $partialClassName = FactoryTools::findPartialClassNameInPaths($className, $this->getAppConfig()->databaseSearchPaths);
        $dbPrefix = $this->getAppConfig()->get("database_data_object_prefix");

        $dataObjectNaming = "/^((.*)\\\\)?(".$dbPrefix.")?(.*)(DataMapper|DataFinder|DataMethods)$/";
        $matches = [];
        $hasDataObjectNaming = preg_match($dataObjectNaming, $partialClassName, $matches);

        if(!$hasDataObjectNaming){
            $implementation = FactoryTools::findImplementation($className,$this->getAppConfig()->databaseSearchPaths);
            return $implementation || class_exists($className);
        }

        $entityName = $matches[1].$matches[4];
        $dataObjectType = $matches[5];
        $appliedDbPrefix = $matches[3];

        if($appliedDbPrefix == ""){
            $className = FactoryTools::prefixActualClassName($className, $dbPrefix);
        }

        $implementation = FactoryTools::findImplementation($className,$this->getAppConfig()->databaseSearchPaths);

        if($implementation || $this->getAppDefinition()->hasEntity($entityName)){
            return true;
        }

        return false;
    }

    public function getGenericDatabaseClassName($className, $dataObjectType)
    {
        if(!in_array($dataObjectType,["DataMapper", "DataFinder"])){
            throw new UnknownClassException(
                $dataObjectType." objects must be implemented. Could not find object '".$className."'"
            );
        }

        $dbPrefix = $this->getAppConfig()->get("database_data_object_prefix");

        return "\\FlyFoundation\\Database\\".$dbPrefix."Generic".$dataObjectType;
    }

    private function prepareArguments($className, $arguments, $entityName)
    {
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
                throw new InvalidClassException("No entity definition '".$entityName."' exists, and hence the class '".$className."' can not be loaded.");
            }
            $entityDefinition = $this->getAppDefinition()->getEntity($entityName);
            array_unshift($arguments,$entityDefinition);
        }

        return $arguments;
    }
}