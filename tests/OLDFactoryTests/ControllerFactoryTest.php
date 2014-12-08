<?php

use FlyFoundation\Core\Context;
use FlyFoundation\Core\Factories\ControllerFactory;
use FlyFoundation\Factory;

require_once __DIR__.'/../test-init.php';


class ControllerFactoryTest extends PHPUnit_Framework_TestCase {
    /** @var  ControllerFactory */
    private $controllerFactory;

    protected function setUp()
    {
        $app = new \FlyFoundation\App();
        $app->addConfigurators(TEST_BASE."/TestApp/configurators");
        $app->prepareCoreDependencies();

        $this->controllerFactory = Factory::load("\\FlyFoundation\\Core\\Factories\\ControllerFactory");

        parent::setUp();
    }

    /**
     * load
     *  - Loads the correct implementation of the specified controller
     *  - If no implementation is found, uses default (GenericEntityController)
     *  - Controller is decorated with model and view of same name if they exist
     */
    //Load existing controller
    public function testLoadControllerThatExists()
    {
        $result = Factory::loadController("MyModel");
        $this->assertInstanceOf("\\TestApp\\Controllers\\MyModelController",$result);

        $model = $result->getModel();

        $view = $result->getView();

        $this->assertInstanceOf("\\TestApp\\Models\\MyModel", $model);
        $this->assertInstanceOf("\\FlyFoundation\\Views\\DefaultView",$view);
    }

    //Load non-existing controller
    public function testLoadControllerThatDoesNotExist()
    {
        $result = Factory::loadController("DemoEntity");
        $this->assertInstanceOf("\\FlyFoundation\\Controllers\\GenericEntityController",$result);

        $model = $result->getModel();

        $view = $result->getView();

        $this->assertInstanceOf("\\FlyFoundation\\Models\\OpenPersistentEntity", $model);
        $this->assertInstanceOf("\\TestApp\\Views\\DemoEntityView",$view);
    }

    //Load controller that doesn't exist and doesn't have controller naming
    public function testLoadingControllerThatDoesNotExistAndDoesNotHaveControllerNaming()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        Factory::load("\\FlyFoundation\\Controllers\\SomeClassNotExists");
    }

    /**
     * exists
     *  - True if:
     *      o The class has controller naming (then it's at least the GenericEntityController)
     *      o The class is explicitly implemented
     */

    //Check existence with existing controller
    public function testExistsControllerWhichExists()
    {
        $result = Factory::controllerExists("DemoEntity");
        $this->assertTrue($result);
    }

    //Check existence with non-existing controller
    public function testExistsControllerWhichExistsButIsNotImplemented()
    {
        $result = Factory::controllerExists("DemoEntity");
        $this->assertTrue($result);
    }

    //Check existence with non controller naming
    public function testExistsControllerWhichDoesNotExistAndHasWrongNaming()
    {
        $result = Factory::exists("\\TestApp\\Controllers\\SomeControllerDoesNotExist");
        $this->assertFalse($result);
    }

    /**
     * getControllerName
     *  - Gets the controller/entity name from a class name and returns false if the name doesn't match the controller standard
     */

    //Name under FlyFoundation
    public function testGetControllerNameWithControllerUnderFlyFoundation()
    {
        $result = $this->controllerFactory->getControllerName("\\FlyFoundation\\Controllers\\MyCrazyClassController");
        $this->assertSame("MyCrazyClass",$result);
    }

    //Name under TestApp
    public function testGetControllerNameWithControllerUnderTestApp()
    {
        $result = $this->controllerFactory->getControllerName("\\TestApp\\Controllers\\SomeDirectory\\AnotherCrazyClassController");
        $this->assertSame("SomeDirectory\\AnotherCrazyClass",$result);
    }

    //Name under undefined controller path
    public function testGetControllerNameWithControllerInNonControllerPath()
    {
        $result = $this->controllerFactory->getControllerName("\\TestApp\\Views\\MyController");
        $this->assertFalse($result);
    }

    //Name that is not a controller name
    public function testGetControllerNameWithNonControllerName()
    {
        $result = $this->controllerFactory->getControllerName("\\FlyFoundation\\SomeRandomClass");
        $this->assertFalse($result);
    }
}
 