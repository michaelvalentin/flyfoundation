<?php

use FlyFoundation\Database\JsonGenericDataStore;
use FlyFoundation\Database\MySqlGenericDataStore;
use FlyFoundation\Factory;

require_once __DIR__ . '/../use-test-app.php';

class DataStoreFactoryTest extends PHPUnit_Framework_TestCase {

    public function testLoadingLsdDefinedNonExistingDataStore()
    {
        $result = Factory::loadDataStore("NotImplemented");
        $this->assertInstanceOf("\\FlyFoundation\\Database\\MySqlGenericDataStore",$result);
        /** @var MySqlGenericDataStore $result */
        $entityName = $result->getEntityName();
        $storageName = $result->getStorageName();

        $this->assertEquals("NotImplemented",$entityName);
        $this->assertEquals("not_implemented", $storageName);
    }

    public function testLoadingNonExistingDataStore()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");

        $result = Factory::loadDataStore("DoesNotExistAndNoLsd");
    }

    public function testLoadingTestAppDataStoreWithLsd()
    {
        $result = Factory::loadDataStore("DemoEntity");
        $this->assertInstanceOf("\\TestApp\\Database\\MySqlDemoEntityDataStore",$result);
        /** @var MySqlGenericDataStore $result */
        $entityName = $result->getEntityName();
        $storageName = $result->getStorageName();

        $this->assertEquals("DemoEntity",$entityName);
        $this->assertEquals("demo_entity", $storageName);
    }

    public function testLoadingTestAppDataStoreWithoutLsd()
    {
        $result = Factory::loadDataStore("NoLsdEntity");
        $this->assertInstanceOf("\\TestApp\\Database\\MySqlNoLsdEntityDataStore",$result);
        /** @var MySqlGenericDataStore $result */
        $entityName = $result->getEntityName();
        $storageName = $result->getStorageName();

        $this->assertEquals("NoLsdEntity",$entityName);
        $this->assertEquals("no_lsd_entity", $storageName);
    }

    public function testLoadingJsonDataStore()
    {
        $result = Factory::loadDataStore("JsonBasedModel");
        $this->assertInstanceOf("\\FlyFoundation\\Database\\JsonGenericDataStore",$result);

        /** @var JsonGenericDataStore $result */
        $entityName = $result->getEntityName();
        $storageName = $result->getStorageName();

        $this->assertEquals("JsonBasedModel",$entityName);
        $this->assertEquals("my-json-file", $storageName);
    }
}
 