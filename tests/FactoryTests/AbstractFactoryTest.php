<?php

use FlyFoundation\Models\OpenPersistentEntity;
use FlyFoundation\Controllers\Controller;
use FlyFoundation\Controllers\GenericEntityController;
use FlyFoundation\Factory;

require_once __DIR__ . '/../test-init.php';

class AbstractFactoryTest extends PHPUnit_Framework_TestCase {
    /** @var  Factory $factory */
    private $factory;

    protected function setUp()
    {
        $app = new \FlyFoundation\App();
        $app->addConfigurators(TEST_BASE."/TestApp/configurators");
        $context = new \FlyFoundation\Core\Context();
        $this->factory = $app->getFactory($context);
        $this->factory->getConfig()->baseSearchPaths->add("\\TestApp");
        parent::setUp();
    }

    public function testGetOverride()
    {
        $config = new \FlyFoundation\Config();
        $config->classOverrides = new \FlyFoundation\Util\Map();
        $config->classOverrides->putAll([
            "\\NewScope\\MyClass" => "\\DemoScope\\NewClass",
            "\\OtherScope\\Demo\\MyClass" => "\\NewScope\\MyClass",
            "\\MyScope\\Test" => "\\NewScope\\Test",
        ]);
        $this->factory->setConfig($config);

        $result1 = $this->factory->getOverride("\\MyScope\\Test");
        $result2 = $this->factory->getOverride("\\OtherScope\\Demo\\MyClass");
        $result3 = $this->factory->getOverride("\\NewScope\\MyClass");
        $result4 = $this->factory->getOverride("\\NewScope\\Testing\\DoesntExist");

        $this->assertSame("\\NewScope\\Test",$result1);
        $this->assertSame("\\DemoScope\\NewClass",$result2);
        $this->assertSame("\\DemoScope\\NewClass",$result3);
        $this->assertFalse($result4);
    }

    public function testGetOverrideCircular()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidConfigurationException");

        $config = new \FlyFoundation\Config();
        $config->classOverrides = new \FlyFoundation\Util\Map();
        $config->classOverrides->putAll([
            "\\NewScope\\MyClass" => "\\DemoScope\\NewClass",
            "\\DemoScope\\NewClass" => "\\MyScope\\Test",
            "\\MyScope\\Test" => "\\NewScope\\MyClass",
        ]);
        $this->factory->setConfig($config);

        $result = $this->factory->getOverride("\\NewScope\\MyClass");
    }

    public function testFindPartialClassNameInPath()
    {
        $className = "\\MyNamespace\\Folder\\SomeClassFolder\\MyClass";
        $className2 = "\\OtherNamespace\\DemoClass\\Test";
        $className3 = "\\MyOtherNamespace\\Kaki\\Demo";

        $paths = new \FlyFoundation\Util\ValueList([
            "\\MyNamespace\\SomeThing",
            "\\OtherNamespace",
            "\\MyNamespace\\Folder\\SomeClassFolder",
            "\\MyNamespace",
        ]);

        $result = $this->factory->findPartialClassNameInPaths($className,$paths);
        $result2 = $this->factory->findPartialClassNameInPaths($className2, $paths);
        $result3 = $this->factory->findPartialClassNameInPaths($className3, $paths);

        $this->assertSame("MyClass",$result);
        $this->assertSame("DemoClass\\Test",$result2);
        $this->assertFalse($result3);
    }

    public function testFindImplementation()
    {
        $className = "\\MyNamespace\\Folder\\SomeClassFolder\\MyClass";
        $className2 = "\\OtherNamespace\\DemoClass\\Test";
        $className3 = "\\MyOtherNamespace\\Kaki\\Demo";
        $className4 = "\\OtherNamespace\\DemoClass";
        $className5 = "\\TestApp\\DemoClass";

        $paths = new \FlyFoundation\Util\ValueList([
            "\\MyNamespace\\SomeThing",
            "\\OtherNamespace",
            "\\MyNamespace\\Folder\\SomeClassFolder",
            "\\MyNamespace",
            "\\TestApp"
        ]);

        $result = $this->factory->findImplementation($className,$paths);
        $result2 = $this->factory->findImplementation($className2, $paths);
        $result3 = $this->factory->findImplementation($className3, $paths);
        $result4 = $this->factory->findImplementation($className4, $paths);
        $result5 = $this->factory->findImplementation($className5, $paths);

        $this->assertFalse($result);
        $this->assertFalse($result2);
        $this->assertFalse($result3);
        $this->assertSame("\\TestApp\\DemoClass",$result4);
        $this->assertSame("\\TestApp\\DemoClass",$result5);
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

}

