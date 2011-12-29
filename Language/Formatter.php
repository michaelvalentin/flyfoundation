<?php
namespace Flyf\Language;

class Formatter {
	/**
	 * Format a float according to current language settings
	 * @param float $float
	 * @return string A string representing the input, according to language settings
	 */
	public static function FormatFloat($float){
		return number_format($float, 2); //TODO: Implement to use language settings..
	}
	
	/**
	 * Format a DateTime according to current language settings
	 * @param DateTime $datetime
	 * @return string A string representing the input, according to language settings
	 */
	public static function FormatDateTime(DateTime $datetime){
		return $datetime->format("Y-m-d H:i:s"); //Implement to use language settings..
	}
}

?>