<?php

use FlyFoundation\Core\Context;
use FlyFoundation\Factory;

require_once __DIR__.'/../test-init.php';


class ViewFactoryTest extends PHPUnit_Framework_TestCase {

    protected function setUp()
    {
        $app = new \FlyFoundation\App();
        $app->addConfigurators(TEST_BASE."/TestApp/configurators");
        $app->prepareCoreDependencies();
        parent::setUp();
    }

    /**
     * load
     *  - Actual class if it exists
     *  - Default view if the class has view naming
     */

    //Load existing view
    public function testLoadViewThatExists()
    {
        $result = Factory::loadView("Demo");
        $this->assertInstanceOf("\\TestApp\\Views\\DemoView",$result);
    }

    //Load not existing view with view naming
    public function testLoadViewThatDoesNotExistButHasViewNaming()
    {
        $result = Factory::loadView("NotImplemented");
        $this->assertInstanceOf("\\FlyFoundation\\Views\\DefaultView",$result);
    }

    //Load not existing view without view naming
    public function testLoadViewThatDoesNotExistWithoutViewNaming()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = Factory::loadWithoutImplementationSearch("\\FlyFoundation\\Views\\NotExistingClass");
    }

    /**
     * exists
     *  - Checks if an actual class exists, or the class has view naming
     */


    //Check existence of existing view
    public function testExistsViewThatExists()
    {
        $result = Factory::viewExists("Demo");
        $this->assertTrue($result);
    }

    //Check existence of not existing view with view naming
    public function testExistsViewThatDoesNotExistButHasViewNaming()
    {
        $result = Factory::ViewExists("NotImplemented");
        $this->assertTrue($result);
    }

    //Check existence of not existing view without view naming
    public function testExistsViewThatDoesNotExistWithoutViewNaming()
    {
        $result = Factory::exists("\\FlyFoundation\\Views\\NotExistingClass");
        $this->assertFalse($result);
    }

}
 