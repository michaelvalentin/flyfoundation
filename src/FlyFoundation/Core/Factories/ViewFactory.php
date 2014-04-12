<?php


namespace FlyFoundation\Core\Factories;


class ViewFactory extends AbstractFactory{

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, $arguments = array())
    {
        $className = $this->findImplementation($className,$this->getConfig()->viewSearchPaths);

        $viewNaming = "/^(.*)View$/";
        $matches = [];
        $hasViewNaming = preg_match($viewNaming, $className, $matches);

        if(!$hasViewNaming || class_exists($className)){
            return $this->getFactory()->loadWithoutOverridesAndDecoration($className, $arguments);
        }

        return $this->getFactory()->load("\\FlyFoundation\\Views\\DefaultView");
    }
}