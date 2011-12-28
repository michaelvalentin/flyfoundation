<?php
namespace Flyf\Components;

/**
 * Component related utilities and tools..
 * @author MV
 */
class Tools {
	/**
	 * Load the controller for this Flyf component
	 * @param string $componentName
	 * @return AbstractController The controller for the component
	 */
	public static function LoadController($componentName){
		$classname = "\\Flyf\\Components\\".$componentName."\\".$componentName."Controller";
		return new $classname();
	}
}

?>