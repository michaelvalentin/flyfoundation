<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Dependencies\AppDefinition;
use FlyFoundation\Factory;
use FlyFoundation\SystemDefinitions\EntityDefinition;
use FlyFoundation\Util\ValueList;

abstract class AbstractFactory {

    use AppDefinition;

    /** @var string */
    protected $genericNamingRegExp = "/.+/";
    /** @var ValueList */
    protected $searchPaths;
    protected $genericClassName = "";
    protected $genericInterface = "";
    protected $genericNamingMatches = [];

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
        $this->genericNamingMatches = $matches;
        $entityName = $this->getEntityName($className);
        $entityDefinitionExists = $this->getAppDefinition()->containsEntityDefinition($entityName);

        if($hasGenericNaming && !$implementation && $entityDefinitionExists){
            $result = Factory::loadAndDecorateWithoutSpecialization($this->genericClassName, $arguments);
        }elseif($implementation){
            $result = Factory::loadAndDecorateWithoutSpecialization($implementation, $arguments);
        }else{
            $result = Factory::loadAndDecorateWithoutSpecialization($className, $arguments);
        }

        if($result instanceof $this->genericInterface)
        {
            $entityName = $this->getEntityName($className);
            $result = $this->prepareGenericResult($result, $entityName);
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
    }

    private function prepareGenericResult($result, $entityName)
    {
        $result = $this->prepareGenericEntity($result, $entityName);
        if($this->getAppDefinition()->containsEntityDefinition($entityName)){
            $entityDefinition = $this->getAppDefinition()->getEntityDefinition($entityName);
            $result = $this->prepareGenericEntityWithDefinition($result, $entityDefinition);
        }
        return $result;
    }

    abstract protected function prepareGenericEntity($result, $entityName);

    abstract protected function prepareGenericEntityWithDefinition($entity, EntityDefinition $entityDefinition);

    /**
     * @param $className
     * @return bool
     */
    protected function getEntityName($className)
    {
        $partialClassName = FactoryTools::findPartialClassNameInPaths(
            $className,
            $this->searchPaths
        );

        if(preg_match($this->genericNamingRegExp, $partialClassName, $matches)){
            if(isset($matches["entityname"])){
                return $matches["entityname"];
            }
            return array_pop($matches);
        }else{
            return $partialClassName;
        }
    }

} 