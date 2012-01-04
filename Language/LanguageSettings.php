<?php
namespace Flyf\Language;

class LanguageSettings {
	private $default = null;
	
	public static function GetDefaultLanguage(){
		$this->default = \Flyf\Core\Config::GetValue("default_language");
		return $this->default; //TODO: Implement
	}
	
	public static function GetCurrentLanguage(){
		return "en"; //TODO: Implement
	}
}

?>