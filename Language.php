<?php
namespace Flyf;

use Flyf\Language\LanguageSettings;

use Flyf\Core\Config;
use Flyf\Language\Formatter;
use Flyf\Language\LangaugeSettings;
use Flyf\Language\Writer;

class Language {
	public static function _($string, array $parts = array()){
		return Writer::Write($string,$parts);
	}
	
	public static function GetCurrent(){
		return LanguageSettings::GetCurrentLanguage();
	}
	
	public static function GetDefault(){
		return LanguageSettings::GetDefaultLanguage();
	}
}

?>