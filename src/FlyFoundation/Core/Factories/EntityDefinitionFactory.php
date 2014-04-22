<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Controllers\Controller;
use FlyFoundation\Core\FileLoader;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidOperationException;

class EntityDefinitionFactory extends AbstractFactory
{
    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, $arguments = array())
    {
        $className = $this->findImplementation($className,$this->getConfig()->entityDefinitionSearchPaths);
        $partialClassName = $this->findPartialClassNameInPaths($className, $this->getConfig()->entityDefinitionSearchPaths);

        $entityDefinitionNaming = "/^(.*)Definition$/";
        $matches = [];
        $hasEntityDefinitionNaming = preg_match($entityDefinitionNaming, $partialClassName, $matches);

        if(!$hasEntityDefinitionNaming){
            return $this->getFactory()->loadWithoutOverridesAndDecoration($className, $arguments);
        }

        if(class_exists($className)){
            return $this->getFactory()->loadWithoutOverridesAndDecoration($className, $arguments);
        }else{
            /** @var FileLoader $fileLoader */
            $fileLoader = $this->getFactory()->load("\\FlyFoundation\\Core\\FileLoader");
            $entityDeclarationFile = $fileLoader->findEntityDefinition($matches[1]);
            array_unshift($arguments,$entityDeclarationFile);
            return $this->getFactory()->load("\\FlyFoundation\\SystemDefinitions\\DynamicEntityDefinition",$arguments);
        }
    }

}