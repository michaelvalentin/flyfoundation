<?php

namespace FlyFoundation\Core\Factories;

use FlyFoundation\Controllers\Controller;
use FlyFoundation\Controllers\GenericEntityController;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Factory;
use FlyFoundation\SystemDefinitions\EntityDefinition;

class ControllerFactory extends AbstractFactory
{

    public function __construct()
    {
        $this->genericClassName = "\\FlyFoundation\\Controllers\\GenericEntityController";
        $this->genericInterface = "\\FlyFoundation\\Controllers\\GenericEntityController";
        $this->genericNamingRegExp = "/^(.*)Controller/";
    }

    protected function prepareGenericEntity($result, $entityName)
    {
        /** @var GenericEntityController $result */
        $result->setEntityName($entityName);

        return $result;
    }

    protected function prepareGenericEntityWithDefinition($entity, EntityDefinition $entityDefinition)
    {
        return $entity;
    }
}