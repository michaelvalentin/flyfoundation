<?php

use FlyFoundation\Core\Factories\FactoryTools;
use FlyFoundation\Models\OpenPersistentEntity;
use FlyFoundation\Controllers\Controller;
use FlyFoundation\Controllers\GenericEntityController;
use FlyFoundation\Factory;

require_once __DIR__ . '/../test-init.php';

class FactoryToolsTest extends PHPUnit_Framework_TestCase {

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

        $result = FactoryTools::findPartialClassNameInPaths($className,$paths);
        $result2 = FactoryTools::findPartialClassNameInPaths($className2, $paths);
        $result3 = FactoryTools::findPartialClassNameInPaths($className3, $paths);

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

        $result = FactoryTools::findImplementation($className,$paths);
        $result2 = FactoryTools::findImplementation($className2, $paths);
        $result3 = FactoryTools::findImplementation($className3, $paths);
        $result4 = FactoryTools::findImplementation($className4, $paths);
        $result5 = FactoryTools::findImplementation($className5, $paths);

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
        $namePrefixed = FactoryTools::prefixActualClassName($name, "Demo");
        $namePrefixed2 = FactoryTools::prefixActualClassName($name2, "SAM");
        $this->assertSame("\\Test\\Demo\\DemoSomething",$namePrefixed);
        $this->assertSame("\\SAMMyClass",$namePrefixed2);
    }

}

