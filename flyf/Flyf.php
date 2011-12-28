<?php
class Flyf {
	private static $registry;

	public static function register($key, $value) {
		return self::$registry[$key] = $value;
	}
	public static function registry($key) {
		return isset(self::$registry[$key]) ? self::$registry[$key] : false;
	}
}
?>
