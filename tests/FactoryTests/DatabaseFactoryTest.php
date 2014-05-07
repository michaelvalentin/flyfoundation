<?php

require_once __DIR__ . '/../test-init.php';

use FlyFoundation\Core\Factories\DatabaseFactory;

class DatabaseFactoryTest extends PHPUnit_Framework_TestCase {
    /** @var DatabaseFactory $dbFactory */
    private $dbFactory;
    /** @var  Factory $factory */
    private $factory;

    protected function setUp()
    {
        $this->dbFactory = new \FlyFoundation\Core\Factories\DatabaseFactory();
        $config = new \FlyFoundation\Config();
        $config->databaseSearchPaths = new \FlyFoundation\Util\ValueList([
            "\\Somesystem\\Tests"
        ]);
        $config->set("database_data_object_prefix","Demo");
        $this->dbFactory->setConfig($config);

        $app = new \FlyFoundation\App();
        $app->addConfigurators(TEST_BASE."/TestApp/configurators");
        $context = new \FlyFoundation\Core\Context();
        $this->factory = $app->getFactory($context);
        $this->factory->getConfig()->baseSearchPaths->add("\\TestApp");

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

    public function testLoadingNonExistantDataMapper()
    {
        $result = $this->factory->loadDataMapper("SpecialClassDataMapper");
        $this->assertInstanceOf("\\FlyFoundation\\Database\\MySqlDataMapper",$result);
    }

    public function testLoadingDataFinderImplementedInTestAppOnly()
    {
        $result = $this->factory->loadDataFinder("TestAppOnly");
        $this->assertInstanceOf("\\TestApp\\Database\\MySqlTestAppOnlyDataFinder",$result);
    }

    public function testLoadingDataFinderImplementedInFlyFoundationAndTestApp()
    {
        $result = $this->factory->load("\\FlyFoundation\\Database\\DataFinder");
        $this->assertInstanceOf("\\TestApp\\Database\\MySqlDataFinder",$result);
    }

    public function testLoadingDataMethods()
    {
        $result = $this->factory->loadDataMethods("MySpecialDataMethods");
        $this->assertInstanceOf("\\TestApp\\Database\\MySqlMySpecialDataMethods", $result);
    }

    public function testLoadingNonExistingDataMethods()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $this->factory->loadDataMethods("ThatDoesNotExist");
    }
}
 