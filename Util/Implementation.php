<?php

namespace Flyf\Util;

/**
 * Base implementation, for the easy override of implemented classes, based on configuration
 * Classes inheriting should make a simple override with PhpDoc for code completion]]
 * 
 * @Package: Flyf
 * @Author: Michael Valentin
 * @Created: 06-08-13 - 17:10
 */
class Implementation {
    private function __construct(){}
    private static $_instances = array();
    private static $_classMappings = array();

    /**
     * Get a new instance of the object, or potentially one of it's overriding children
     * Override this method to be private, in order to create a Singleton - only creation via I()
     *
     * @return Implementation The relevant implementation
     */
    protected static function Make(){
        $class = get_called_class();

        //For efficiency, if class has all-ready been resolved, just use this...
        if(array_key_exists($class, self::$_classMappings)) return new self::$_classMappings[$class];

        //Search for overrides
        $next  = $class;
        while($next){
            $class = $next;
            $next = Config::GetOverride($class);
        }

        return new $class;
    }

    /**
     * Get a single instance for accessing "static" methods
     *
     * @return Implementation
     */
    protected static function I(){
        $class = get_called_class();
        if(empty(self::$_instances[$class])) self::$_instances[$class] = self::Make();
        return self::$_instances[$class];
    }
}