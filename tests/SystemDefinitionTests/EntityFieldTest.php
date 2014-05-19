<?php

use FlyFoundation\App;
use FlyFoundation\SystemDefinitions\SystemDefinition;

require_once __DIR__ . '/../test-init.php';


class EntityFieldTest extends PHPUnit_Framework_TestCase {
    /** @var  SystemDefinition */
    private $definition;

    protected function setUp()
    {
        $app = new App();
        $app->addConfigurators(TEST_BASE."/TestApp/configurators");
        $this->definition = $app->getFactory()->load("\\FlyFoundation\\SystemDefinitions\\SystemDefinition");
        parent::setUp();
    }

    public function addFieldsInDefinition($options)
    {
        $options = [
            "name" => "Testing Definition",
            "entities" => [
                [
                    "name" => "TestEntity",
                    "fields" => $options
                ]
            ]
        ];
        $this->definition->applyOptions($options);
    }

    public function testSimpleField()
    {
        $this->addFieldsInDefinition([
            [
                "name" => "TestField",
                "type" => "string"
            ]
        ]);
        $this->definition->finalize();
        $this->assertTrue(true);
    }

    public function testInvalidType()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->addFieldsInDefinition([
            [
                "name" => "TestField",
                "type" => "funny-string"
            ]
        ]);
        $this->definition->finalize();
    }

    public function testExternalType()
    {
        $this->definition->applyOptions([
            "name" => "Testing Definition",
            "entities" => [
                [
                    "name" => "TestEntity",
                    "fields" => [
                        [
                            "name" => "Test",
                            "type" => "DemoEntity"
                        ]
                    ]
                ],
                [
                    "name" => "DemoEntity",
                    "fields" => [
                        [
                            "name" => "Test",
                            "type" => "string"
                        ]
                    ]
                ]
            ]
        ]);
        $this->definition->finalize();
        $this->assertTrue(true);
    }

    public function testNonExistingExternalType()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->definition->applyOptions([
            "name" => "Testing Definition",
            "entities" => [
                [
                    "name" => "TestEntity",
                    "fields" => [
                        [
                            "name" => "Test",
                            "type" => "NonExistingEntity"
                        ]
                    ]
                ],
                [
                    "name" => "DemoEntity",
                    "fields" => [
                        [
                            "name" => "Test",
                            "type" => "TestEntity"
                        ]
                    ]
                ]
            ]
        ]);
        $this->definition->finalize();
    }

    public function testExternalTypeSelfReference()
    {
        $this->definition->applyOptions([
            "name" => "Testing Definition",
            "entities" => [
                [
                    "name" => "TestEntity",
                    "fields" => [
                        [
                            "name" => "Test",
                            "type" => "TestEntity"
                        ]
                    ]
                ],
                [
                    "name" => "DemoEntity",
                    "fields" => [
                        [
                            "name" => "Test",
                            "type" => "DemoEntity"
                        ]
                    ]
                ]
            ]
        ]);
        $this->definition->finalize();
        $this->assertTrue(true);
    }

    public function testExternalTypeCircleReference()
    {
        $this->definition->applyOptions([
            "name" => "Testing Definition",
            "entities" => [
                [
                    "name" => "TestEntity",
                    "fields" => [
                        [
                            "name" => "Test",
                            "type" => "DemoEntity"
                        ]
                    ]
                ],
                [
                    "name" => "DemoEntity",
                    "fields" => [
                        [
                            "name" => "Test",
                            "type" => "TestEntity"
                        ]
                    ]
                ]
            ]
        ]);
        $this->definition->finalize();
        $this->assertTrue(true);
    }

    public function testIsInPrimaryKeyOne()
    {
        $this->definition->applyOptions([
            "name" => "Testing Definition",
            "entities" => [
                [
                    "name" => "TestEntity",
                    "fields" => [
                        [
                            "name" => "Test",
                            "type" => "integer",
                            "PrimaryKey" => true
                        ],
                        [
                            "name" => "OtherTest",
                            "type" => "string",
                        ]
                    ]
                ]
            ]
        ]);
        $this->definition->finalize();
        $result1 = $this->definition->getEntity("TestEntity")->getField("Test")->isInPrimaryKey();
        $this->assertTrue($result1);
        $result2 = $this->definition->getEntity("TestEntity")->getField("OtherTest")->isInPrimaryKey();
        $this->assertFalse($result2);
    }

    public function testIsPrimaryKeyTwo()
    {
        $this->definition->applyOptions([
            "name" => "Testing Definition",
            "entities" => [
                [
                    "name" => "TestEntity",
                    "fields" => [
                        [
                            "name" => "Test",
                            "type" => "integer",
                            "PrimaryKey" => true
                        ],
                        [
                            "name" => "OtherTest",
                            "type" => "string",
                            "PrimaryKey" => true
                        ]
                    ]
                ]
            ]
        ]);
        $this->definition->finalize();
        $result1 = $this->definition->getEntity("TestEntity")->getField("Test")->isInPrimaryKey();
        $this->assertTrue($result1);
        $result2 = $this->definition->getEntity("TestEntity")->getField("OtherTest")->isInPrimaryKey();
        $this->assertTrue($result2);
    }
}