<?php
namespace Flyf\Language;

class Writer {
	private static $language  = array();
	
	/**
	 * Translate this string if necessary
	 * @param string $string
	 */
	public static function _($string, array $parts = array()){
		$output = "";
		if(isset(self::$language[self::IniFormat($string)])){
			$output = self::$language[self::IniFormat($string)];
		}else{
			$output = $string;
		}
		foreach($parts as $l=>$v){
			$output = str_replace("[[".$l."]]",$v,$output);
		}
		return $output;
	}
	
	public static function Load($dir){
		$langs = array();
		$langs["default"] = $dir.DS."Language".DS.LanguageSettings::GetDefaultLanguage().".ini";
		if(LanguageSettings::GetCurrentLanguage() != LanguageSettings::GetDefaultLanguage()){
			$langs["current"] = $dir.DS."Language".DS.LanguageSettings::GetCurrentLanguage().".ini";
		}
		
		foreach($langs as $l=>$la){
			if(file_exists($la)){
				$values = parse_ini_file($la);
				self::$language = array_merge(self::$language, $values);	
			}else{
				//Debug::Hint("No language ini-file for ".$l." language at '".$dir."'");
			}
		}
	}
	
	private static function IniFormat($string){
		$out = strtoupper($string);
		$out = preg_replace("/\s/","_",$out);
		return $out;
	}
}

?>