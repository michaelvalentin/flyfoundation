<?php
namespace Flyf\Core;

/**
 * Static configuration class for the Flyf platform
 * @author MV
 */
class Config {
	private static $_config;
	private static $_locked = false;
	
	/**
	 * Set the configuration for the system to the values defined in the input array.
	 * Existing values are overridden by new values.
	 * @param array $config Configuration as an array
	 */
	public static function Set(array $config){
		if(self::$_locked) throw new \Exception("Config is locked and can not be modified.");
		if(!is_array(self::$_config)){
			self::$_config = $config;
		}else{
			self::$_config = array_merge(self::$_config,$config);	
		}
	}
	
	/**
	 * @param string $index The index to load from
	 * @throws \Exception If config has not been setup
	 * @return multitype:|NULL The value of the configuration field, null if no value is set.
	 */
	public static function GetValue($index){
		if(!is_array(self::$_config)) throw new \Exception("You cannot call Config before it is initialized");
		if(isset(self::$_config[$index])){
			return self::$_config[$index];
		}
		return null;
	}
	
	public static function Lock(){
		
	}
}

?>
