<?php
namespace Flyf\Examples;

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once '../../Core/Dispatcher.php';
\Flyf\Core\Dispatcher::Init();

use \Flyf\Util\Debug as Debug;

Debug::Error("This is an error");
Debug::Log("This is a log");
Debug::Hint("This is a hint");

Debug::Flush();

?>
