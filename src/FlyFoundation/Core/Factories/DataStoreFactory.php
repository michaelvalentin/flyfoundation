<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Database\GenericDataStore;

class DataStoreFactory extends AbstractFactory{

    public function __construct()
    {
        $this->genericClassName = "\\FlyFoundation\\Database\\GenericDataStore";
        $this->genericInterface = "\\FlyFoundation\\Database\\GenericDataStore";
        $this->genericNamingRegExp = "^(.*)DataStore";
    }

    protected function prepareGeneric($result, $entityName)
    {
        /** @var GenericDataStore $result */
        $result->setEntityName($entityName);
        return $result;
    }
} 