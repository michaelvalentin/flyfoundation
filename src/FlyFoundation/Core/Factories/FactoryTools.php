<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Util\ValueList;

class FactoryTools {


    public static function findPartialClassNameInPaths($className, ValueList $searchPaths)
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

    public static function findImplementation($className, ValueList $searchPaths)
    {

        $partialClassName = self::findPartialClassNameInPaths($className, $searchPaths);

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

    public static function prefixActualClassName($className, $prefix)
    {
        $classNameParts = explode("\\",$className);
        $lastClassNamePart = array_pop($classNameParts);
        $databasePrefixedLastClassNamePart = $prefix.$lastClassNamePart;
        array_push($classNameParts,$databasePrefixedLastClassNamePart);
        $className = implode("\\",$classNameParts);
        return $className;
    }
} 