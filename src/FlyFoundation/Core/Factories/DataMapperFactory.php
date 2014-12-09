<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Database\GenericDataMapper;

class DataMapperFactory extends AbstractFactory{

    public function __construct()
    {
        $this->genericClassName = "\\FlyFoundation\\Database\\GenericDataMapper";
        $this->genericInterface = "\\FlyFoundation\\Database\\GenericDataMapper";
        $this->genericNamingRegExp = "/^(.*)DataMapper$/";
    }

    protected function prepareGeneric($result, $entityName)
    {
        /** @var GenericDataMapper $result */
        $result->setEntityName($entityName);
        return $result;
    }
}