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
        $this->genericInterface = "\\FlyFoundation\\Controllers\\Controller";
        $this->genericNamingRegExp = "/^(.*)Controller/";
    }

    protected function prepareGenericEntity($result, $entityName)
    {
        /** @var GenericEntityController $result */

        if(Factory::viewExists($entityName)){
            $result->setView(Factory::loadView($entityName));
        }

        if(Factory::modelExists($entityName)){
            $result->setModel(Factory::loadModel($entityName));
        }

        return $result;
    }

    protected function prepareGenericEntityWithDefinition($result, EntityDefinition $entityDefinition)
    {
        return $result;
    }
}