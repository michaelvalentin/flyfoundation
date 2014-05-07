<?php

use FlyFoundation\Factory;
use TestApp\SomeClass;

require_once __DIR__ . '/../test-init.php';

class FactoryTest extends PHPUnit_Framework_TestCase {
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

    /**
     * load
     *  - Finds the relevant implementation
     *      o Overwrites
     *      o Implementation in base paths
     *  - Calls the specialized factory if that is relevant
     *      o (TESTED IN SPECIALIZED FACTORY TESTS)
     *  - Ensures that the output is an instance of the called class
     */

    //Load class in FlyFoundation
    public function testLoadClassThatExistsInFlyFoundation()
    {
        $result = $this->factory->load("\\FlyFoundation\\Util\\Set");
        $this->assertInstanceOf("\\FlyFoundation\\Util\\Set",$result);
    }

    //Load class that is overridden
    public function testLoadOverriddenClass()
    {
        $result = $this->factory->load("\\FlyFoundation\\FooBarClass");
        $this->assertInstanceOf("\\TestApp\\DemoClass",$result);
    }

    //Load class in TestApp not in FlyFoundation
    public function testLoadClassInTestAppNotInFlyFoundation()
    {
        $result = $this->factory->load("\\FlyFoundation\\DemoClass");
        $this->assertInstanceOf("\\TestApp\\DemoClass",$result);
        $this->assertSame(50,$result->test());
    }

    //Load class in TestApp and FlyFoundation
    public function testLoadClassInTestAppAndInFlyFoundation()
    {
        $result = $this->factory->load("\\FlyFoundation\\Util\\ClassMap");
        $this->assertInstanceOf("\\TestApp\\Util\\ClassMap",$result);
    }

    //Load class that does not exist in either
    public function testLoadClassThatDoesNotExist()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = $this->factory->load("\\TestApp\\NotExistingClass");
    }

    //Load existing class that uses environment
    public function testLoadClassThatUsesEnvironment()
    {
        /** @var SomeClass $result */
        $this->factory->getConfig()->classOverrides->clear();
        $result = $this->factory->load("\\TestApp\\SomeClass");
        $config = $result->getConfig();
        $factory = $result->getFactory();
        $context = $result->getContext();
        $appDefinition = $result->getAppDefinition();

        $this->assertInstanceOf("\\FlyFoundation\\Config",$config);
        $this->assertInstanceOf("\\FlyFoundation\\Factory", $factory);
        $this->assertInstanceOf("\\FlyFoundation\\Core\\Context",$context);
        $this->assertInstanceOf("\\FlyFoundation\\SystemDefinitions\\SystemDefinition",$appDefinition);
    }

    //Load existing class that does not use environment
    public function testLoadClassNotUsingEnvironment()
    {
        $result = $this->factory->load("\\TestApp\\DemoClass");
        $hasMethod = method_exists($result, "getConfig");
        $this->assertFalse($hasMethod);
    }

    //Load class that is overwritten and does not implement the original class interface
    public function testLoadClassOverrideNotImplementingOriginal()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidClassException");
        $result = $this->factory->load("\\TestApp\\SomeClass");
    }

    /**
     * exists
     *  - Finds the relevant implementation
     *  - Finds specialized factory if relevant
     *      o (TESTED IN SPECIALIZED FACTORY TESTS)
     *  - Checks if class exists
     */

    //Test with a class that exists as it is
    public function testExistsWithExcistingClass()
    {
        $result = $this->factory->exists("\\TestApp\\DemoClass");
        $this->assertTrue($result);
    }

    //Test with a class that exists through override
    public function testExistsWithClassExistingThroughOverride()
    {
        $result = $this->factory->exists("\\FlyFoundation\\FooBarClass");
        $this->assertTrue($result);
    }

    //Test with a class that exists by implementation
    public function testExistsWithClassExistingThroughImplementation()
    {
        $result = $this->factory->exists("\\FlyFoundation\\DemoClass");
        $this->assertTrue($result);
    }

    //Test with a class that does not exist
    public function testExistsWithClassThatDoesNotExist()
    {
        $result = $this->factory->exists("\\TestApp\\NonExistingClass");
        $this->assertFalse($result);
    }

    /**
     * loadWithoutOverridesAndDecoration
     *  - Loads the class
     *  - Adds relevant environment variables
     */

    //Load class that uses environment
    public function testLoadWithoutOverridesAndDecorationForClassThatUsesEnvironment()
    {
        /** @var SomeClass $result */
        $this->factory->getConfig()->classOverrides->clear();
        $result = $this->factory->loadWithoutOverridesAndDecoration("\\TestApp\\SomeClass",[]);
        $config = $result->getConfig();
        $factory = $result->getFactory();
        $context = $result->getContext();
        $appDefinition = $result->getAppDefinition();

        $this->assertInstanceOf("\\FlyFoundation\\Config",$config);
        $this->assertInstanceOf("\\FlyFoundation\\Factory", $factory);
        $this->assertInstanceOf("\\FlyFoundation\\Core\\Context",$context);
        $this->assertInstanceOf("\\FlyFoundation\\SystemDefinitions\\SystemDefinition",$appDefinition);
    }
}

