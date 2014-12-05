<?php

use FlyFoundation\SystemDefinitions\DefinitionComponent;

require_once __DIR__ . '/../test-init.php';

class DefinitionComponentTestClassTwo extends \FlyFoundation\SystemDefinitions\DefinitionComponent
{
    protected $value;

    public function validate()
    {
        if(!$this->value){
            throw new \FlyFoundation\Exceptions\InvalidOperationException();
        }

        parent::validate();
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}

class DefinitionComponentTestClass extends \FlyFoundation\SystemDefinitions\DefinitionComponent
{
    public $defCompTwo;
    public $value;

    public function __construct()
    {
        $this->defCompTwo = new DefinitionComponentTestClassTwo();
        $this->value = "test";
    }

    public function validate()
    {
        if($this->value !== null){
            throw new \FlyFoundation\Exceptions\InvalidOperationException();
        }

        parent::validate();
    }
}

class DefinitionComponentTest extends PHPUnit_Framework_TestCase {

    /** @var  DefinitionComponent */
    protected $defComp;

    protected function setUp()
    {
        $this->defComp = new DefinitionComponentTestClass();
    }

    public function testSetGetSettings()
    {
        $data = [
            "test" => "demo",
            "some_setting" => "something",
            "MySetting" => "My big value",
            "DateSetting" => new DateTime(),
            "ObjectSetting" => new \FlyFoundation\Util\Map()
        ];

        $this->defComp->setSettings($data);

        $res = $this->defComp->getSettings();

        $this->assertSame($data, $res);
    }

    public function testGetSetting()
    {
        $data = [
            "test" => "demo",
            "some_setting" => "something",
            "MySetting" => "My big value",
            "DateSetting" => new DateTime(),
            "ObjectSetting" => new \FlyFoundation\Util\Map()
        ];

        $this->defComp->setSettings($data);

        $res1 = $this->defComp->getSetting("test");
        $res2 = $this->defComp->getSetting("MySetting");
        $res3 = $this->defComp->getSetting("some_setting");
        $res4 = $this->defComp->getSetting("DateSetting");
        $res5 = $this->defComp->getSetting("ObjectSetting");

        $this->assertEquals("demo",$res1);
        $this->assertEquals("My big value",$res2);
        $this->assertEquals("something",$res3);
        $this->assertInstanceOf("\\DateTime",$res4);
        $this->assertInstanceOf("\\FlyFoundation\\Util\\Map",$res5);
    }

    public function testGetNonExsistingSetting()
    {
        $this->defComp->setSettings([
            "test" => "demo",
            "demo" => false
        ]);

        $res1 = $this->defComp->getSetting("nonexistant", "Test");
        $res2 = $this->defComp->getSetting("nonexistant");
        $res3 = $this->defComp->getSetting("demo", "Test");

        $this->assertEquals("Test", $res1);
        $this->assertNull($res2);
        $this->assertFalse($res3);
    }

    public function testGetSetName()
    {
        $this->defComp->setName("TestName");
        $res = $this->defComp->getName();

        $this->assertEquals("TestName",$res);
    }

    public function testValidateAllWrong()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidOperationException");
        $this->defComp->validate();
    }

    public function testValidateOneWrong()
    {
        $this->defComp->value = null;
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidOperationException");
        $this->defComp->validate();
    }

    public function testValidateOtherWrong()
    {
        $this->defComp->defCompTwo->setValue("Test");
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidOperationException");
        $this->defComp->validate();
    }


    public function testValidate()
    {
        $this->defComp->value = null;
        $this->defComp->defCompTwo->setValue("test");

        $this->defComp->validate();
    }

    public function testLock()
    {
        $this->defComp->lock();

        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidOperationException");

        $this->defComp->setName("Test");
    }

    public function testIsLocked()
    {
        $res1 = $this->defComp->isLocked();
        $this->defComp->lock();
        $res2 = $this->defComp->isLocked();

        $this->assertFalse($res1);
        $this->assertTrue($res2);
    }

}
 