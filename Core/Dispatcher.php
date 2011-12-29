<?php
namespace Flyf\Core;

use \Flyf\Database\Connection;

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
		$rootController = Config::GetValue("root_controller");
		//$rootController->AddArgs(Request::GetArgs());  //TODO: WHERE SHOULD WE GET ROOT CONTROLLER FROM? Consider other than argument
		$rootController->Process();
		$rootController->CollectData();
		$Response->SetContent($rootController->Render()); 
		$Response->Output();

		
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
