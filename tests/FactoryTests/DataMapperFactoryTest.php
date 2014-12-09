<?php

use FlyFoundation\Database\GenericDataMapper;
use FlyFoundation\Factory;
use TestApp\Database\DemoEntityDataMapper;
use TestApp\Database\NoLsdEntityDataMapper;

require_once __DIR__ . '/../use-test-app.php';


class DataMapperFactoryTest extends PHPUnit_Framework_TestCase {
    public function testLoadGenericWithLsd()
    {
        $result = Factory::loadDataMapper("NotImplemented");
        $this->assertInstanceOf("\\FlyFoundation\\Database\\GenericDataMapper",$result);
        /** @var GenericDataMapper $result */
        $name = $result->getEntityName();
        $this->assertEquals("NotImplemented",$name);
    }

    public function testLoadGenericWithoutLsd()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = Factory::loadDataMapper("NotExistNoLsd");
    }

    public function testLoadImplementedWithLsd()
    {
        $result = Factory::loadDataMapper("DemoEntity");
        $this->assertInstanceOf("\\TestApp\\Database\\DemoEntityDataMapper",$result);
        /** @var DemoEntityDataMapper $result */
        $name = $result->getEntityName();
        $this->assertEquals("DemoEntity",$name);

        $dataStore = $result->getDataStore();
        $entityToStorage = $result->getEntityToStorageMapping()->asArray();
        $storageToEntity = $result->getStorageToEntityMapping()->asArray();

        $dataStoreEntityName = $dataStore->getEntityName();
        $this->assertEquals("DemoEntity",$dataStoreEntityName);

        $this->assertEquals([
            "MyField" => "myfield",
            "OtherField" => "other_field",
        ],$entityToStorage);

        $this->assertEquals([
            "myfield" => "MyField",
            "other_field" => "OtherField",
        ],$storageToEntity);
    }

    public function testLoadImplementedWithoutLsd()
    {
        $result = Factory::loadDataMapper("NoLsdEntity");
        $this->assertInstanceOf("\\TestApp\\Database\\NoLsdEntityDataMapper",$result);
        /** @var NoLsdEntityDataMapper $result */
        $entityName = $result->getEntityName();
        $this->assertEquals("NoLsdEntity",$entityName);
    }
}
 