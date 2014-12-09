<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Database\GenericDataStore;
use FlyFoundation\Dependencies\AppDefinition;
use FlyFoundation\SystemDefinitions\EntityDefinition;

class DataStoreFactory extends StorageAwareFactory{

    use AppDefinition;

    public function __construct()
    {
        $this->genericClassName = "\\FlyFoundation\\Database\\GenericDataStore";
        $this->genericInterface = "\\FlyFoundation\\Database\\GenericDataStore";
        $this->genericNamingRegExp = "/^(.*)DataStore$/";
    }

    protected function prepareGenericEntity($result, $entityName)
    {
        /** @var GenericDataStore $result */
        $result->setEntityName($entityName);
        return $result;
    }

    protected function prepareGenericEntityWithDefinition($result, EntityDefinition $entityDefinition)
    {
        $storageName = $entityDefinition->getSetting("StorageName");
        $result->setStorageName($storageName);
        return $result;
    }
}