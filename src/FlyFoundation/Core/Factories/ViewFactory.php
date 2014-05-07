<?php


namespace FlyFoundation\Core\Factories;


class ViewFactory extends AbstractFactory{

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, array $arguments = array())
    {
        $implementation = $this->findImplementation($className,$this->getConfig()->viewSearchPaths);
        $hasViewNaming = $this->hasViewNaming($className);

        if($hasViewNaming && !$implementation){
            return $this->getFactory()->load("\\FlyFoundation\\Views\\DefaultView");
        }

        return $this->getFactory()->loadWithoutOverridesAndDecoration($className, $arguments);
    }

    public function exists($className)
    {
        $implementation = $this->findImplementation($className,$this->getConfig()->viewSearchPaths);
        $hasViewNaming = $this->hasViewNaming($className);

        return $implementation || $hasViewNaming;
    }

    private function hasViewNaming($className)
    {
        $viewNaming = "/^(.*)View$/";
        return preg_match($viewNaming, $className);
    }
}