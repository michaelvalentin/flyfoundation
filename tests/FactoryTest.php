<?php

use FlyFoundation\Factory;

require_once __DIR__."/../vendor/autoload.php";

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

    protected function setUp()
    {
        $this->factory = new \FlyFoundation\Factory(
            new \FlyFoundation\Config(),
            new \FlyFoundation\Core\Context()
        );
        parent::setUp();
    }

}
 