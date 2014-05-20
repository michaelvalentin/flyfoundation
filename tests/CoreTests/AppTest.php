<?php

require_once __DIR__ . '/../test-init.php';

use FlyFoundation\App;

class AppTest extends \PHPUnit_Framework_TestCase {

    public function testInstansiation()
    {
        $app = new App();
        $this->assertInstanceOf("\\FlyFoundation\\App",$app);
    }

    //Test if the configurations added is actually applied
    public function testConfiguration()
    {
        $app = new App();
        $app->addConfigurators(TEST_BASE."/TestApp/configurators");
        $app->prepareCoreDependencies();

        $config = \FlyFoundation\Factory::getConfig();

        $test = $config->get("test");
        $this->assertSame("ABCDabcd",$test);

        $test2 = $config->get("test2");
        $this->assertSame("This is a demo",$test2);
    }

    public function testEntityDefinition()
    {
        $app = new App();
        $app->addConfigurators(TEST_BASE."/TestApp/configurators");
        $app->prepareCoreDependencies();

        $systemDefinition = \FlyFoundation\Factory::getAppDefinition();

        $result = $systemDefinition->hasEntity("DemoEntity");
        $this->assertTrue($result);
    }
}