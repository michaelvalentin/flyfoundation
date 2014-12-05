<?php

//Necessary auto loaders to run the system
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/autoload.php';

//Initiating the application
$app = new FlyFoundation\App();
$app->addConfigurators(__DIR__.'/configuration');

//Retrieving the system query
$uri = $_GET["q"];
unset($_GET["q"]);

//Building the call context
$context = new \FlyFoundation\Core\Context($uri,[
    "get" => $_GET,
    "post" => $_POST,
    "server" => $_SERVER,
    "files" => $_FILES,
    "cookie" => $_COOKIE
]);

//Cleaning up globals to avoid usage
unset($_GET);
unset($_POST);
unset($_SERVER);
unset($_FILES);
unset($_COOKIE);

//Serve the response to the call context
$app->serve($context);