<?php

require_once __DIR__.'/test-init.php';

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
        $config->set("database_data_object_prefix","Demo");
        $this->dbFactory->setConfig($config);
        parent::setUp();
    }

    public function testGetDynamicDatabaseClassName()
    {
        $res1 = $this->dbFactory->getDynamicDatabaseClassName("\\MyApp\\MyScope\\SomeNonExistantDataMapper","DataMapper");
        $res2 = $this->dbFactory->getDynamicDatabaseClassName("\\OtherApp\\VirtualDataFinder","DataFinder");
        $this->assertSame("\\FlyFoundation\\Database\\DemoDataMapper",$res1);
        $this->assertSame("\\FlyFoundation\\Database\\DemoDataFinder",$res2);
    }

    public function testGetDynamicDatabaseClassNameFail()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = $this->dbFactory->getDynamicDatabaseClassName("\\Test\\Scope\\SomeDataMethods","DataMethods");
    }
}
 