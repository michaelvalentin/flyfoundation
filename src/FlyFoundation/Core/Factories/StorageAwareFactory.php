<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Factory;

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
            $result = Factory::load($this->genericClassName, $arguments);
        }elseif($implementation){
            $result = Factory::loadAndDecorateWithoutSpecialization($implementation, $arguments);
        }else{
            $result = Factory::loadAndDecorateWithoutSpecialization($prefixedClassName, $arguments);
        }

        if($result instanceof $this->genericInterface)
        {
            $entityName = $this->getEntityName($className);
            $result = $this->prepareGeneric($result, $entityName);
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