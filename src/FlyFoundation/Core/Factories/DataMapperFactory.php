<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Database\GenericDataMapper;
use FlyFoundation\SystemDefinitions\EntityDefinition;

class DataMapperFactory extends AbstractFactory{

    public function __construct()
    {
        $this->genericClassName = "\\FlyFoundation\\Database\\GenericDataMapper";
        $this->genericInterface = "\\FlyFoundation\\Database\\GenericDataMapper";
        $this->genericNamingRegExp = "/^(.*)DataMapper$/";
    }

    protected function prepareGenericEntity($result, $entityName)
    {
        /** @var GenericDataMapper $result */
        $result->setEntityName($entityName);
        return $result;
    }

    protected function prepareGenericEntityWithDefinition($result, EntityDefinition $entityDefinition)
    {
        return $result;
    }
}