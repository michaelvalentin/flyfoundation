<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Factory;
use FlyFoundation\Views\GenericView;

class ViewFactory extends AbstractFactory
{

    public function __construct()
    {
        $this->genericNamingRegExp = "/^(.*)View$/";
        $this->genericClassName = "\\FlyFoundation\\Views\\GenericView";
        $this->genericInterface = "\\FlyFoundation\\Views\\GenericView";
    }

    protected function prepareGeneric($result, $entityName)
    {
        /** @var GenericView $result */
        $result->setEntityName($entityName);
        return $result;
    }
}