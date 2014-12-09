<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Database\GenericDataFinder;
use FlyFoundation\SystemDefinitions\EntityDefinition;

class DataFinderFactory extends AbstractFactory{

    public function __construct()
    {
        $this->genericClassName = "\\FlyFoundation\\Database\\GenericDataFinder";
        $this->genericInterface = "\\FlyFoundation\\Database\\GenericDataFinder";
        $this->genericNamingRegExp = "/^(.*)DataFinder$/";
    }

    protected function prepareGenericEntity($result, $entityName)
    {
        /** @var GenericDataFinder $result */
        $result->setEntityName($entityName);
        return $result;
    }

    protected function prepareGenericEntityWithDefinition($entity, EntityDefinition $entityDefinition)
    {
        return $entity;
    }
}