<?php
namespace Flyf\Core;

use \Flyf\Database\Connection as Connection;
use \Flyf\Util\Debug as Debug;

/**
 * A class to take of dispatching the application
 * @author MV
 */
class Dispatcher {
	/**
	 * Run the application..
	 */
	public static function Run(){
		//Constants
		define('DEBUG', Config::GetValue("debug"));
		
		$connection = Connection::GetConnection();
		$connection->Connect();

		$response = Response::GetResponse();
		
		$rootController = \Flyf\Util\ComponentLoader::FromRequest();

		$rootController->Process();
		$rootController->CollectData();
		
		$response->SetContent($rootController->Render()); 
		$response->Output();

		$connection->Disconnect();
		
		Debug::Flush();
	}
	
	/**
	 * Initialize the application
	 */
	public static function Init() {
		
		//Constants
		define("DS",DIRECTORY_SEPARATOR);
		
		//Autoloader
		require_once 'Autoloader.php';
		
		//Standard configuration
		require_once 'Flyf/Resources/DefaultConfig/stdConfig.php';
	}
}
