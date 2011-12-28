<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

include 'flyf/Flyf.php';
include 'Core/Request.php';

include 'Database/Connection.php';
include 'Database/QueryBuilder.php';

include 'Models/Abstracts/Model.php';
include 'flyfModels/DataAccessObject.phpnclude 'flyfModels/ValueObject.phpnclude 'flyfModels/MetaValueObject.phpnclude 'Models/Resource.php';

include 'components/cms/model/Cms/Page.php';
include 'components/cms/model/Cms/Page/DataAccessObject.php';
include 'components/cms/model/Cms/Page/ValueObject.php';
include 'components/cms/model/Cms/Page/Resource.php';

include 'components/cms/model/Cms/Block.php';
include 'components/cms/model/Cms/Block/DataAccessObject.php';
include 'components/cms/model/Cms/Block/ValueObject.php';
include 'components/cms/model/Cms/Block/MetaValueObject.php';
include 'components/cms/model/Cms/Block/Resource.php';

include 'components/cms/model/Cms/None.php';
include 'components/cms/model/Cms/None/DataAccessObject.php';
include 'components/cms/model/Cms/None/ValueObject.php';
include 'components/cms/model/Cms/None/Resource.php';

$connection = Flyf_Database_Connection::getInstance();
$connection->connect();

Flyf::register('flyf_database_connection', $connection);
Flyf::register('flyf_database_querybuilder', new Flyf_Database_QueryBuilder());

$request = new Flyf_Request();
$request->configure();

print_r($request);

echo $request->getLanguage().'<br />';
echo $request->getComponent().'<br />';


$connection->disconnect();

?>
