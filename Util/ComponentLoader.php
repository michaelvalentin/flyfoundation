<?php
namespace Flyf\Util;

class ComponentLoader {
	/**
	 * Load the controller for this Flyf component
	 * @param string $componentName
	 * @return AbstractController The controller for the component
	 */
	public static function FromName($componentName){
		$className = "\\Flyf\\Components\\".$componentName."\\".$componentName."Controller";
		return self::TryToLoad($className);
	}
	
	public static function FromRequest(\Flyf\AbstractController $parent = null){
		if($parent == null){
			$parentKey = "root";
		}else{
			$parentKey = preg_replace("/\\?Flyf\\Components\\\(.+?)Controller/","$1", get_class($parent));
		}
		$req = \Flyf\Core\Request::GetRequest();
		$actualClassName = "\\Flyf\\Components\\".$req->GetComponent($parentKey)."Controller";
		return self::TryToLoad($actualClassName);
	}
	
	private static function TryToLoad($className){
		if(class_exists($className, true)){
			return new $className();
		}else{
			return null;
		}
	}
}

?>