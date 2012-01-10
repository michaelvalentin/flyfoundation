<?php
namespace Flyf\Examples;

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once '../../Core/Dispatcher.php';
\Flyf\Core\Dispatcher::Init();

\Flyf\Core\Config::Set(array(
	'debug_console_level' => array('error', 'log', 'hint'),
	'debug_file_level' => array('error', 'log', 'hint'),
	'debug_file_write' => array('multiple', 'single'),
	'debug_file_path' => 'Var/',

	'database_hostname' => 'localhost',
	'database_username' => 'signifly',
	'database_password' => 'abcd1234',
	'database_database' => 'signifly',
	'database_charset' => 'utf8'
));

use \Flyf\Database\Connection as Connection;

$connection = Connection::GetConnection();

$connection->Connect();

use \Flyf\Util\UrlHelper as UrlHelper;

echo UrlHelper::GetUrl('page(id=8&url=hello-article)', 'current', false, true);

$connection->Disconnect();

?>
