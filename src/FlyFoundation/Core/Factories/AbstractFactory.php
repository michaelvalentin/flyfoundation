<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Core\Environment;
use FlyFoundation\Exceptions\InvalidConfigurationException;
use FlyFoundation\Util\Set;
use FlyFoundation\Util\ValueList;

abstract class AbstractFactory {
    use Environment;

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public abstract function load($className, array $arguments = array());

    public abstract function exists($className);

    public function getOverride($className){
        $config = $this->getConfig();
        $originalClassName = $className;
        $i = 0;
        $usedClasses = new Set();
        while($config->classOverrides->containsKey($className))
        {
            $className = $config->classOverrides->get($className);

            $i++;
            if($i > 20){
                if($usedClasses->contains($className)){
                    throw new InvalidConfigurationException("The overwriting of '".$originalClassName."' is circular, and the class name can not be resolved.");
                }
                $usedClasses->add($className);
            }
        }
        if($originalClassName == $className){
            return false;
        }else{
            return $className;
        }
    }

    public function findPartialClassNameInPaths($className, ValueList $searchPaths)
    {
        $matches = [];

        foreach($searchPaths->asArray() as $path)
        {
            $regexpPath = str_replace("\\","\\\\",$path);

            $startWithPath = "/^".$regexpPath."\\\\(.*)/";
            $matchesStartsWithPath = preg_match($startWithPath,$className,$matches);

            if($matchesStartsWithPath){
                return $matches[1];
            }
        }
        return false;
    }

    public function findImplementation($className, ValueList $searchPaths)
    {

        $partialClassName = $this->findPartialClassNameInPaths($className, $searchPaths);

        if(!$partialClassName){
            return false;
        }

        foreach($searchPaths->asArray() as $path){
            $potentialImplementation = $path."\\".$partialClassName;
            if(class_exists($potentialImplementation)){
                return $potentialImplementation;
            }
        }
        return false;
    }



    public function prefixActualClassName($className, $prefix)
    {
        $classNameParts = explode("\\",$className);
        $lastClassNamePart = array_pop($classNameParts);
        $databasePrefixedLastClassNamePart = $prefix.$lastClassNamePart;
        array_push($classNameParts,$databasePrefixedLastClassNamePart);
        $className = implode("\\",$classNameParts);
        return $className;
    }
} 