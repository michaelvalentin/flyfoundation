<?php
use FlyFoundation\Core\Config;
use FlyFoundation\Core\Context;
use FlyFoundation\SystemDefinitions\SystemDefinition;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/TestApp/autoload.php';

define('TEST_BASE',__DIR__);

//TODO: This could be better mock objects...
$config = new Config();
$config->dependencies->putDependency("FlyFoundation\\Dependencies\\AppContext",new Context(""),true);
$config->dependencies->putDependency("FlyFoundation\\Dependencies\\AppDefinition",new SystemDefinition(),true);
\FlyFoundation\Factory::setConfig(new Config());