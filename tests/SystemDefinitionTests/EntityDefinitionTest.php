<?php

use FlyFoundation\App;
use FlyFoundation\SystemDefinitions\EntityDefinition;
use FlyFoundation\SystemDefinitions\SystemDefinition;

require_once __DIR__ . '/../test-init.php';


class EntityDefinitionTest extends PHPUnit_Framework_TestCase {
    /** @var  SystemDefinition */
    private $definition;

    protected function setUp()
    {
        $app = new App();
        $app->addConfigurators(TEST_BASE."/TestApp/configurators");
        $this->definition = $app->getFactory()->load("\\FlyFoundation\\SystemDefinitions\\SystemDefinition");
        parent::setUp();
    }

    public function applySingleEntityOptions(array $options)
    {
        $options = [
            "name" => "Testing Definintion",
            "entities" => [
                $options
            ]
        ];
        $this->definition->applyOptions($options);
    }

    public function testConstructionOfValidDefinition()
    {
        $this->applySingleEntityOptions([
            "name" => "DemoEntity",
            "fields" => [
                [
                    "name" => "testTime",
                    "type" => "DateTime",
                    "defaultValue" => "2010-01-01 00:00:00",
                    "autoIncrement" => "0"
                ]
            ]
        ]);
        $this->definition->finalize();
        $this->assertTrue(true);
    }

    public function testConstructIncompleteDefinition()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->applySingleEntityOptions([
            "name" => "DemoEntity",
            "fields" => [
                [
                    "type" => "DateTime",
                    "defaultValue" => "2010-01-01 00:00:00",
                    "autoIncrement" => "0"
                ]
            ]
        ]);
        $this->definition->finalize();
    }

    public function testConstructIncompleteDefinition2()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->applySingleEntityOptions([
            "name" => "DemoEntity",
            "fields" => [
                [
                    "name" => "testTime",
                    "defaultValue" => "2010-01-01 00:00:00",
                    "autoIncrement" => "0"
                ]
            ]
        ]);
        $this->definition->finalize();
    }

    public function testConstructInvalidDefinition()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->applySingleEntityOptions([
            "name" => "DemoEntity",
            "fields" => [
                [
                    "type" => "TypeDoesNotExist",
                    "name" => "testTime",
                    "defaultValue" => "2010-01-01 00:00:00",
                    "autoIncrement" => "0"
                ]
            ]
        ]);
        $this->definition->finalize();
    }

    public function testSettings()
    {
        $this->applySingleEntityOptions([
            "name" => "DemoEntity",
            "fields" => [
                [
                    "type" => "string",
                    "name" => "testField"
                ],
                [
                    "type" => "string",
                    "name" => "testField2"
                ],
            ],
            "settings" => [
                "mySetting" => "myValue",
                "myOtherSetting" => "otherValue"
            ]
        ]);
        $this->definition->finalize();
        $entity = $this->definition->getEntity("DemoEntity");
        $result1 = $entity->getSetting("mySetting");
        $result2 = $entity->getSetting("myOtherSetting");
        $result3 = $entity->getSetting("doesNotExist", false);
        $result4 = $entity->getSetting("doesNotExist");
        $result5 = $entity->getSetting("doesNotExist","Demo");

        $this->assertSame("myValue", $result1);
        $this->assertSame("otherValue", $result2);
        $this->assertFalse($result3);
        $this->assertNull($result4);
        $this->assertSame("Demo",$result5);
    }
}
 