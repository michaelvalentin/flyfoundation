<?php
namespace Flyf\Util;

class ComponentLoader {
	public static function ComponentExists($component){
		$controller = self::ControllerClass($component);
		return class_exists($controller);
	}
	
	public static function LoadController($component, array $parameters = array()){
		$controller = self::ControllerClass($component);
		return new $controller($parameters);
	}
	
	private static function ControllerClass($component){
		$parts = explode("\\",$component);
		return "\\Flyf\\Components\\".$component."\\".array_pop($parts)."Controller";
	}
}