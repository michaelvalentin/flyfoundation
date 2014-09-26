<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/autoload.php';

$app = new FlyFoundation\App();

$app->addConfigurators(__DIR__.'/configuration');

$query = $_GET["q"];
unset($_GET["q"]);
$app->serve($query);