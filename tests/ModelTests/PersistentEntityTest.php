<?php

use FlyFoundation\Models\PersistentEntity;
use FlyFoundation\SystemDefinitions\SystemDefinition;

require_once __DIR__.'/../test-init.php';

class PersistentEntityTest extends \PHPUnit_Framework_TestCase {

    /** @var SystemDefinition */
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

    public function testGetDefinition()
    {
        $entity = new PersistentEntity($this->definition);
        $definition = $entity->getDefinition();

        $this->assertEquals($this->definition,$definition);
    }

    public function testGetPersistentData()
    {
        $entity = new PersistentEntity($this->definition,[
            "TestField" => 34,
            "Test" => "Testing"
        ]);

        $data = $entity->getPersistentData();
        $this->assertEquals(34,$data["TestField"]);
        $this->assertSame("Testing",$data["Test"]);
        $this->assertEquals(2,count($data));
    }

    public function testGetPrimaryKey()
    {
        $entity = new PersistentEntity($this->definition,[
            "TestField" => 34,
            "Test" => "Testing",
            "Id" => 5
        ]);

        $data = $entity->getPrimaryKeyValues();
        $this->assertEquals(5,$data["Id"]);
        $this->assertEquals(1,count($data));
    }
}