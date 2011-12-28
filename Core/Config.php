<?php
namespace Flyf\Core;

/**
 * Static configuration class for the Flyf platform
 * @author MV
 */
class Config {
	private static $_config;
	private static $_setup = false;
	
	/**
	 * Set the configuration for the system to the values defined in the input array
	 * @param array $config Configuration as an array
	 * @throws \Exception If configuration is allready set..
	 */
	public static function Setup(array $config){
		if(self::$_setup==true) throw new \Exception("Config can only be set up once");
		self::$_config = $config;
		self::$_setup = true;
	}
	
	/**
	 * @param string $index The index to load from
	 * @throws \Exception If config has not been setup
	 * @return multitype:|NULL The value of the configuration field, null if no value is set.
	 */
	public static function GetValue($index){
		if(!self::$_setup) throw new \Exception("You cannot call Config before it is initialized");
		if(isset(self::$_config[$index])){
			return self::$_config[$index];
		}
		return null;
	}
}

?>