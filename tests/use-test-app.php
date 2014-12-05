<?php

require_once __DIR__."/test-init.php";

use FlyFoundation\App;
use FlyFoundation\Core\Context;

$app = new App();
$app->addConfigurators(__DIR__."/TestApp/configurators");
$app->prepareCoreDependencies(new Context(""));