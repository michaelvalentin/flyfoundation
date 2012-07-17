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
	
	public static function IsDefault(\Flyf\Models\Core\Language $lang){
		return self::GetDefault()->iso == $lang->iso;
	}
	
	public static function IsCurrent(\Flyf\Models\Core\Language $lang){
		return self::GetCurrent()->iso == $lang->iso;
	}
}

?>