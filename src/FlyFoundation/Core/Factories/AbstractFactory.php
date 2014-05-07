<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Core\Environment;
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
        while($config->classOverrides->containsKey($className))
        {
            $className = $config->classOverrides->get($className);
        }
        if($originalClassName == $className){
            return false;
        }else{
            return $className;
        }
    }


    public function explodeClassName($className)
    {
        $parts = explode("\\",$className);
        if($parts[0]==""){
            array_shift($parts);
        }
        return $parts;
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
            return $className;
        }

        foreach($searchPaths->asArray() as $path){
            $fullClassName = $path."\\".$partialClassName;
            if(class_exists($fullClassName)){
                return $fullClassName;
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