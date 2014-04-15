<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Controllers\Controller;

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
            $entityDeclarationFile = $this->findEntityDeclarationFile($matches[1]);
            array_unshift($arguments,$entityDeclarationFile);
            return $this->getFactory()->load("\\FlyFoundation\\SystemDefinitions\\DynamicEntityDefinition",$arguments);
        }
    }

    private function findEntityDeclarationFile($entityName)
    {
        foreach($this->getConfig()->entityDefinitionDirectories->asArray() as $directory){
            $filename = $directory."/".$entityName.".lsd";
            if(file_exists($filename)){
                return $filename;
            }
        }
        return "NO_LSD_FILE_FOUND_BY_ENTITY_DEFINITION_FACTORY";
    }

}