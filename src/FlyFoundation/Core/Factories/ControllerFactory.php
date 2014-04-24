<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Controllers\Controller;

class ControllerFactory extends AbstractFactory
{
    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, $arguments = array())
    {
        $className = $this->findImplementation($className,$this->getConfig()->controllerSearchPaths);
        $partialClassName = $this->findPartialClassNameInPaths($className, $this->getConfig()->controllerSearchPaths);

        $controllerNaming = "/^(.*)Controller$/";
        $matches = [];
        $hasControllerNaming = preg_match($controllerNaming, $partialClassName, $matches);

        if(!$hasControllerNaming){
            return $this->getFactory()->loadWithoutOverridesAndDecoration($className, $arguments);
        }

        if(class_exists($className)){
            $controller = $this->getFactory()->loadWithoutOverridesAndDecoration($className, $arguments);
        }else{
            $controller = $this->getFactory()->load("\\FlyFoundation\\Controllers\\GenericEntityController",$arguments);
        }

        if(($controller instanceof Controller)){
            $controller = $this->decorateController($controller, $matches[1]);
        }

        return $controller;
    }

    private function decorateController(Controller $controller, $controllerName)
    {
        $view = $this->getFactory()->loadView($controllerName);
        $controller->setView($view);

        $model = $this->getFactory()->loadModel($controllerName);
        $controller->setModel($model);

        return $controller;
    }

}