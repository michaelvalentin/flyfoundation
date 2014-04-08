<?php

namespace FlyFoundation;

/**
 * Class Config
 *
 * Configurations that are global and NOT user editable. The configuration must be locked at application
 * runtime, and is from that point immutable.
 *
 * @package Core
 */
class Config {
	private static $_config;
	private static $_locked = false;

	/**
	 * Set the configuration for the system to the values defined in the input array.
	 * Existing values are overridden by new values.
     *
	 * @param array $config Configuration as an array
	 * @throws \Exceptions\InvalidOperationException If config has been locked.
	 */
	public static function Set(array $config){
		if(self::$_locked){
            throw new \Exceptions\InvalidOperationException("Config is locked and can not be modified. Set configurations through config files.");
        }
		if(!is_array(self::$_config)){
			self::$_config = $config;
		}else{
			self::$_config = array_merge(self::$_config,$config);	
		}
	}

    /**
     * Get the value of the given index in the config
     *
     * @param $index
     * @return The value of the configuration field, null if no value is set
     */
	public static function Get($index){
		if(!is_array(self::$_config)) return null;
		if(isset(self::$_config[$index])){
			return self::$_config[$index];
		}
		return null;
	}

    /**
     * Lock the configuration
     */
    public static function Lock(){
		self::$_locked = true;
	}

    /**
     * Is the configuration locked?
     *
     * @return bool True if it is locked
     */
    public static function IsLocked(){
		return self::$_locked;
	}
}