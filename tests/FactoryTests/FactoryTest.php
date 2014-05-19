<?php

use FlyFoundation\Core\Context;
use FlyFoundation\Factory;
use TestApp\SomeClass;
use TestApp\SomeOtherClass;

require_once __DIR__ . '/../test-init.php';

class FactoryTest extends PHPUnit_Framework_TestCase {

    protected function setUp()
    {
        $app = new \FlyFoundation\App();
        $app->addConfigurators(TEST_BASE."/TestApp/configurators");
        $app->prepareCoreDependencies("testing",new Context());
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
        $result = Factory::load("\\FlyFoundation\\Util\\Set");
        $this->assertInstanceOf("\\FlyFoundation\\Util\\Set",$result);
    }

    //Load class in TestApp and FlyFoundation
    public function testLoadClassInTestAppAndInFlyFoundation()
    {
        $result = Factory::Load("\\FlyFoundation\\Util\\ClassMap");
        $this->assertInstanceOf("\\FlyFoundation\\Util\\ClassMap",$result);
    }

    //Load class that does not exist in either
    public function testLoadClassThatDoesNotExist()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = Factory::load("\\TestApp\\NotExistingClass");
    }

    //Load existing class that uses environment
    public function testLoadClassThatUsesAllCoreDependencyTraits()
    {
        /** @var SomeClass $result */
        $result = Factory::load("\\TestApp\\SomeClass");
        $config = $result->getAppConfig();
        $context = $result->getAppContext();
        $appDefinition = $result->getAppDefinition();

        $this->assertInstanceOf("\\FlyFoundation\\Config",$config);
        $this->assertInstanceOf("\\FlyFoundation\\Core\\Context",$context);
        $this->assertInstanceOf("\\FlyFoundation\\SystemDefinitions\\SystemDefinition",$appDefinition);
    }

    public function testLoadClassTheUsesOneCoreDependencyTrait()
    {
        /** @var SomeOtherClass $result */
        $result = Factory::load("\\TestApp\\SomeOtherClass");
        $config = $result->getAppConfig();

        $this->assertInstanceOf("\\FlyFoundation\\Config",$config);
        $this->assertFalse(method_exists($result,"getAppContext"));
        $this->assertFalse(method_exists($result,"getAppDefinition"));
    }

    //Load existing class that does not use environment
    public function testLoadClassNotUsingEnvironment()
    {
        $result = Factory::load("\\TestApp\\DemoClass");
        $this->assertFalse(method_exists($result,"getAppContext"));
        $this->assertFalse(method_exists($result,"getAppDefinition"));
        $this->assertFalse(method_exists($result, "getAppConfig"));
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
        $result = Factory::exists("\\TestApp\\DemoClass");
        $this->assertTrue($result);
    }

    //Test with a class that does not exist
    public function testExistsWithClassThatDoesNotExist()
    {
        $result = Factory::exists("\\TestApp\\NonExistingClass");
        $this->assertFalse($result);
    }

    /**
     * loadWithoutOverridesAndDecoration
     *  - Loads the class
     *  - Adds relevant environment variables
     */

    //Load class that uses environment
    public function testLoadAndDecorateWithoutSpecialization()
    {
        /** @var SomeClass $result */
        $result = Factory::loadAndDecorateWithoutSpecialization("\\TestApp\\SomeClass",[]);
        $config = $result->getAppConfig();
        $context = $result->getAppContext();
        $appDefinition = $result->getAppDefinition();

        $this->assertInstanceOf("\\FlyFoundation\\Config",$config);
        $this->assertInstanceOf("\\FlyFoundation\\Core\\Context",$context);
        $this->assertInstanceOf("\\FlyFoundation\\SystemDefinitions\\SystemDefinition",$appDefinition);
    }
}

