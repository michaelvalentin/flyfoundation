<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Dependencies\AppDefinition;
use FlyFoundation\Factory;
use FlyFoundation\Util\ValueList;

abstract class AbstractFactory {

    use AppDefinition;

    /** @var string */
    protected $genericNamingRegExp = "/.+/";
    /** @var ValueList */
    private $searchPaths;
    protected $genericClassName = "";
    protected $genericInterface = "";

    public function setSearchPaths(ValueList $searchPaths)
    {
        $this->searchPaths = $searchPaths;
    }

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, array $arguments = array())
    {
        $implementation =  FactoryTools::findImplementation($className,$this->searchPaths);
        $hasGenericNaming = preg_match($this->genericNamingRegExp, $className, $matches);
        $entityName = $this->getEntityName($className);
        $entityDefinitionExists = $this->getAppDefinition()->containsEntityDefinition($entityName);

        if($hasGenericNaming && !$implementation && $entityDefinitionExists){
            $result = Factory::load($this->genericClassName, $arguments);
        }elseif($implementation){
            $result = Factory::loadAndDecorateWithoutSpecialization($implementation, $arguments);
        }else{
            $result = Factory::loadAndDecorateWithoutSpecialization($className, $arguments);
        }

        if($result instanceof $this->genericInterface && $entityDefinitionExists)
        {
            $entityName = $this->getEntityName($className);
            $result = $this->prepareGeneric($result, $entityName);
        }

        return $result;
    }

    public function exists($className)
    {
        $implementation =  FactoryTools::findImplementation($className,$this->searchPaths);
        $hasGenericNaming = preg_match($this->genericNamingRegExp, $className, $matches);
        $entityName = $this->getEntityName($className);
        $entityDefinitionExists = $this->getAppDefinition()->containsEntityDefinition($entityName);

        if($implementation){
            return true;
        }elseif($hasGenericNaming && $entityDefinitionExists){
            return true;
        }else{
            return class_exists($className);
        }

        return $implementation || $hasGenericNaming || class_exists($className);
    }

    abstract protected function prepareGeneric($result, $entityName);

    /**
     * @param $className
     * @return bool
     */
    private function getEntityName($className)
    {
        $partialClassName = FactoryTools::findPartialClassNameInPaths(
            $className,
            $this->searchPaths
        );

        if(preg_match($this->genericNamingRegExp, $partialClassName, $matches)){
            return array_pop($matches);
        }else{
            return $partialClassName;
        }
    }

} 