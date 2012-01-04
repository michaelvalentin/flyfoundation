<?php
namespace Flyf\Examples;

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once '../../Core/Dispatcher.php';
\Flyf\Core\Dispatcher::Init();

use \Flyf\Database\Connection;

$connection = Connection::GetInstance();

$connection->Connect();

// Insert
$connection->Prepare('INSERT INTO cms_page (title, content) VALUES (:title, :content)');
$connection->Bind(array(
	':title' => 'My Title',
	':content' => 'My Content'
));

$result = $connection->ExecuteNonQuery();

print_r($result);

// Select
$connection->Prepare('SELECT * FROM cms_page WHERE content = :content');
$connection->Bind(array(
	':content' => 'My Content'
));

$result = $connection->ExecuteQuery();

print_r($result);

// Delete
$connection->Prepare('DELETE FROM cms_page WHERE content = :content');
$connection->Bind(array(
	':content' => 'My Content'
));

$result = $connection->ExecuteNonQuery();

print_r($result);

$connection->Disconnect();

?>
