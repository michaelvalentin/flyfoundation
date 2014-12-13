<?php

use FlyFoundation\Controllers\AbstractController;
use FlyFoundation\Controllers\Controller;
use FlyFoundation\Core\Context;
use FlyFoundation\Core\Factories\ControllerFactory;
use FlyFoundation\Factory;

require_once __DIR__ . '/../use-test-app.php';


class ControllerFactoryTest extends PHPUnit_Framework_TestCase {
    /** @var  ControllerFactory */
    private $controllerFactory;

    protected function setUp()
    {
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
        /** @var AbstractController $result */
        $result = Factory::loadController("MyModel");
        $this->assertInstanceOf("\\TestApp\\Controllers\\MyModelController",$result);

        $model = $result->getModel();

        $view = $result->getView("List");

        $this->assertInstanceOf("\\TestApp\\Models\\MyModel", $model);
        $this->assertInstanceOf("\\TestApp\\Views\\MyModelListView",$view);
    }

    //Load non-existing controller
    public function testLoadControllerThatDoesNotExist()
    {
        /** @var AbstractController $result */
        $result = Factory::loadController("DemoEntity");
        $this->assertInstanceOf("\\FlyFoundation\\Controllers\\GenericEntityController",$result);

        $this->assertEquals("DemoEntity",$result->getEntityName());

        $model = $result->getModel();

        $view = $result->getView("");

        $this->assertInstanceOf("\\FlyFoundation\\Models\\OpenGenericEntity", $model);
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
}
 