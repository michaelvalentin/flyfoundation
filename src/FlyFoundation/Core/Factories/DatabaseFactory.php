<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Exceptions\UnknownClassException;

class DatabaseFactory extends AbstractFactory{

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, array $arguments = array())
    {
        $className = $this->findImplementation($className,$this->getConfig()->databaseSearchPaths);
        $partialClassName = $this->findPartialClassNameInPaths($className, $this->getConfig()->databaseSearchPaths);
        $dbPrefix = $this->getConfig()->get("database_data_object_prefix");

        $dataObjectNaming = "/^((.*)\\\\)?(".$dbPrefix.")?(.*)(DataMapper|DataFinder|DataMethods)$/";
        $matches = [];
        $hasDataObjectNaming = preg_match($dataObjectNaming, $partialClassName, $matches);

        if(!$hasDataObjectNaming){
            return $this->getFactory()->loadWithoutOverridesAndDecoration($className,$arguments);
        }

        $entityName = $matches[1].$matches[4];
        $dataObjectType = $matches[5];
        $appliedDbPrefix = $matches[3];

        if($appliedDbPrefix == ""){
            $className = $this->prefixActualClassName($className, $dbPrefix);
            return $this->getFactory()->load($className, $arguments);
        }

        if(in_array($dataObjectType,["DataMapper","DataFinder"])){
            $entityDefinition = $this->getFactory()->loadEntityDefinition($entityName);
            array_unshift($arguments,$entityDefinition);
        }

        if(class_exists($className)){
            return $this->getFactory()->loadWithoutOverridesAndDecoration($className,$arguments);
        }else{
            $dynamicClassName = $this->getDynamicDatabaseClassName($className, $dataObjectType);
            return $this->getFactory()->load($dynamicClassName, $arguments);
        }
    }

    public function exists($className)
    {
        //TODO: Implement!
    }

    public function getDynamicDatabaseClassName($className, $dataObjectType)
    {
        if(!in_array($dataObjectType,["DataMapper", "DataFinder"])){
            throw new UnknownClassException(
                $dataObjectType." objects must be implemented. Could not find object '".$className."'"
            );
        }

        $dbPrefix = $this->getConfig()->get("database_data_object_prefix");

        return "\\FlyFoundation\\Database\\".$dbPrefix.$dataObjectType;
    }
}