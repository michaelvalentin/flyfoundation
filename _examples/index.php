<?php
require_once '../App.php';

$app = new Flyf\App();
$app->Init();
Flyf\Util\Config::Lock();
$app->Run();