<?php
namespace Basefly;

/**
 * AutoLoader to make sure that we don't have to do includes. Loads based on the namespace defined
 *
 * @author Michael Valentin
 */
class AutoLoader {

    /**
     * Load a class file, based on the class name
     *
     * @param $name The fully qualified name of the class, including namespace
     */
    public static function LoadClass($name) {

        $basedir = __DIR__."/";
        $nameParts = explode("\\",$name);
        array_shift($nameParts);
        $name = implode("/",$nameParts);
        $filename = $basedir.$name.".php";

        if (file_exists($filename)) {
            require_once $filename;
            return;
        }
    }
}

//Define this class ass the autoloader for the application...
spl_autoload_register(__NAMESPACE__."\\AutoLoader::LoadClass");