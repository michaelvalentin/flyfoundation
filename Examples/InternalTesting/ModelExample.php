<?php
namespace Flyf\Examples;

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once '../../Core/Dispatcher.php';
\Flyf\Core\Dispatcher::Init();

\Flyf\Core\Config::Setup(array(
	'profiler_console_output' => true,
	'profiler_file_output' => true,
	'profiler_file_write' => array('multiple', 'single'),
	'profiler_file_path' => 'Var/'
));



use \Flyf\Database\Connection as Connection;
use \Flyf\Models\Cms\Page as Page;

$connection = Connection::GetInstance();

$connection->Connect();

// Create and save
echo "\r\n >> create and save\r\n";
$page = Page::Create(array(
	'title' => 'My New Page',
	'content' => 'Wooaw, My New Page'
));

echo $page->Get('id')."\r\n";
echo $page->Get('title')."\r\n";
echo $page->Get('content')."\r\n";

$page->Save();

// Load by id and double save
echo "\r\n >> load by id and double save\r\n";
$page = Page::Load(1);

echo $page->Get('id')."\r\n";
echo $page->Get('title')."\r\n";
echo $page->Get('content')."\r\n";
echo $page->Get('date_modified')."\r\n";

$page->Set('title', 'hello');

$page->Save();

echo $page->Get('id')."\r\n";
echo $page->Get('title')."\r\n";
echo $page->Get('content')."\r\n";
echo $page->Get('date_modified')."\r\n";

$page->Set('title', 'No hello');

$page->Save();

echo $page->Get('id')."\r\n";
echo $page->Get('title')."\r\n";
echo $page->Get('content')."\r\n";
echo $page->Get('date_modified')."\r\n";

// Load by param and trash / untrash
echo "\r\n >> load by param\r\n";
$page = Page::Load(array('title' => 'No hello'));

echo $page->Get('id')."\r\n";
echo $page->Get('title')."\r\n";
echo $page->Get('content')."\r\n";

$page->Trash();

echo $page->Get('id')."\r\n";
echo $page->Get('title')."\r\n";
echo $page->Get('content')."\r\n";
echo $page->Get('date_trashed')."\r\n";

$page->Untrash();

echo $page->Get('id')."\r\n";
echo $page->Get('title')."\r\n";
echo $page->Get('content')."\r\n";
echo $page->Get('date_trashed')."\r\n";

$connection->Disconnect();

?>
