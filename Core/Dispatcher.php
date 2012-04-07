<?php
namespace Flyf\Core;

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
		//Constants
		define('DEBUG', Config::GetValue("debug"));
		
		//Setup a database connection
		$connection = Connection::GetConnection();
		$connection->Connect();

		//Find the root controller to process
		$controller = \Flyf\Util\ComponentLoader::FromRequest();

		//Process the root controller and collect data
		$controller->Process();
		$controller->CollectData();
		
		//Output the response
		$response = Response::GetResponse();	
		$response->SetContent($controller->Render()); 
		$response->Output();

		//Make sure to disconnect the database..
		$connection->Disconnect();
		
		//Flush the debug to finish of..
		Debug::Flush();
	}
	
	/**
	 * Initialize the application
	 * @author Michael Valentin <mv@signifly.com>
	 */
	public static function Init() {
		//Constants
		define("DS",DIRECTORY_SEPARATOR);
		
		//Load custom functions
		require_once 'Flyf/functions.php';
		
		//Autoloader for flyf lib files
		require_once 'Autoloader.php'; 
		
		//Standard configuration
		require_once 'Flyf/Resources/DefaultConfig/stdConfig.php';
	}
}
