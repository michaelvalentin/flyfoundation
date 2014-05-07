<?php

use FlyFoundation\Factory;

require_once __DIR__.'/../test-init.php';


class ControllerFactoryTest extends PHPUnit_Framework_TestCase {
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

    public function testLoadingNonExistingController()
    {
        /** @var GenericEntityController $result */
        $result = $this->factory->loadController("SomeSpecial");
        $this->assertInstanceOf("\\FlyFoundation\\Controllers\\GenericEntityController",$result);

        $model = $result->getModel();
        $view = $result->getView();

        $this->assertInstanceOf("\\FlyFoundation\\Models\\Model",$model);
        $this->assertInstanceOf("\\FlyFoundation\\Views\\View",$view);
    }

    public function testLoadingControllerInTestApp()
    {
        /** @var Controller $result */
        $result = $this->factory->loadController("TestAppSpecial");
        $this->assertInstanceOf("\\TestApp\\Controllers\\TestAppSpecialController",$result);

        $model = $result->getModel();
        $view = $result->getView();

        $this->assertInstanceOf("\\FlyFoundation\\Models\\Model",$model);
        $this->assertInstanceOf("\\FlyFoundation\\Views\\View",$view);
    }
}
 