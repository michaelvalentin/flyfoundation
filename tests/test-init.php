<?php
use FlyFoundation\Config;
use FlyFoundation\Core\Context;
use FlyFoundation\SystemDefinitions\SystemDefinition;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/TestApp/autoload.php';

define('TEST_BASE',__DIR__);

//TODO: This could be better mock objects...
\FlyFoundation\Factory::setConfig(new Config());
\FlyFoundation\Factory::setContext(new Context());
$mockSystemDefinition = new SystemDefinition();
$mockSystemDefinition->applyOptions([
    "name" => "TestApp"
]);
$mockSystemDefinition->finalize();
\FlyFoundation\Factory::setAppDefinition($mockSystemDefinition);