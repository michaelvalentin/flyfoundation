<?php

use FlyFoundation\Factory;

require_once __DIR__.'/../test-init.php';


class ViewFactoryTest extends PHPUnit_Framework_TestCase {
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
     *  - Actual class if it exists
     *  - Default view if the class has view naming
     */

    //Load existing view
    public function testLoadViewThatExists()
    {
        $result = $this->factory->loadView("Demo");
        $this->assertInstanceOf("\\TestApp\\Views\\DemoView",$result);
    }

    //Load not existing view with view naming
    public function testLoadViewThatDoesNotExistButHasViewNaming()
    {
        $result = $this->factory->loadView("NotImplemented");
        $this->assertInstanceOf("\\FlyFoundation\\Views\\DefaultView",$result);
    }

    //Load not existing view without view naming
    public function testLoadViewThatDoesNotExistWithoutViewNaming()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\UnknownClassException");
        $result = $this->factory->load("\\FlyFoundation\\Views\\NotExistingClass");
    }

    /**
     * exists
     *  - Checks if an actual class exists, or the class has view naming
     */


    //Check existence of existing view
    public function testExistsViewThatExists()
    {
        $result = $this->factory->viewExists("Demo");
        $this->assertTrue($result);
    }

    //Check existence of not existing view with view naming
    public function testExistsViewThatDoesNotExistButHasViewNaming()
    {
        $result = $this->factory->ViewExists("NotImplemented");
        $this->assertTrue($result);
    }

    //Check existence of not existing view without view naming
    public function testExistsViewThatDoesNotExistWithoutViewNaming()
    {
        $result = $this->factory->exists("\\FlyFoundation\\Views\\NotExistingClass");
        $this->assertFalse($result);
    }

}
 