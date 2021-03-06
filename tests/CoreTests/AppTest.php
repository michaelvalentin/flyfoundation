<?php

require_once __DIR__ . '/../test-init.php';

use FlyFoundation\App;
use FlyFoundation\Core\Context;

class AppTest extends \PHPUnit_Framework_TestCase {

    public function testInstantiation()
    {
        $app = new App();
        $this->assertInstanceOf("\\FlyFoundation\\App",$app);
    }

    //Test if the configurations added is actually applied
    public function testConfiguration()
    {
        $app = new App();
        $app->addConfigurators(TEST_BASE."/TestApp/configurators");
        $app->prepareCoreDependencies(new Context(""));

        $config = \FlyFoundation\Factory::getConfig();

        $test = $config->get("test");
        $this->assertSame("ABCDabcd",$test);

        $test2 = $config->get("test2");
        $this->assertSame("This is a demo",$test2);
    }
}