<?php


namespace FlyFoundation\Util;


use FlyFoundation\Exceptions\InvalidArgumentException;
use ReflectionClass;

abstract class Enum {
    private static $constCacheArray = NULL;

    private static function getConstants()
    {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    public static function isValidName($name)
    {
        $constants = self::getConstants();

        return array_key_exists($name, $constants);
    }

    public static function isValidType($type)
    {
        $values = array_values(self::getConstants());
        return in_array($type, $values, $strict = true);
    }

    public static function nameFromType($type)
    {
        $constants = self::getConstants();
        if(!array_key_exists($type, $constants)){
            throw new InvalidArgumentException(
                "There is no enum with that type in ".get_called_class()
            );
        }
        return $constants[$type];
    }

    public static function typeFromName($name)
    {
        if(!self::isValidName($name)){
            return false;
        }

        $constants = self::getConstants();

        foreach($constants as $constName => $value){
            if($constName == $name) return $value;
        }
    }
} 