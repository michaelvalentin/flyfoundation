<?php
namespace Flyf;

use \Flyf\Exceptions\InvalidOperationException;
use \Flyf\Database\Connection as Connection;
use \Flyf\Core\Config as Config;
use \Flyf\Core\Request as Request;
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
        try {
            //Check necessary preconditions
            if(!Config::IsLocked()) throw new InvalidOperationException("Configuration file must be locked, before the application can be run!");
            if(!self::$init) throw new InvalidOperationException("Application must be initialized with App::Init() before calling App::Run()");

            //Catch debug input before we process futher
            if(isset($_GET["debug"])){
                define('DEBUG_CMD', $_GET["debug"]);
                unset($_GET["debug"]);
            }else{
                define('DEBUG_CMD', false);
            }

            //Define relevant constants
            if(DEBUG_CMD == "nodebug"){
                define('DEBUG', false);
            }else{
                define('DEBUG', Config::Get("debug"));
            }

            //Start profile-timer if necessary
            if(DEBUG_CMD == "profile" && DEBUG){
                $starttime = microtime();
            }

            //All errors should be shown when the app is in debug mode!
            if(DEBUG){
                error_reporting(E_ALL);
            }

            //Get the request
            $request = Request::GetRequest();
            //Print the request as debug?
            if(DEBUG && DEBUG_CMD == "request"){
                echo '<pre>';
                print_r($request->AsArray());
                echo '</pre>';
                die();
            }

            //What is the requested controller?
            $controller = $request->GetController();

            //What is the action
            $action = $request->GetAction();

            //Handle non existing controllers or actions
            if(empty($controller) || !method_exists($controller,$action)){
                $controller = new \Flyf\Controllers\SystemController();
                $action = "PageNotFound";
            }

            $controller->$action();

            //Output the response for the request
            //$response = Response::GetResponse();
            //$response->SetController($controller);
            //$response->Output();

            //Flush the debugger to finish of..
            //Debug::Flush();
            if(DEBUG_CMD == "profile" && DEBUG){
                $now = microtime();
                echo sprintf("<p><b>Total time in seconds:</b> %f</p>", $now-$starttime);
            }
        } catch(\Exception $e){

        }
	}

    /**
     * Initiate the application. Sets up the autoloader and loads utility functions
     */
    public static function Init(){
        //Constants
        define("DS",DIRECTORY_SEPARATOR);
        define("FLYFDIR",__DIR__);

        //Autoloader for flyf lib files and define flyf dir
        require_once __DIR__ . "/Core/Autoloader.php";

        //Mark that we have successfully done the initialization
        self::$init = true;
    }
}
