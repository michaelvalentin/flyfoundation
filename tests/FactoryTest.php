<?php

use FlyFoundation\Factory;
use FlyFoundation\Models\OpenPersistentEntity;

require_once __DIR__.'/test-init.php';

class FactoryTest extends PHPUnit_Framework_TestCase {
    /** @var  Factory $factory */
    private $factory;

    public function testFindPartialClassNameInPath()
    {
        $className = "\\MyNamespace\\Folder\\SomeClassFolder\\MyClass";
        $className2 = "\\OtherNamespace\\DemoClass\\Test";
        $className3 = "\\MyOtherNamespace\\Kaki\\Demo";

        $paths = new \FlyFoundation\Util\ValueList([
            "\\MyNamespace\\SomeThing",
            "\\OtherNamespace",
            "\\MyNamespace\\Folder\\SomeClassFolder",
            "\\MyNamespace"
        ]);

        $result = $this->factory->findPartialClassNameInPaths($className,$paths);
        $result2 = $this->factory->findPartialClassNameInPaths($className2, $paths);
        $result3 = $this->factory->findPartialClassNameInPaths($className3, $paths);

        $this->assertSame("MyClass",$result);
        $this->assertSame("DemoClass\\Test",$result2);
        $this->assertFalse($result3);
    }

    public function testGetOverride()
    {
        $config = new \FlyFoundation\Config();
        $config->classOverrides = new \FlyFoundation\Util\Map();
        $config->classOverrides->putAll([
            "\\MyScope\\Test" => "\\NewScope\\Test",
            "\\OtherScope\\Demo\\MyClass" => "\\NewScope\\MyClass",
            "\\NewScope\\MyClass" => "\\DemoScope\\NewClass"
        ]);
        $this->factory->setConfig($config);

        $result1 = $this->factory->getOverride("\\MyScope\\Test");
        $result2 = $this->factory->getOverride("\\OtherScope\\Demo\\MyClass");
        $result3 = $this->factory->getOverride("\\NewScope\\MyClass");
        $result4 = $this->factory->getOverride("\\NewScope\\Testing\\DoesntExist");

        $this->assertSame("\\NewScope\\Test",$result1);
        $this->assertSame("\\DemoScope\\NewClass",$result2);
        $this->assertSame("\\DemoScope\\NewClass",$result3);
        $this->assertSame("\\NewScope\\Testing\\DoesntExist",$result4);
    }

    public function testPrefixActualClassName()
    {
        $name = "\\Test\\Demo\\Something";
        $name2 = "\\MyClass";
        $namePrefixed = $this->factory->prefixActualClassName($name, "Demo");
        $namePrefixed2 = $this->factory->prefixActualClassName($name2, "SAM");
        $this->assertSame("\\Test\\Demo\\DemoSomething",$namePrefixed);
        $this->assertSame("\\SAMMyClass",$namePrefixed2);
    }

    public function testLoadingClassImplementedInTestAppNotImplementedInFlyFoundation(){
        $result = $this->factory->load("\\FlyFoundation\\DemoClass");
        $this->assertInstanceOf("\\TestApp\\DemoClass",$result);
        $this->assertSame(50,$result->test());
    }

    public function testLoadingExistingFlyFoundationClass(){
        $result = $this->factory->load("\\FlyFoundation\\Util\\Set");
        $this->assertInstanceOf("\\FlyFoundation\\Util\\Set",$result);
    }

    public function testLoadingNonExistingModel(){
        /** @var OpenPersistentEntity $result */
        $result = $this->factory->load("\\FlyFoundation\\Models\\MyModel");
        $this->assertInstanceOf("\\FlyFoundation\\Models\\OpenPersistentEntity",$result);
        $def = $result->getDefinition(); //TODO: Inspect the definition to see if it's correct
    }

    public function testLoadExistingModelInTestApp(){
        $result = $this->factory->load("\\FlyFoundation\\Models\\OtherTestModel");
        $this->assertInstanceOf("\\TestApp\\Models\\OtherTestModel",$result);
    }

    public function testLoadingModelInExtraModelPath(){
        $result = $this->factory->load("\\FlyFoundation\\Models\\ThirdTestModel");
        $this->assertInstanceOf("\\TestApp\\ExtraModelPath\\ThirdTestModel",$result);
    }

    protected function setUp()
    {
        $app = new \FlyFoundation\App();
        $app->addConfigurator(__DIR__."/TestApp/configurators");
        $this->factory = $app->getFactory();
        $this->factory->getConfig()->baseSearchPaths->add("\\TestApp");
        parent::setUp();
    }

}
 