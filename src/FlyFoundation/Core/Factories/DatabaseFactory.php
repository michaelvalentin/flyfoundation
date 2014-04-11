<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Exceptions\UnknownClassException;

class DatabaseFactory extends AbstractFactory{

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, $arguments = array())
    {
        $dataObjectNaming = "/^(.*)(DataMapper|DataFinder|DataMethods)$/";
        $matches = [];
        $hasDataObjectNaming = preg_match($dataObjectNaming, $className, $matches);
        if(!$hasDataObjectNaming){
            return $this->getFactory()->loadWithoutOverridesAndDecoration($className,$arguments);
        }
        $dataObjectType = $matches[2];

        $dbPrefix = $this->getConfig()->get("database_data_object_prefix");
        $className = $this->prefixActualClassName($className, $dbPrefix);

        $className = $this->findImplementation($className,$this->getConfig()->databaseSearchPaths);

        $modelName = "";
        $entityDefinition = $this->getFactory()->load("\\FlyFoundation\\EntityDefinitions\\".$modelName."Definition");

        if(class_exists($className)){
            return $this->getFactory()->loadWithoutOverridesAndDecoration($className,[$entityDefinition]);
        }else{
            $dynamicClassName = $this->getDynamicClassName($className, $dataObjectType);
            return $this->getFactory()->load($dynamicClassName, [$entityDefinition]);
        }
    }

    public function prefixActualClassName($className, $databasePrefix)
    {
        $classNameParts = explode("\\",$className);
        $lastClassNamePart = array_pop($classNameParts);
        $databasePrefixedLastClassNamePart = $databasePrefix.$lastClassNamePart;
        array_push($classNameParts,$databasePrefixedLastClassNamePart);
        $className = implode("\\",$classNameParts);
        return $className;
    }

    public function getDynamicClassName($className, $dataObjectType)
    {
        if($dataObjectType == "DataMethods"){
            throw new UnknownClassException(
                "DataMethod objects must be implemented. Could not find object '".$className."'"
            );
        }

        $dbPrefix = $this->getConfig()->get("database_data_object_prefix");

        $dynamicClassName = "\\FlyFoundation\\Database\\".$dbPrefix.$dataObjectType;
        $dynamicClassName = $this->findImplementation($dynamicClassName,$this->getConfig()->databaseSearchPaths);

        return $dynamicClassName;
    }

}