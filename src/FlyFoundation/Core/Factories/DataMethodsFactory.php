<?php


namespace FlyFoundation\Core\Factories;

use FlyFoundation\SystemDefinitions\EntityDefinition;

class DataMethodsFactory extends StorageAwareFactory{

    public function __construct()
    {
        $this->genericClassName = "\\FlyFoundation\\Database\\DataMethodsMustBeImplementedNoGenerics";
        $this->genericInterface = "\\FlyFoundation\\Database\\DataMethodsMustBeImplementedNoGenerics";
        $this->genericNamingRegExp = "/^(.*)DataMethods$/";
    }

    protected function prepareGenericEntity($result, $entityName)
    {
    }

    protected function prepareGenericEntityWithDefinition($result, EntityDefinition $entityDefinition)
    {
        return $result;
    }
}