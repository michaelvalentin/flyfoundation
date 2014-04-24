<?php

use FlyFoundation\App;
use FlyFoundation\SystemDefinitions\SystemDefinition;

require_once __DIR__.'/test-init.php';


class SystemDefinitionTest extends PHPUnit_Framework_TestCase {
    /** @var  SystemDefinition */
    private $definition;

    protected function setUp()
    {
        $app = new App();
        $app->addConfigurators(__DIR__."/TestApp/configurators");
        $this->definition = $app->getFactory()->load("\\FlyFoundation\\SystemDefinitions\\SystemDefinition");
    }

    public function testName()
    {
        $this->definition->applyOptions([
            "name" => "testDefinition"
        ]);
        $this->definition->finalize();

        $result = $this->definition->getName();
        $this->assertSame("testDefinition",$result);
    }

    public function testNoNameException()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->definition->applyOptions([]);
        $this->definition->finalize();
    }

    public function testEntities()
    {
        $this->definition->applyOptions([
            "name" => "testDefinition",
            "entities" => [
                [
                    "name" => "testEntity",
                    "fields" => [
                        [
                            "name" => "testField",
                            "type" => "string"
                        ]
                     ]

                ],
                [
                    "name" => "testEntity2",
                    "fields" => [
                        [
                            "name" => "testField",
                            "type" => "string"
                        ]
                    ]
                ],
            ]
        ]);
        $this->definition->finalize();

        $result = $this->definition->getEntities();
        foreach($result as $r){
            $this->assertInstanceOf("\\FlyFoundation\\SystemDefinitions\\EntityDefinition",$r);
        }
        $resultCount = count($result);
        $this->assertEquals(2,$resultCount);

        $entity = $this->definition->getEntity("testEntity");
        $result2 = $entity->getName();

        $this->assertSame("testEntity",$result2);
    }
}
 