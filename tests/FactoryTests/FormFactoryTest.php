<?php

use FlyFoundation\Factory;

require_once __DIR__.'/../use-test-app.php';

class FormFactoryTest extends PHPUnit_Framework_TestCase {

    public function testLoadKnownForm()
    {
        $form = Factory::loadForm("DemoEntity","Create");
        $result = $form->asArray();
        $this->assertEquals(2,count($result));
    }
}
 