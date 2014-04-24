<?php

use FlyFoundation\App;
use FlyFoundation\SystemDefinitions\EntityDefinition;

require_once __DIR__.'/test-init.php';


class EntityDefinitionTest extends PHPUnit_Framework_TestCase {
    /** @var  EntityDefinition */
    private $definition;

    protected function setUp()
    {
        $app = new App();
        $app->addConfigurators(__DIR__."/TestApp/configurators");
        $this->definition = $app->getFactory()->load("\\FlyFoundation\\SystemDefinitions\\EntityDefinition");
        parent::setUp();
    }

    public function testConstructionOfValidDefinition()
    {
        $this->definition->applyOptions([
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
        $this->definition->applyOptions([
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
        $this->definition->applyOptions([
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
        $this->definition->applyOptions([
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
        $this->definition->applyOptions([
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
        $result1 = $this->definition->getSetting("mySetting");
        $result2 = $this->definition->getSetting("myOtherSetting");
        $result3 = $this->definition->getSetting("doesNotExist");

        $this->assertSame("myValue", $result1);
        $this->assertSame("otherValue", $result2);
        $this->assertFalse($result3);
    }
}
 