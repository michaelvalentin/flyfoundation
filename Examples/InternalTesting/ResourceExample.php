<?php
namespace Flyf\Examples;

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once '../../Core/Dispatcher.php';
\Flyf\Core\Dispatcher::Init();

use \Flyf\Database\Connection as Connection;
use \Flyf\Models\Cms\Page as Page;

$connection = Connection::GetInstance();

$connection->Connect();

$resource = Page::Resource();
$pages = $resource->Build();

foreach ($pages as $page) {
	echo $page->get('id')."\r\n";
	echo $page->get('title')."r\n";
	echo $page->get('content')."\r\n\r\n";
}

$connection->Disconnect();

?>
