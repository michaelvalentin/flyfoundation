<?php
namespace Flyf\Util;

/**
 * The static Validate class is a collection of
 * static methods. Each method takes a range of
 * parameters, validates whether the input corresponds
 * to the argument and returns a boolean value.
 *
 * @note
 * The Validate class should be expanded as our 
 * needs increase. If we need specialization of the
 * Validate class we can consider to rewrite in to
 * the UrlHelper style (with a factory and default/custom).
 *
 * @author Henrik Haugbølle <hh@signifly.com>
 * @version 2012-01-06
 */
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

	/**
	 * The method is a "uni"-method to call the methods
	 * in the Validate class. It is usable when one wants
	 * to automate different calls or when iterating through
	 * a number of validations (e.g. stored in an array).
	 *
	 * @param string $method
	 * @param mixed $content
	 * @param mixed $argument
	 * @return boolean
	 */
	public static function Validate($method, $content, $argument) {
		$method = str_replace(' ', '', ucwords(str_replace('_', ' ', $method)));

		if (!method_exists(__CLASS__, $method)) {
			throw new Exception('Method "'.$method.'" does not exists in class "'.__CLASS__.'"');
		}

		return self::$method($content, $argument);
	}
}
?>
