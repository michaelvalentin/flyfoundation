<?php

require_once 'init-tests.php';

use FlyFoundation\Core\App;

class AppTest extends \PHPUnit_Framework_TestCase {

    public function testInstansiation()
    {
        $app = new App();
        $this->assertInstanceOf("\\FlyFoundation\\Core\\App",$app);
    }
}