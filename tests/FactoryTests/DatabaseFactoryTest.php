<?php

require_once __DIR__ . '/../use-test-app.php';

use FlyFoundation\Core\Context;
use FlyFoundation\Core\Factories\DatabaseFactory;
use FlyFoundation\Factory;

class DatabaseFactoryTest extends PHPUnit_Framework_TestCase {
    /** @var DatabaseFactory $dbFactory */
    private $dbFactory;

    protected function setUp()
    {
        $this->dbFactory = Factory::load("\\FlyFoundation\\Core\\Factories\\DatabaseFactory");
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
        $result = Factory::loadDataMapper("MyTest");
        $this->assertInstanceOf("\\TestApp\\Database\\MyTestDataMapper",$result);
    }

    //Load GenericDataMapper with entity definition
    public function testLoadGenericDataMapperByNameWithEntityDefinition()
    {
        $result = Factory::loadDataMapper("DemoEntity");
        $this->assertInstanceOf("\\FlyFoundation\\Database\\GenericDataMapper",$result);
    }

    //Load GenericDataMapper without entity definition
    public function testLoadGenericDataMapperByNameWithoutEntityDefinition()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = Factory::loadDataMapper("NotExistingEntity");
    }

    //Load existing DataFinder
    public function testLoadExistingDataFinder()
    {
        $result = Factory::loadDataFinder("MyOtherTest");
        $this->assertInstanceOf("\\TestApp\\Database\\MyOtherTestDataFinder",$result);
    }

    //Load GenericDataFinder with entity definition
    public function testLoadGenericDataFinderByNameWithEntityDefinition()
    {
        $result = Factory::loadDataFinder("DemoEntity");
        $this->assertInstanceOf("\\FlyFoundation\\Database\\GenericDataFinder",$result);
    }

    //Load GenericDataFinder without entity definition
    public function testLoadGenericDataFinderByNameWithoutEntityDefinition()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = Factory::loadDataFinder("NotExistingEntity");
    }

    //Load existing DataMethods
    public function testLoadExistingDataMethods()
    {
        $result = Factory::loadDataMethods("Some");
        $this->assertInstanceOf("\\TestApp\\Database\\MySqlSomeDataMethods",$result);
    }

    //Load not existing DataMethods
    public function testLoadNotExistingDataMethods()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = Factory::loadDataMethods("SomeOtherDataMethodsDoNotExist");
    }

    //Load existing Database class (not DataMapper, DataFinder or DataMethods)
    public function testLoadExistingDatabaseClassNotSpecial()
    {
        $result = Factory::load("\\FlyFoundation\\Database\\SomeClass");
        $this->assertInstanceOf("\\TestApp\\Database\\SomeClass",$result);
    }

    //Load not existing Database class (not DataMapper, DataFinder or DataMethods)
    public function testLoadNotExistingDatabaseClassNotSpecial()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = Factory::load("\\FlyFoundation\\Database\\SomeNotExistingClass");
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
        $result = Factory::dataMapperExists("MyTest");
        $this->assertTrue($result);
    }

    //Existence of GenericDataMapper with entity definition
    public function testExistsDataMapperByNameWithEntityDefinition()
    {
        $result = Factory::dataMapperExists("DemoEntity");
        $this->assertTrue($result);
    }

    //Existence of GenericDataMapper without entity definition
    public function testExistsGenericDataMapperByNameWithoutEntityDefinition()
    {
        $result = Factory::dataMapperExists("NotExistingEntity");
        $this->assertFalse($result);
    }

    //Existence of xisting DataFinder
    public function testExistsExistingDataFinder()
    {
        $result = Factory::dataFinderExists("MyOtherTest");
        $this->assertTrue($result);
    }

    //Existence of GenericDataFinder with entity definition
    public function testExistsGenericDataFinderByNameWithEntityDefinition()
    {
        $result = Factory::dataFinderExists("DemoEntity");
        $this->assertTrue($result);
    }

    //Existence of GenericDataFinder without entity definition
    public function testExistsGenericDataFinderByNameWithoutEntityDefinition()
    {
        $result = Factory::dataFinderExists("NotExistingEntity");
        $this->assertFalse($result);
    }

    //Existence of existing DataMethods
    public function testExistsExistingDataMethods()
    {
        $result = Factory::dataMethodsExists("Some");
        $this->assertTrue($result);
    }

    //Existence of not existing DataMethods
    public function testExistsNotExistingDataMethods()
    {
        $result = Factory::dataMethodsExists("SomeOtherDoNotExist");
        $this->assertFalse($result);
    }

    //Existence of existing Database class (not DataMapper, DataFinder or DataMethods)
    public function testExistsExistingDatabaseClassNotSpecial()
    {
        $result = Factory::exists("\\FlyFoundation\\Database\\SomeClass");
        $this->assertTrue($result);
    }

    //Existence of not existing Database class (not DataMapper, DataFinder or DataMethods)
    public function testExistsNotExistingDatabaseClassNotSpecial()
    {
        $result = Factory::exists("\\FlyFoundation\\Database\\SomeNotExistingClass");
        $this->assertFalse($result);
    }
}
 