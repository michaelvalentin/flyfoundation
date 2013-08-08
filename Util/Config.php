<?php
namespace Flyf\Util;

/**
 * Configuration for the FlyFoundation platform. The config is the BARE MINIMUM needed to start the application, and
 * is newer user editable. All other settings should be placed in the settings class, where they are easily user
 * editable from the administration
 *
 * IMPORTANT: NO SETTINGS HERE, AND NEVER ANYTHING TO BE MANIPULATED BY USERS! DEVELOPER/SYSTEM STUFF ONLY!
 * 
 * @author Michael Valentin <mv@signifly.com>
 */

class Config {
	private static $_config;
	private static $_locked = false;

	/**
	 * Set the configuration for the system to the values defined in the input array.
	 * Existing values are overridden by new values.
     *
	 * @param array $config Configuration as an array
	 * @throws \Flyf\Exceptions\InvalidOperationException If config has been locked. 
	 */
	public static function Set(array $config){
		if(self::$_locked) throw new \Flyf\Exceptions\InvalidOperationException("Config is locked and can not be modified.");
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
     * Legacy alias for "Get" method.
     * Get the value of the given index in the config
     *
     * @param $index
     * @return The value of the configuration field, null if no value is set
     */
    public static function GetValue($index){
        return self::Get($index);
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

    public static function GetOverride($className){
        return self::Get("override_class_".$className);
    }

    public static function SetOverride($changeClassName, $toClassName){
        self::Set([
           "override_class_".$changeClassName => $toClassName
        ]);
    }
}