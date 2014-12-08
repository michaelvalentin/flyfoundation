<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Database\GenericDataFinder;

class DataFinderFactory extends AbstractFactory{

    public function __construct()
    {
        $this->genericClassName = "\\FlyFoundation\\Database\\GenericDataFinder";
        $this->genericInterface = "\\FlyFoundation\\Database\\GenericDataFinder";
        $this->genericNamingRegExp = "^(.*)DataFinder";
    }

    protected function prepareGeneric($result, $entityName)
    {
        /** @var GenericDataFinder $result */
        $result->setEntityName($entityName);
        return $result;
    }
} 