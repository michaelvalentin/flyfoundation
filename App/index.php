<?php
require_once '../Flyf/App.php';

$app = new Flyf\App();
$app->Init();
Flyf\Core\Config::Set(["debug" => true]);
Flyf\Core\Config::Lock();
$app->Run();