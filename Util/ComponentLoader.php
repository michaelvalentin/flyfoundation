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
			if(!in_array($className, \Flyf\Core\Config::GetValue("allowed_components"))){
				echo $className;
				throw new \Exception("Component is not allowed to be called in this project");
			}
			return new $className();
		}else{
			return null;
		}
	}
}

?>