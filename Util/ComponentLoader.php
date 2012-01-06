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
		$controller = $req->GetComponent($parentKey);
		$controller = str_replace(' ', '', ucwords(str_replace('\\', ' ', $controller)));

		// jeg har bare justeret en anelse her for at kunne lave urlhelperen.
		
		$actualClassName = "\\Flyf\\Components\\".$controller."\\".$controller."Controller";

		return self::TryToLoad($actualClassName);
	}
	
	private static function TryToLoad($className){
		if(class_exists($className, true)){
			/*if(!in_array($className, \Flyf\Core\Config::GetValue("allowed_components"))){
				echo $className;
				throw new \Exception("Component is not allowed to be called in this project");
			}*/
			
			return new $className();
		}else{	
			return null;
		}
	}
}

?>
