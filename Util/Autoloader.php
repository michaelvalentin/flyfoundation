<?php
namespace Flyf\Util;

/**
 * Autoloader to make sure that we don't have to do includes. Loads based on the namespace defined
 *
 * @author Michael Valentin
 */
class Autoloader {

    /**
     * Load a class file, based on the class name
     *
     * @param $name The fully qualified name of the class, including namespace
     */
    public static function LoadClass($name) {

        //Split the request
        $parts = explode("\\", $name);

        //Insert library url if found
        switch($parts[0]){
            case "Flyf" :
                $parts[0] = FLYFDIR;
                break;
        }

        //Determine file name
		$filename = implode(DS,$parts).".php";

		//Try to find file in defined path
		if (file_exists($filename)) {
			require_once $filename;
			return;
		}

        //Find and explode the include path
		$include_paths_string = get_include_path();
		if (preg_match("/;/",$include_paths_string)) {
			$include_paths = explode(";",$include_paths_string);
		} else {
			$include_paths = explode(":", $include_paths_string);
		}

		//Try to require file from include path(s)
		foreach ($include_paths as $p){
			if (file_exists($p.DS.$filename)) {
				require_once $p.DS.$filename;
				return;
			}
		}

        //Try to use pre-namespace format, if filename contains _
		if(preg_match("/_/",$name)){
			$parts = explode("_",$name);

			//Determine library and file name
            $filename = implode(DS, $parts).".php";
			
			//Try to find file in libraries with flyf..
			if (file_exists(FLYFDIR.DS.$filename)) {
				require_once FLYFDIR.DS.$filename;
				return;
			}
			
			//Try to require file from include path(s)
			foreach ($include_paths as $p){
				if (file_exists($p.DS.$filename)) {
					require_once $p.DS.$filename;
					return;
				}
			}
		}
	}
}

//Define this class ass the autoloader for the application...
spl_autoload_register(__NAMESPACE__."\\Autoloader::LoadClass");

?>
