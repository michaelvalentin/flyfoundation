<?php


namespace FlyFoundation\Core\Factories;


class ModelFactory extends AbstractFactory{

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, $arguments = array())
    {
        $className = $this->findImplementation($className,$this->getConfig()->modelSearchPaths);
        $partialClassName = $this->findPartialClassNameInPaths($className, $this->getConfig()->modelSearchPaths);

        if(class_exists($className)){
            return $this->getFactory()->loadWithoutOverridesAndDecoration($className, $arguments);
        }else{
            $entityDefinition = $this->getFactory()->loadEntityDefinition($partialClassName);
            array_unshift($arguments,$entityDefinition);
            return $this->getFactory()->load("\\FlyFoundation\\Models\\OpenPersistentEntity",$arguments);
        }
    }
}