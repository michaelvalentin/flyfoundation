<?php
namespace Flyf\Core;

use Flyf\Exceptions\InvalidOperationException;

use \Flyf\Database\Connection as Connection;
use \Flyf\Util\Debug as Debug;

/**
 * A class to take of dispatching the application
 * @author Michael Valentin <mv@signifly.com>
 */
class Dispatcher {
	/**
	 * Run the application..
	 * @author Michael Valentin <mv@signifly.com>
	 */
	public static function Run(){
		if(!Config::IsLocked()) throw new InvalidOperationException("Configuration file must be locked, before the application can be run!");
		
		//Constants
		define('DEBUG', Config::GetValue("debug"));
		define('FLYF_ROOT', str_replace(DS."Core","",__DIR__));

		//What is the request?
		$request = Request::GetRequest();
		
		//What is the requested controller?
		$controller = $request->GetFrontController();
		
		//Output the response for the request
		$response = Response::GetResponse();
		$response->SetController($controller); 
		$response->Output();

		//Flush the debugger to finish of..
		Debug::Flush();
	}
	
	/**
	 * Initialize the application
	 * @author Michael Valentin <mv@signifly.com>
	 */
	public static function Init() {
		//Constants
		define("DS",DIRECTORY_SEPARATOR);
		
		//Load Flyf utility functions
		require_once 'Flyf/Util/functions.php';
		
		//Autoloader for flyf lib files
		require_once 'Autoloader.php';
		
		\Flyf\Resources\Configurations\StandardConfiguration::Apply();
	}
}
