<?php

namespace Core;

/**
 * Class Autoloader
 *
 * An auto loader class for easy auto loading of classes
 *
 * @package Core
 */
class Autoloader {

    /**
     * Load this class
     *
     * @param $name
     */
    public static function LoadClass($name) {
        //Split the request
        $parts = explode("\\", $name);

        //Determine file name
		$filename = BASEDIR.DS.implode(DS,$parts).".php";

		//Try to find file in defined path
		if(file_exists($filename)) {
			require_once $filename;
			return;
		}
	}
}

//Define this class ass the autoloader for the application...
spl_autoload_register(__NAMESPACE__."\\Autoloader::LoadClass");
