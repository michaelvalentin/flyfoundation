<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Controllers\Controller;
use FlyFoundation\Exceptions\InvalidClassException;
use FlyFoundation\Models\Entity;

class ControllerFactory extends AbstractFactory
{
    private $defaultController = "\\FlyFoundation\\Controllers\\GenericEntityController";

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, array $arguments = array())
    {
        $implementation = $this->findImplementation($className,$this->getConfig()->controllerSearchPaths);
        $controllerName = $this->getControllerName($className);

        if(!$controllerName){
            if($implementation){
                $className = $implementation;
            }
            return $this->getFactory()->loadWithoutOverridesAndDecoration($className, $arguments);
        }

        if($implementation){
            $controller = $this->getFactory()->loadWithoutOverridesAndDecoration($className, $arguments);
        }else{
            $controller = $this->getFactory()->load($this->defaultController,$arguments);
        }

        if(($controller instanceof Controller)){
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

        $implementation = $this->findImplementation($className,$this->getConfig()->controllerSearchPaths);
        if($implementation){
            return true;
        }

        return false;
    }

    private function decorateController(Controller $controller, $controllerName)
    {
        $factory = $this->getFactory();

        if($factory->viewExists($controllerName)){
            $view = $this->getFactory()->loadView($controllerName);
            $controller->setView($view);
        }

        if($factory->modelExists($controllerName)){
            $model = $this->getFactory()->loadModel($controllerName);
            $controller->setModel($model);
        }

        return $controller;
    }

    public function getControllerName($className)
    {
        $partialClassName = $this->findPartialClassNameInPaths($className, $this->getConfig()->controllerSearchPaths);
        $controllerNaming = "/^(.*)Controller$/";
        $matches = [];
        if(preg_match($controllerNaming, $partialClassName, $matches)){
            return $matches[1];
        }else{
            return false;
        }
    }
}