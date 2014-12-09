<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Exceptions\InvalidClassException;
use FlyFoundation\Factory;
use FlyFoundation\Models\GenericEntity;
use FlyFoundation\SystemDefinitions\EntityDefinition;

class ModelFactory extends AbstractFactory
{

    public function __construct()
    {
        $this->genericNamingRegExp = "/^(.*)$/";
        $this->genericClassName = "\\FlyFoundation\\Models\\OpenGenericEntity";
        $this->genericInterface = "\\FlyFoundation\\Models\\GenericEntity";
    }

    protected function prepareGenericEntity($result, $entityName)
    {
        /** @var GenericEntity $result */
        //TODO: Prepare properly!
        $result->setName($entityName);
        return $result;
    }

    protected function prepareGenericEntityWithDefinition($result, EntityDefinition $entityDefinition)
    {
        return $result;
    }
}