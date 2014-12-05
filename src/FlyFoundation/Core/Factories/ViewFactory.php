<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Factory;

class ViewFactory{

    use AppConfig;

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, array $arguments = array())
    {
        $implementation = FactoryTools::findImplementation($className,$this->getAppConfig()->viewSearchPaths);
        $hasViewNaming = $this->hasViewNaming($className);

        if($hasViewNaming && !$implementation){
            return Factory::loadWithoutImplementationSearch("\\FlyFoundation\\Views\\GenericView");
        }

        return Factory::loadAndDecorateWithoutSpecialization($implementation, $arguments);
    }

    public function exists($className)
    {
        $implementation = FactoryTools::findImplementation($className,$this->getAppConfig()->viewSearchPaths);
        $hasViewNaming = $this->hasViewNaming($className);

        return $implementation || $hasViewNaming || class_exists($className);
    }

    private function hasViewNaming($className)
    {
        $viewNaming = "/^(.*)View$/";
        return preg_match($viewNaming, $className);
    }
}