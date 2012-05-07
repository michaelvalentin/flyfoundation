<?php
namespace Flyf\Language;

use \Flyf\Core\Config as Config;
use \Flyf\Core\Request as Request;

class LanguageSettings {
	public static function GetDefaultLanguage(){
		return "en"; //!!TODO Implement!
		return Config::GetValue("default_language");
	}
	
	public static function GetCurrentLanguage(){
		return "en"; //!!TODO Implement!
		return Request::GetRequest()->GetLanguage();
	}
}

?>
