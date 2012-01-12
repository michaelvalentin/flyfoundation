<?php
namespace Flyf\Util;

class ComponentLoader {
	/**
	 * Load the controller for this Flyf component
	 * @param string $componentName
	 * @return AbstractController The controller for the component
	 */
	public static function FromName($componentName) {
		$className = "\\Flyf\\Components\\".$componentName."\\".$componentName."Controller";
		return self::TryToLoad($className);
	}
	
	public static function FromRequest(\Flyf\AbstractController $parent = null) {
		if ($parent == null) {
			$parentKey = 'root';

			Debug::Log('No component specified when loading FromRequest in ComponentLoader; therefore loading root-component');
		} else {
			$parentKey = preg_replace("/\\?Flyf\\Components\\\(.+?)Controller/","$1", get_class($parent));
		}

		$request = \Flyf\Core\Request::GetRequest();
		$controller = $request->GetComponent($parentKey);
		$controller = str_replace(' ', '', ucwords(str_replace('\\', ' ', $controller)));
		
		$className = "\\Flyf\\Components\\".$controller."\\".$controller."Controller";

		return self::TryToLoad($className);
	}
	
	private static function TryToLoad($className){
		if (class_exists($className, true)) {
			// tjek om klassen er "lovlig" at kalde
			return new $className();
		}

		return null;
	}

	public static function NextComponent($component) {
		$request = \Flyf\Core\Request::GetRequest();
		$component_key = strtolower(str_replace('Controller', '', end(explode('\\', get_class($component)))));

		if (($next_key = $request->GetComponent($component_key)) !== null) {
			return self::FromName($next_key);
		}

		return null;
	}
}

?>
