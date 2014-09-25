<?php

use FlyFoundation\Models\OpenPersistentEntity;
use FlyFoundation\SystemDefinitions\EntityDefinition;

require_once __DIR__.'/../test-init.php';


class OpenPersistentEntityTest extends PHPUnit_Framework_TestCase {

    /** @var EntityDefinition */
    private $definition;

    protected function setUp()
    {
        $definition = new \FlyFoundation\SystemDefinitions\EntityDefinition();
        $definition->applyOptions([
            "name" => "DemoEntity",
            "fields" => [
                [
                    "name" => "Id",
                    "type" => "integer",
                    "primaryKey" => true
                ],
                [
                    "name" => "TestField",
                    "type" => "integer"
                ],
                [
                    "name" => "Test",
                    "type" => "string"
                ],
            ]
        ]);
        $definition->finalize();
        $this->definition = $definition;

        parent::setUp();
    }

    public function testSetup()
    {
        $this->assertTrue(true);
    }

    public function testGet()
    {
        $entity = new OpenPersistentEntity($this->definition,[
            "TestField" => 25,
            "Test" => "Test in progress!",
            "Id" => 53
        ]);

        $result = $entity->get("TestField");
        $result2 = $entity->get("Test");
        $result3 = $entity->get("Id");

        $this->assertEquals(25,$result);
        $this->assertSame("Test in progress!",$result2);
        $this->assertEquals(53, $result3);
    }

    public function testGetUnset()
    {
        $entity = new OpenPersistentEntity($this->definition,[
            "TestField" => 25,
            "Id" => 53
        ]);

        $result = $entity->get("Test");
        $this->assertNull($result);
    }

    public function testSet()
    {
        $entity = new OpenPersistentEntity($this->definition,[
            "TestField" => 25,
            "Id" => 53
        ]);

        $entity->set("TestField",43);
        $result = $entity->get("TestField");
        $this->assertEquals(43, $result);

        $entity->set("Test","String here");
        $expectedData = [
            "TestField" => 43,
            "Id" => 53,
            "Test" => "String here"
        ];
        $data = $entity->getPersistentData();

        $this->assertEmpty(array_diff($expectedData,$data));
    }

    public function testSetInvalidType()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $entity = new OpenPersistentEntity($this->definition);
        $entity->set("TestField", "NotInteger");
    }
}
 