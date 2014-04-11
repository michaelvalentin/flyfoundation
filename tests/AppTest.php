<?php

require_once __DIR__.'/../vendor/autoload.php';

use FlyFoundation\App;

class AppTest extends \PHPUnit_Framework_TestCase {

    public function testInstansiation()
    {
        $app = new App();
        $this->assertInstanceOf("\\FlyFoundation\\App",$app);
    }

    //Test if the configurations added is actually applied
    public function testConfigurationsLoading()
    {
        $app = new App();
        $app->addConfigurator(__DIR__."/sample_configurators");
        $config = $app->getConfiguration();

        $test = $config->get("test");
        $this->assertSame("ABCDabcd",$test);

        $test2 = $config->get("test2");
        $this->assertSame("This is a demo",$test2);
    }
}