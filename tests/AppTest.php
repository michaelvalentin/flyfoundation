<?php


use FlyFoundation\Core\App;

class AppTest extends \PHPUnit_Framework_TestCase {

    public function testInstansiation()
    {
        $app = new App();
        assertInstanceOf("\\FlyFoundation\\Core\\App",$app);
    }
}
 