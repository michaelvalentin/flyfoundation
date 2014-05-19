<?php

use FlyFoundation\Models\PersistentEntity;

require_once __DIR__.'/../test-init.php';

class PersistentEntityTest extends \PHPUnit_Framework_TestCase {

    /** @var PersistentEntity */
    private $entity;

    protected function setUp()
    {
        $definition = new \FlyFoundation\SystemDefinitions\EntityDefinition();
        $definition->applyOptions([
            "name" => "DemoEntity",
            "fields" => [
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
        $entity = new PersistentEntity($definition, []);
        $this->entity = $entity;
        parent::setUp();
    }

    public function testSetup()
    {
        $this->assertTrue(true);
    }
}
 