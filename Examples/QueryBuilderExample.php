<?php
namespace Flyf\Examples;

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once '../Core/Dispatcher.php';
\Flyf\Core\Dispatcher::Init();

use \Flyf\Database\Connection as Connection;
use \Flyf\Database\QueryBuilder as QueryBuilder;

$connection = Connection::GetInstance();

$connection->Connect();

$queryBuilder = new QueryBuilder();

// Select
$queryBuilder->SetType('select');
$queryBuilder->SetTable('cms_page');
$queryBuilder->SetFields(array('id', 'title', 'content'));
$queryBuilder->AddCondition('title != :title');
$queryBuilder->BindParam('title', 'asd');

$result = $queryBuilder->Execute();

echo "\r\nSelect:\r\n";
echo $queryBuilder->GetLastQuery()."\r\n";

print_r($result);
echo "\r\n";

// Update
$queryBuilder->SetType('update');
$queryBuilder->SetTable('cms_page');
$queryBuilder->SetFields(array('title', 'content'));
$queryBuilder->SetValues(array('My title 3', 'My content 3'));
$queryBuilder->AddCondition('title = :title');
$queryBuilder->BindParam('title', 'My title 3');

$result = $queryBuilder->Execute();

echo "\r\nUpdate:\r\n";
echo $queryBuilder->GetLastQuery()."\r\n";

print_r($result);
echo "\r\n";

// Insert
$queryBuilder->SetType('insert');
$queryBuilder->SetTable('cms_page');
$queryBuilder->SetFields(array('id', 'title', 'content'));
$queryBuilder->SetValues(array('', 'My insert', 'My content insert'));

$result = $queryBuilder->Execute();

echo "\r\nInsert:\r\n";
echo $queryBuilder->GetLastQuery()."\r\n";

print_r($result);
echo "\r\n";

// Delete
$queryBuilder->SetType('delete');
$queryBuilder->SetTable('cms_page');
$queryBuilder->AddCondition('id = :id');
$queryBuilder->BindParams(array(
	'id' => $result
));

$result = $queryBuilder->Execute();

echo "\r\nDelete:\r\n";
echo $queryBuilder->GetLastQuery()."\r\n";

print_r($result);
echo "\r\n";


$connection->Disconnect();

?>
