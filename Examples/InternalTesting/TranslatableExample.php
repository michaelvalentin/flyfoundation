<?php
namespace Flyf\Examples;

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once '../../Core/Dispatcher.php';
require_once '../../Core/Config.php';
require_once '../../Language/LanguageSettings.php';

\Flyf\Core\Config::Setup(array(
	'database_hostname' => 'localhost',
	'database_username' => 'signifly',
	'database_password' => 'abcd1234',
	'database_database' => 'signifly',
	'database_charset' => 'utf8',

	'default_language' => 'da'
));

\Flyf\Core\Dispatcher::Init();

use \Flyf\Database\Connection as Connection;
use \Flyf\Models\Test\Blog\Entry as Entry;

$connection = Connection::GetConnection();

$connection->Connect();

$entry = Entry::load(1);

#print_r($entry->getTranslatableFields());

echo 'Default language:'."\r\n";
echo $entry->get('title')."\r\n";
echo $entry->get('content')."\r\n";
echo "\r\n";

echo 'En:'."\r\n";
echo $entry->get('title', 'en')."\r\n";
echo $entry->get('content', 'en')."\r\n";
echo "\r\n";

echo 'Fr:'."\r\n";
echo $entry->get('title', 'fr')."\r\n";
echo $entry->get('content', 'fr')."\r\n";
echo "\r\n";

echo 'Nl:'."\r\n";
$entry->set('title', 'værdi', 'nl');

echo $entry->get('title', 'nl')."\r\n";
echo $entry->get('content', 'nl')."\r\n";
echo "\r\n";

$entry->Save();

$connection->Disconnect();

?>
