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
    public abstract function load($className, $arguments = array());

    public function getOverride($className){
        $config = $this->getConfig();
        while($config->classOverrides->hasKey($className))
        {
            $className = $config->classOverrides->get($className);
        }
        return $className;
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

    public function findImplementation($className, ValueList $baseSearchPaths)
    {

        $partialClassName = $this->findPartialClassNameInPaths($className, $baseSearchPaths);

        if(!$partialClassName){
            return $className;
        }

        foreach($baseSearchPaths->asArray() as $path){
            $fullClassName = $path."\\".$partialClassName;
            if(class_exists($fullClassName)){
                return $fullClassName;
            }
        }
        return $className;
    }
} 