<?php

require_once __DIR__.'/../vendor/autoload.php';

use FlyFoundation\App;

class AppTest extends \PHPUnit_Framework_TestCase {

    public function testInstansiation()
    {
        $app = new App();
        $this->assertInstanceOf("\\FlyFoundation\\App",$app);
    }
}