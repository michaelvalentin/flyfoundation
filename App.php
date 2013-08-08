<?php
namespace Flyf;

use \Flyf\Exceptions\InvalidOperationException;
use \Flyf\Database\Connection as Connection;
use \Flyf\Util\Debug as Debug;
use \Flyf\Util\Config as Config;
use \Flyf\Util\Request as Request;
use Flyf\Util\Session;

/**
 * Dispatcher for setting up and running the application
 *
 * @author Michael Valentin <mv@signifly.com>
 */
class App {
    private static $init = false;

	/**
	 * Run the application..
	 * @author Michael Valentin <mv@signifly.com>
	 */
	public static function Run(){
		if(!Config::IsLocked()) throw new InvalidOperationException("Configuration file must be locked, before the application can be run!");
		if(!self::$init) throw new InvalidOperationException("Application must be initialized with App::Init() before calling App::Run()");

		//Constants
		define('DEBUG', Config::Get("debug"));
		define('FLYF_ROOT', str_replace(DS."Core","",__DIR__));

		//What is the request?
		$request = Request::GetRequest();

        //echo '<pre>';
        //print_r($request->AsArray());
        //echo '</pre>';

		//What is the requested controller?
		$controller = $request->GetController();

        //What is the action
        $action = $request->GetAction();

        //Handle non existing controllers or actions
        if(empty($controller) || !method_exists($controller,$action)){
            $controller = new \Flyf\Modules\SystemController();
            $action = "PageNotFound";
        }

        $controller->$action();

		//Output the response for the request
		//$response = Response::GetResponse();
		//$response->SetController($controller);
		//$response->Output();

		//Flush the debugger to finish of..
		//Debug::Flush();
	}

    /**
     * Initiate the application. Sets up the autoloader and loads utility functions
     */
    public static function Init(){
        //Constants
        define("DS",DIRECTORY_SEPARATOR);
        define("FLYFDIR",__DIR__);

        //Autoloader for flyf lib files and define flyf dir
        require_once __DIR__ . "/Util/Autoloader.php";

        //Mark that we have successfully done the initialization
        self::$init = true;
    }
}
