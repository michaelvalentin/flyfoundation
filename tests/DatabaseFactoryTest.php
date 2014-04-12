<?php

require_once __DIR__."/../vendor/autoload.php";

use FlyFoundation\Core\Factories\DatabaseFactory;

class DatabaseFactoryTest extends PHPUnit_Framework_TestCase {
    /** @var DatabaseFactory $dbFactory */
    private $dbFactory;

    protected function setUp()
    {
        $this->dbFactory = new \FlyFoundation\Core\Factories\DatabaseFactory();
        $config = new \FlyFoundation\Config();
        $config->databaseSearchPaths = new \FlyFoundation\Util\ValueList([
            "\\Somesystem\\Tests"
        ]);
        $config->set("database_data_object_prefix","DEMO");
        $this->dbFactory->setConfig($config);
        parent::setUp();
    }

}
 