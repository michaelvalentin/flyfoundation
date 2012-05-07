<?php
namespace Flyf\Language;

use Flyf\Exceptions\InvalidOperationException;

use \Flyf\Core\Config as Config;
use \Flyf\Core\Request as Request;

class LanguageSettings {
	private static $_default;
	private static $_current;
	
	public static function GetDefaultLanguage(){
		if(!self::$_default) self::loadLanguages();
		return self:: $_default;
	}
	
	public static function GetCurrentLanguage(){
		if(!self::$_current) self::loadLanguages();
		return self:: $_current;
	}
	
	private static function loadLanguages(){
		$default_iso = Config::GetValue("default_language");
		$current_iso = \Flyf\Core\Request::GetRequest()->GetLanguageIso();
		$current_domain = false;
		if(!$current_iso){
			$current_domain = \Flyf\Core\Request::GetRequest()->GetDomain();
		}
		
		//Load default language
		$default = \Flyf\Models\Core\Language::Load(array("iso"=>$default_iso));
		if(!$default) throw new InvalidOperationException("Default language could not be loaded.");
		self::$_default = $default;
		
		//Load current language
		if($current_iso){
			$current = \Flyf\Models\Core\Language::Load(array("iso"=>$current_iso));
		}elseif($current_domain){
			$current = \Flyf\Models\Core\Language::Load(array("url"=>$current_domain));
		}
		if(!$current) $current = $default;
		self::$_current = $current;
	}
}

?>
