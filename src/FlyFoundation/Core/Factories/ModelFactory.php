<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Exceptions\InvalidClassException;
use FlyFoundation\Factory;
use FlyFoundation\Models\GenericEntity;

class ModelFactory extends AbstractFactory
{

    public function __construct()
    {
        $this->genericNamingRegExp = "/^(.*)$/";
        $this->genericClassName = "\\FlyFoundation\\Models\\OpenGenericEntity";
        $this->genericInterface = "\\FlyFoundation\\Models\\GenericEntity";
    }

    protected function prepareGeneric($result, $entityName)
    {
        /** @var GenericEntity $result */
        //TODO: Prepare properly!
        $result->setName($entityName);
        return $result;
    }

}