<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Factory;
use FlyFoundation\SystemDefinitions\EntityDefinition;
use FlyFoundation\Views\GenericView;

class ViewFactory extends AbstractFactory
{

    public function __construct()
    {
        $this->genericNamingRegExp = "/^(.*)View$/";
        $this->genericClassName = "\\FlyFoundation\\Views\\GenericView";
        $this->genericInterface = "\\FlyFoundation\\Views\\GenericView";
    }

    protected function prepareGenericEntity($result, $entityName)
    {
        /** @var GenericView $result */
        $result->setEntityName($entityName);
        return $result;
    }

    protected function prepareGenericEntityWithDefinition($result, EntityDefinition $entityDefinition)
    {
        return $result;
    }
}