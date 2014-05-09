<?php

require_once __DIR__ . '/../test-init.php';

use FlyFoundation\Core\Factories\DatabaseFactory;
use FlyFoundation\Factory;

class DatabaseFactoryTest extends PHPUnit_Framework_TestCase {
    /** @var DatabaseFactory $dbFactory */
    private $dbFactory;
    /** @var  Factory $factory */
    private $factory;

    protected function setUp()
    {
        $app = new \FlyFoundation\App();
        $app->addConfigurators(TEST_BASE."/TestApp/configurators");
        $context = new \FlyFoundation\Core\Context();
        $this->factory = $app->getFactory($context);

        $this->factory->getConfig()->baseSearchPaths->add("\\TestApp");
        $this->factory->getConfig()->databaseSearchPaths->add("\\Somesystem\\Tests");
        $this->dbFactory = $this->factory->load("\\FlyFoundation\\Core\\Factories\\DatabaseFactory");


        parent::setUp();
    }

    /**
     * load
     *  - Adds the relevant database prefix
     *  - If an implementation does not exist, a generic version is used
     */

    //Load an existing DataMapper
    public function testLoadExistingDataMapper()
    {
        $result = $this->factory->loadDataMapper("MyTest");
        $this->assertInstanceOf("\\TestApp\\Database\\MySqlMyTestDataMapper",$result);
    }

    //Load GenericDataMapper with entity definition
    public function testLoadGenericDataMapperByNameWithEntityDefinition()
    {
        $result = $this->factory->loadDataMapper("DemoEntity");
        $this->assertInstanceOf("\\FlyFoundation\\Database\\MySqlGenericDataMapper",$result);
    }

    //Load GenericDataMapper without entity definition
    public function testLoadGenericDataMapperByNameWithoutEntityDefinition()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = $this->factory->loadDataMapper("NotExistingEntity");
    }

    //Load existing DataFinder
    public function testLoadExistingDataFinder()
    {
        $result = $this->factory->loadDataFinder("MyOtherTest");
        $this->assertInstanceOf("\\TestApp\\Database\\MySqlMyOtherTestDataFinder",$result);
    }

    //Load GenericDataFinder with entity definition
    public function testLoadGenericDataFinderByNameWithEntityDefinition()
    {
        $result = $this->factory->loadDataFinder("DemoEntity");
        $this->assertInstanceOf("\\FlyFoundation\\Database\\MySqlGenericDataFinder",$result);
    }

    //Load GenericDataFinder without entity definition
    public function testLoadGenericDataFinderByNameWithoutEntityDefinition()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = $this->factory->loadDataFinder("NotExistingEntity");
    }

    //Load existing DataMethods
    public function testLoadExistingDataMethods()
    {
        $result = $this->factory->loadDataMethods("SomeDataMethods");
        $this->assertInstanceOf("\\TestApp\\Database\\MySqlSomeDataMethods",$result);
    }

    //Load not existing DataMethods
    public function testLoadNotExistingDataMethods()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = $this->factory->loadDataMethods("SomeOtherDataMethodsDoNotExist");
    }

    //Load existing Database class (not DataMapper, DataFinder or DataMethods)
    public function testLoadExistingDatabaseClassNotSpecial()
    {
        $result = $this->factory->load("\\FlyFoundation\\Database\\SomeClass");
        $this->assertInstanceOf("\\TestApp\\Database\\SomeClass",$result);
    }

    //Load not existing Database class (not DataMapper, DataFinder or DataMethods)
    public function testLoadNotExistingDatabaseClassNotSpecial()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = $this->factory->load("\\FlyFoundation\\Database\\SomeNotExistingClass");
    }

    /**
     * exists
     *  - Adds prefix
     *  - Checks if an actual implementation exists
     *  - Checks if entity definition allows for a generic version
     */

    //Existence of an existing DataMapper
    public function testExistsExistingDataMapper()
    {
        $result = $this->factory->dataMapperExists("MyTest");
        $this->assertTrue($result);
    }

    //Existence of GenericDataMapper with entity definition
    public function testExistsDataMapperByNameWithEntityDefinition()
    {
        $result = $this->factory->dataMapperExists("DemoEntity");
        $this->assertTrue($result);
    }

    //Existence of GenericDataMapper without entity definition
    public function testExistsGenericDataMapperByNameWithoutEntityDefinition()
    {
        $result = $this->factory->dataMapperExists("NotExistingEntity");
        $this->assertFalse($result);
    }

    //Existence of xisting DataFinder
    public function testExistsExistingDataFinder()
    {
        $result = $this->factory->dataFinderExists("MyOtherTest");
        $this->assertTrue($result);
    }

    //Existence of GenericDataFinder with entity definition
    public function testExistsGenericDataFinderByNameWithEntityDefinition()
    {
        $result = $this->factory->dataFinderExists("DemoEntity");
        $this->assertTrue($result);
    }

    //Existence of GenericDataFinder without entity definition
    public function testExistsGenericDataFinderByNameWithoutEntityDefinition()
    {
        $result = $this->factory->dataFinderExists("NotExistingEntity");
        $this->assertFalse($result);
    }

    //Existence of existing DataMethods
    public function testExistsExistingDataMethods()
    {
        $result = $this->factory->dataMethodsExists("SomeDataMethods");
        $this->assertTrue($result);
    }

    //Existence of not existing DataMethods
    public function testExistsNotExistingDataMethods()
    {
        $result = $this->factory->dataMethodsExists("SomeOtherDataMethodsDoNotExist");
        $this->assertFalse($result);
    }

    //Existence of existing Database class (not DataMapper, DataFinder or DataMethods)
    public function testExistsExistingDatabaseClassNotSpecial()
    {
        $result = $this->factory->exists("\\FlyFoundation\\Database\\SomeClass");
        $this->assertTrue($result);
    }

    //Existence of not existing Database class (not DataMapper, DataFinder or DataMethods)
    public function testExistsNotExistingDatabaseClassNotSpecial()
    {
        $result = $this->factory->exists("\\FlyFoundation\\Database\\SomeNotExistingClass");
        $this->assertFalse($result);
    }

    /**
     * getGenericDatabaseClassName
     *  - Loads GenericDataFinder or GenericDataMapper
     *  - Error on other stuff
     */

    //Test good cases
    public function testGetGenericDatabaseClassName()
    {
        $res1 = $this->dbFactory->getGenericDatabaseClassName("\\MyApp\\MyScope\\SomeNotExistingDataMapper","DataMapper");
        $res2 = $this->dbFactory->getGenericDatabaseClassName("\\OtherApp\\VirtualDataFinder","DataFinder");
        $this->assertSame("\\FlyFoundation\\Database\\MySqlGenericDataMapper",$res1);
        $this->assertSame("\\FlyFoundation\\Database\\MySqlGenericDataFinder",$res2);
    }

    //Test failing with DataMethods
    public function testGetGenericDatabaseClassNameFail()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = $this->dbFactory->getGenericDatabaseClassName("\\TestApp\\Database\\SomeDataMethods","DataMethods");
    }

    //Test failing with other class
    public function testGetGenericDatabaseClassNameFail2()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = $this->dbFactory->getGenericDatabaseClassName("\\Test\\Scope\\ClassDoesNotExist","WhatMethod");
    }
}
 