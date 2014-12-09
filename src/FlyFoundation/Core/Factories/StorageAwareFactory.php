<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Factory;

//TODO: Consider if this can be merged better into AbstractFactory, there is a great deal of duplication...

abstract class StorageAwareFactory extends AbstractFactory{

    use AppConfig;

    protected $prefixedGenericClass = "";
    protected $prefixedGenericInterface = "";
    protected $prefixedGenericNamingRegExp = "";

    public function load($className, array $arguments = array())
    {
        $entityName = $this->getEntityName($className);
        $entityDefinitionExists = $this->getAppDefinition()->containsEntityDefinition($entityName);
        $prefix = $this->getStorageTypePrefix($entityName);
        $prefixedClassName = $this->prefixLastClassPart($className, $prefix);
        $implementation =  FactoryTools::findImplementation($prefixedClassName,$this->searchPaths);
        $hasGenericNaming = preg_match($this->genericNamingRegExp, $className, $matches);

        if($hasGenericNaming && !$implementation && $entityDefinitionExists){
            $prefixedGenericClassName = $this->prefixLastClassPart($this->genericClassName, $prefix);
            $result = Factory::loadAndDecorateWithoutSpecialization($prefixedGenericClassName, $arguments);
        }elseif($implementation){
            $result = Factory::loadAndDecorateWithoutSpecialization($implementation, $arguments);
        }else{
            $result = Factory::loadAndDecorateWithoutSpecialization($prefixedClassName, $arguments);
        }

        if($result instanceof $this->genericInterface)
        {
            $entityName = $this->getEntityName($className);
            $result = $this->prepareGenericEntity($result, $entityName);
            if($this->getAppDefinition()->containsEntityDefinition($entityName)){
                $entityDefinition = $this->getAppDefinition()->getEntityDefinition($entityName);
                $result = $this->prepareGenericEntityWithDefinition($result, $entityDefinition);
            }
        }

        return $result;
    }

    public function exists($className)
    {
        $entityName = $this->getEntityName($className);
        $entityDefinitionExists = $this->getAppDefinition()->containsEntityDefinition($entityName);
        $prefix = $this->getStorageTypePrefix($entityName);
        $prefixedClassName = $this->prefixLastClassPart($className, $prefix);
        $implementation =  FactoryTools::findImplementation($prefixedClassName,$this->searchPaths);
        $hasGenericNaming = preg_match($this->genericNamingRegExp, $className, $matches);

        if($implementation){
            return true;
        }elseif($hasGenericNaming && $entityDefinitionExists){
            return true;
        }else{
            return class_exists($prefixedClassName);
        }
    }

    protected function getStorageTypePrefix($entityName)
    {
        $default = $this->getAppConfig()->get("DefaultStorageType","MySql");
        if($this->getAppDefinition()->containsEntityDefinition($entityName)){
            $entityDef = $this->getAppDefinition()->getEntityDefinition($entityName);
            return $entityDef->getSetting("StorageType",$default);
        }
        return $default;
    }

    private function prefixLastClassPart($className, $prefix)
    {
        $parts = explode("\\",$className);
        $lastPart = count($parts) - 1;
        $parts[$lastPart] = $prefix.$parts[$lastPart];
        return implode("\\",$parts);
    }
} 