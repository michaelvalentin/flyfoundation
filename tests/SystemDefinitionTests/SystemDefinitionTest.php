<?php

use FlyFoundation\SystemDefinitions\EntityDefinition;
use FlyFoundation\SystemDefinitions\SystemDefinition;

require_once __DIR__ . '/../test-init.php';

class SystemDefinitionTest extends PHPUnit_Framework_TestCase {
    /** @var  SystemDefinition */
    protected $systemDef;

    protected function setUp()
    {
        $this->systemDef = new SystemDefinition();
    }

    public function testGetEmptyEntityDefinitions()
    {
        $res = $this->systemDef->getEntityDefinitions();

        $this->assertTrue(is_array($res));
        $this->assertEmpty($res);
    }

    public function testSetGetEntityDefinitions()
    {
        $entities = [
            new EntityDefinition(),
            new EntityDefinition()
        ];
        $entities[0]->setName("Test");
        $entities[1]->setName("Other");
        $resultEntities = [
            $entities[0],
            $entities[1]
        ];

        $this->systemDef->setEntityDefinitions($entities);
        $res = $this->systemDef->getEntityDefinitions();
        $this->assertSame($resultEntities,$res);
    }

    public function testContainsEntityDefinitions()
    {
        $entities = [
            new EntityDefinition(),
            new EntityDefinition()
        ];
        $entities[0]->setName("Test");
        $entities[1]->setName("Other");

        $this->systemDef->setEntityDefinitions($entities);

        $res1 = $this->systemDef->containsEntityDefinition("Test");
        $res2 = $this->systemDef->containsEntityDefinition("Demo");
        $res3 = $this->systemDef->containsEntityDefinition("Other");

        $this->assertTrue($res1);
        $this->assertFalse($res2);
        $this->assertTrue($res3);
    }

    public function testGetEntityDefinition()
    {
        $entities = [
            new EntityDefinition(),
            new EntityDefinition()
        ];
        $entities[0]->setName("Test");
        $entities[1]->setName("Other");

        $this->systemDef->setEntityDefinitions($entities);

        $res = $this->systemDef->getEntityDefinition("Other");
        $this->assertInstanceOf("\\FlyFoundation\\SystemDefinitions\\EntityDefinition",$res);
        $this->assertEquals("Other",$res->getName());
    }

    public function testGetNonExistingEntityDefinition()
    {
        $entities = [
            new EntityDefinition(),
            new EntityDefinition()
        ];
        $entities[0]->setName("Test");
        $entities[1]->setName("Other");

        $this->systemDef->setEntityDefinitions($entities);

        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $res = $this->systemDef->getEntityDefinition("MyEntity");
    }
}
 