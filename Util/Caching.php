<?php
namespace Flyf\Util;

/**
 * Simple caching mechanism
 * @author MV
 */
class Caching {
	private static $_data;
	
	/**
	 * Try to load a string from given key
	 * @param string $key
	 * @return NULL|string The value for the key if it exists, otherwise null
	 */
	public static function Load($key){
		if(DEBUG) return null; //Never use cache when in debug mode..
		if(!is_array(self::$_data)){
			self::LoadFromDb();
		} 
		if(isset(self::$_data[$key])){
			return self::$_data[$key];
		}
		return null;
	}
	
	/**
	 * Save this string in the cache, associated with this key
	 * @param unknown_type $key The key to load the value from later
	 * @param unknown_type $value The value to save
	 * @param unknown_type $ttl The time-to-live. In how many seconds from now should the entry be valid? 
	 */
	public static function Save($key, $value, $ttl){
		$expire = time()+$ttl;
		//Db::Execute("INSERT INTO caching (key, value, expires) VALUES ($key, $value, FROM_UNIXTIME($expire))");
	}
	
	/**
	 * Load cache-data from database
	 */
	private static function LoadFromDb(){
		//Db::Execute("DELETE FROM caching WHERE expires < NOW()");
		//self::$_data = Db::LoadAssoc("SELECT * FROM caching"); //TODO: Load caching and format for $_data..
		self::$_data = array();
	}
}

?>