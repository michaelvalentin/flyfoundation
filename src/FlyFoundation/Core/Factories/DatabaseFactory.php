<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Exceptions\InvalidClassException;
use FlyFoundation\Exceptions\UnknownClassException;
use FlyFoundation\SystemDefinitions\EntityDefinition;

class DatabaseFactory extends AbstractFactory{

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, array $arguments = array())
    {
        $partialClassName = $this->findPartialClassNameInPaths($className, $this->getConfig()->databaseSearchPaths);
        $dbPrefix = $this->getConfig()->get("database_data_object_prefix");

        $dataObjectNaming = "/^((.*)\\\\)?(".$dbPrefix.")?(.*)(DataMapper|DataFinder|DataMethods)$/";
        $matches = [];
        $hasDataObjectNaming = preg_match($dataObjectNaming, $partialClassName, $matches);

        if(!$hasDataObjectNaming){
            $implementation = $this->findImplementation($className,$this->getConfig()->databaseSearchPaths);
            return $this->getFactory()->loadWithoutOverridesAndDecoration($implementation,$arguments);
        }

        $entityName = $matches[1].$matches[4];
        $dataObjectType = $matches[5];
        $appliedDbPrefix = $matches[3];

        if($appliedDbPrefix == ""){
            $className = $this->prefixActualClassName($className, $dbPrefix);
            return $this->getFactory()->load($className, $arguments);
        }

        $implementation = $this->findImplementation($className,$this->getConfig()->databaseSearchPaths);

        if($implementation){
            $arguments = $this->prepareArguments($implementation, $arguments, $entityName);
            return $this->getFactory()->loadWithoutOverridesAndDecoration($className,$arguments);
        }elseif($this->getAppDefinition()->hasEntity($entityName)){
            $dynamicClassName = $this->getGenericDatabaseClassName($className, $dataObjectType);
            $arguments = $this->prepareArguments($dynamicClassName, $arguments, $entityName);
            return $this->getFactory()->load($dynamicClassName, $arguments);
        }else{
            throw new UnknownClassException("The class '".$className."' could not be found neither as concrete implementation or generic implementation through definitions.");
        }
    }

    public function exists($className)
    {
        $partialClassName = $this->findPartialClassNameInPaths($className, $this->getConfig()->databaseSearchPaths);
        $dbPrefix = $this->getConfig()->get("database_data_object_prefix");

        $dataObjectNaming = "/^((.*)\\\\)?(".$dbPrefix.")?(.*)(DataMapper|DataFinder|DataMethods)$/";
        $matches = [];
        $hasDataObjectNaming = preg_match($dataObjectNaming, $partialClassName, $matches);

        if(!$hasDataObjectNaming){
            return class_exists($className);
        }

        $entityName = $matches[1].$matches[4];
        $dataObjectType = $matches[5];
        $appliedDbPrefix = $matches[3];

        if($appliedDbPrefix == ""){
            $className = $this->prefixActualClassName($className, $dbPrefix);
            return $this->getFactory()->exists($className);
        }

        $implementation = $this->findImplementation($className,$this->getConfig()->databaseSearchPaths);

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

        $dbPrefix = $this->getConfig()->get("database_data_object_prefix");

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