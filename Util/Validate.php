<?php
namespace Flyf\Util;

class Validate {

	public static function Length($content, $length) {
		return strlen($content) > $length;
	}

	public static function Format($content, $format) {
		switch ($format) {
			case 'string': 
				return is_string($content); 
				break;
			case 'numeric': 
			case 'number': 
				return is_numeric($content); 
				break;
		}

		return false;
	}

	public static function Validate($method, $content, $argument) {
		$method = str_replace(' ', '', ucwords(str_replace('_', ' ', $method)));

		if (!method_exists(__CLASS__, $method)) {
			throw new Exception('Method "'.$method.'" does not exists in class "'.__CLASS__.'"');
		}

		return self::$method($content, $argument);
	}
}
?>
