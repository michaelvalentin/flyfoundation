<?php

use FlyFoundation\Core\Context;
use FlyFoundation\Factory;

require_once __DIR__ . '/../use-test-app.php';


class ViewFactoryTest extends PHPUnit_Framework_TestCase {

    //Load existing view
    public function testLoadViewThatExists()
    {
        $result = Factory::loadView("Demo");
        $this->assertInstanceOf("\\TestApp\\Views\\DemoView",$result);
    }

    //Load not existing view with LSD-definition and view naming
    public function testLoadViewThatDoesNotExistButHasLsdAndViewNaming()
    {
        $result = Factory::loadView("NotImplemented");
        $this->assertInstanceOf("\\FlyFoundation\\Views\\GenericView",$result);
        $this->assertEquals("NotImplemented",$result->getEntityName());
    }

    //Load not existsing view with view naming but no LSD-definition
    public function testLoadViewThatDoesNotExistButHasViewNaming()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = Factory::loadView("SomeClassNotInLsd");
    }

    //Load not existing view without view naming
    public function testLoadViewThatDoesNotExistWithoutViewNaming()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = Factory::load("\\FlyFoundation\\Views\\NotExistingClass");
    }

    //Check existence of existing view
    public function testExistsViewThatExists()
    {
        $result = Factory::viewExists("Demo");
        $this->assertTrue($result);
    }

    //Check existence of not existing view with view naming and LSD-definition
    public function testExistsViewThatDoesNotExistButHasViewNamingAndLsd()
    {
        $result = Factory::ViewExists("NotImplemented");
        $this->assertTrue($result);
    }

    //Check existence of view with view naming but no LSD-definition
    public function testExistsViewThatDoesNotExistButHasViewNaming()
    {
        $result = Factory::viewExists("SomeClassNotInLsd");
        $this->assertFalse($result);
    }

    //Check existence of not existing view without view naming
    public function testExistsViewThatDoesNotExistWithoutViewNaming()
    {
        $result = Factory::exists("\\FlyFoundation\\Views\\NotExistingClass");
        $this->assertFalse($result);
    }

}
 