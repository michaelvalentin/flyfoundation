<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Database\GenericDataMapper;
use FlyFoundation\Factory;
use FlyFoundation\SystemDefinitions\EntityDefinition;
use FlyFoundation\Util\NameManipulator;

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
        if(Factory::dataStoreExists($entityName)){
            $result->setDataStore(Factory::loadDataStore($entityName));
        }
        return $result;
    }

    protected function prepareGenericEntityWithDefinition($entity, EntityDefinition $entityDefinition)
    {
        /** @var GenericDataMapper $entity */
        $nameManipulator = new NameManipulator();

        foreach($entityDefinition->getFieldDefinitions() as $field){
            $entityFieldName = $field->getName();
            $defaultStorageFieldName = $nameManipulator->toUnderscored($entityFieldName);
            $storageFieldName = $field->getSetting("StorageFieldName",$defaultStorageFieldName);
            $entity->addNameMapping($entityFieldName,$storageFieldName);
        }
        return $entity;
    }
}