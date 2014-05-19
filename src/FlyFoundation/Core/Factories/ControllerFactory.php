<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Controllers\Controller;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Dependencies\AppDefinition;
use FlyFoundation\Factory;

class ControllerFactory
{
    use AppConfig;

    private $defaultController = "\\FlyFoundation\\Controllers\\GenericEntityController";

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, array $arguments = array())
    {
        $implementation = FactoryTools::findImplementation($className,$this->getAppConfig()->controllerSearchPaths);
        $controllerName = $this->getControllerName($className);

        if(!$controllerName){
            if($implementation){
                $className = $implementation;
            }
            return Factory::loadAndDecorateWithoutSpecialization($className, $arguments);
        }

        if($implementation){
            $controller = Factory::loadAndDecorateWithoutSpecialization($implementation, $arguments);
        }else{
            $controller = Factory::loadAndDecorateWithoutSpecialization($this->defaultController,$arguments);
        }

        if($controller instanceof Controller){
            $controller = $this->decorateController($controller, $controllerName);
        }

        return $controller;
    }

    public function exists($className)
    {
        $controllerName = $this->getControllerName($className);
        if($controllerName){
            return true;
        }

        $implementation = FactoryTools::findImplementation($className,$this->getAppConfig()->controllerSearchPaths);
        if($implementation){
            return true;
        }

        return class_exists($className);
    }

    private function decorateController(Controller $controller, $controllerName)
    {

        if(Factory::viewExists($controllerName)){
            $controller->setView(Factory::loadView($controllerName));
        }

        if(Factory::modelExists($controllerName)){
            $controller->setModel(Factory::loadModel($controllerName));
        }

        return $controller;
    }

    public function getControllerName($className)
    {
        $partialClassName = FactoryTools::findPartialClassNameInPaths($className, $this->getAppConfig()->controllerSearchPaths);
        $controllerNaming = "/^(.*)Controller$/";
        $matches = [];
        if(preg_match($controllerNaming, $partialClassName, $matches)){
            return $matches[1];
        }else{
            return false;
        }
    }
}