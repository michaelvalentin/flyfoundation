<?php

namespace FlyFoundation\Core;

use Controllers\Abstracts\IController;
use Exceptions\InvalidArgumentException;
use Util\Profiler;

/**
 * Class App
 *
 * The application, which is able to initialize itself and serve a request
 *
 * @package Core
 */
class App {
    private static $init = false;

    /**
     * Server this request
     *
     * @param $query
     * @throws \Exceptions\InvalidArgumentException
     * @throws \Exceptions\InvalidOperationException
     */
    public static function Serve($query){
        //Check necessary preconditions
        if(!Config::IsLocked()) throw new \Exceptions\InvalidOperationException("Configuration file must be locked, before the application can be run!");
        if(!self::$init) throw new \Exceptions\InvalidOperationException("Application must be initialized with App::Init() before calling App::Run()");

        //Setup response
        $response = new Response();

        //Load the request
        $request = Request::getRequest();

        //Run the base controller
        $base_controller = Config::Get("BaseController");
        if(!$base_controller instanceof IController){
            throw new InvalidArgumentException("BaseController must be a valid IController in configuration");
        }
        $response = $base_controller->Render($response);

        //Determine and run the relevant controller
        $controller = Config::Get("Parser")->GetController(Request::getRequest());
        $response = $controller->Render($response);

        //Output the response
        $response->Output();
        if(DEBUG){ Debug::Flush(); }
        if(DEBUG_CMD == "profile"){ Profiler::PrintAllTimers(); }
	}

    /**
     * Initialize the autoload-feature of the system
     */
    public static function InitAutoload(){
        //Autoloader for flyf lib files and define flyf dir
        require_once __DIR__ . "/Autoloader.php";
    }

    /**
     * Initialize the application, including the request object
     *
     * @param $query
     */
    public static function Init($query){

        //Initialize the request
        \Core\Request::Init($query);

        //Load system configuration / defaults
        require_once __DIR__ . DS . "system-config.php";

        //Setup debug constants
        $debug_cmd = \Core\Request::GetRequest()->getParameter("d");
        if($debug_cmd && \Core\Request::GetRequest()->isDemoDomain())
        {
            define('DEBUG', true);
            define('DEBUG_CMD', $debug_cmd);
        }
        else
        {
            define('DEBUG_CMD', false);
            define('DEBUG', false);
        }

        //Start profiler
        if(DEBUG_CMD == "profile"){ Profiler::StartTimer("Program","The entire program from init till output"); }

        //All errors should be shown when the app is in debug mode!
        if(DEBUG){
            error_reporting(E_ALL);
        }else{
            error_reporting(E_ERROR);
        }

        //Mark that we have successfully done the initialization
        self::$init = true;
    }
}
