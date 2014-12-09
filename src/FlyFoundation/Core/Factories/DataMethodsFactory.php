<?php


namespace FlyFoundation\Core\Factories;

class DataMethodsFactory extends StorageAwareFactory{

    public function __construct()
    {
        $this->genericClassName = "\\FlyFoundation\\Database\\DataMethodsMustBeImplementedNoGenerics";
        $this->genericInterface = "\\FlyFoundation\\Database\\DataMethodsMustBeImplementedNoGenerics";
        $this->genericNamingRegExp = "/^(.*)DataMethods$/";
    }

    protected function prepareGeneric($result, $entityName)
    {
    }
} 