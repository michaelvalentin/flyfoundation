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
		define("DEBUG",Config::GetValue("debug"));
		
		$connection = Connection::GetConnection();
		$connection->Connect();

		$Response = Response::GetResponse();
		$rootController = \Flyf\Util\ComponentLoader::FromRequest();
		if($rootController  == null) {
			$rootController = \Flyf\Util\ComponentLoader::FromName("SharedTest");
		}

		$rootController->Process();
		$rootController->CollectData();
		$Response->SetContent($rootController->Render()); 
		$Response->Output();

		Debug::Flush();
		$connection->Disconnect();
	}
	
	/**
	 * Initialize the application
	 */
	public static function Init(){
		//Constants
		define("DS",DIRECTORY_SEPARATOR);
		
		require_once 'Autoloader.php';
		//Autoloader
	}
}

?>
