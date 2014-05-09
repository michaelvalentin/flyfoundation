<?php

use FlyFoundation\App;
use FlyFoundation\SystemDefinitions\SystemDefinition;

require_once __DIR__ . '/test-init.php';


class TestEntityDefinitionOverwriting extends PHPUnit_Framework_TestCase {
    /** @var  SystemDefinition */
    private $definition;

    protected function setUp(){
        $app = new App();
        $app->addConfigurators(__DIR__."/TestApp/configurators");
        $this->definition = $app->getFactory()->getAppDefinition();
        parent::setUp();
    }

    public function testOutputData(){
        $result = $this->definition->getName();
        $this->assertSame("TestApp",$result);

        $result = $this->definition->hasEntity("MyImage");
        $this->assertTrue($result);

        $result = $this->definition->hasEntity("Image");
        $this->assertTrue($result);

        $result = $this->definition->hasEntity("File");
        $this->assertTrue($result);

        $result = $this->definition->hasEntity("Setting");
        $this->assertTrue($result);

        $result = $this->definition->hasEntity("DemoEntity");
        $this->assertTrue($result);

        $result = $this->definition->getEntity("MyImage")->getField("alternative")->getSetting("my_setting");
        $this->assertSame("something",$result);

        $result = $this->definition->getEntity("MyImage")->hasField("MyImageField");
        $this->assertTrue($result);

        $result = $this->definition->getEntity("File")->hasField("alt-url");
        $this->assertTrue($result);
    }
}
 