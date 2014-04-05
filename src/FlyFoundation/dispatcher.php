<?php

use \Core\Config;
use \Core\App;

//Constants
define("DS",DIRECTORY_SEPARATOR);
define("BASEDIR",__DIR__);

try
{
    require_once __DIR__ . '/Core/App.php';

    //Get the query string, as identified by the internal server redirects (.htaccess)
    $query = isset($_GET["q"]) ? $_GET["q"] : false;
    unset($_GET["q"]);

    //Autoload for convenient class loading
    App::InitAutoload();

    //Configurations for this app
    require_once __DIR__ . "/config.php";

    //Initialize the app
    App::Init($query);

    //Lock the configuration
    Config::Lock();

    //Serve the page with the given query
    App::Serve($query);
}
catch(Exception $e)
{
    //TODO!
    echo "EXCEPTIONS SHOULD BE LOGGED! And users informed...";
    echo "<br /><br />";
    echo $e->getMessage();
}

if(DEBUG_CMD == "serverdata"){
    echo '<pre>';
    echo "Server:";
    print_r($_SERVER);
    echo'</pre>';
}