<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/autoload.php';

$app = new FlyFoundation\App();

$app->addConfigurators(__DIR__.'/configuration');

$app->serve($_GET["q"]);