<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new FlyFoundation\App();

$app->addConfigurator(__DIR__.'/configuration');

$app->serve($_GET["q"]);