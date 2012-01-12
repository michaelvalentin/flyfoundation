<?php
namespace Flyf\Core;

/**
 * Autoload to load a class from the Flyf library; Searches based on namespace..
 * @author MV
 */
class Autoloader {
	public static function LoadClass($name) {
		$parts = preg_split("&\\\&", $name);
		
		$include_paths_string = get_include_path();
		if (preg_match("/;/",$include_paths_string)) {
			$include_paths = preg_split("/;/",$include_paths_string);
		} else {
			$include_paths = preg_split("/:/", $include_paths_string);
		}

		//Determine library and file name
		$libname = $parts[0];
		$filename = "";
		for($i = 1; $i<(count($parts)-1); $i++){
			$filename .= $parts[$i].DS;
		}
		$filename .= $parts[count($parts)-1].".php";
		
		//Try to find file in libraries with flyf..
		$libfile = __DIR__.DS."..".DS."..".DS.$libname.DS.$filename;
		if (file_exists($libfile)) {
			require_once $libfile;
			return;
		}
		
		//Try to require file from include path(s)
		foreach ($include_paths as $p){
			if (file_exists($p.DS.$libname.DS.$filename)) {
				require_once $p.DS.$libname.DS.$filename;
				return;
			}
		}
	}
}

spl_autoload_register(__NAMESPACE__."\\Autoloader::LoadClass");

?>
