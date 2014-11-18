<?php

namespace ModelTests\GenericEntityTests;

use FlyFoundation\Models\EntityFields\TextField;
use FlyFoundation\Models\EntityValidations\Required;
use FlyFoundation\Models\OpenGenericEntity;

require_once __DIR__.'/../../test-init.php';

class GenericEntityTest extends \PHPUnit_Framework_TestCase {

    /** @var OpenGenericEntity */
    private $entity;

    protected function setUp(){
        parent::setUp();

        $this->entity = new OpenGenericEntity();
        $field = new TextField();
        $field->setName("test");
        $this->entity->addField($field);
        $field = new TextField();
        $field->setName("demo");
        $this->entity->addField($field);
        $validation = new Required();
        $validation->setName("require-demo");
        $validation->setFields([$field]);
        $this->entity->addValidation($validation);
    }

    public function testGetAndSet()
    {
        $this->entity->set("test","my value");
        $this->entity->set("demo","other value");
        $result1 = $this->entity->get("test");
        $result2 = $this->entity->get("demo");

        $this->assertEquals("my value",$result1);
        $this->assertEquals("other value",$result2);
    }

    public function testSetObjectOnTextField()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $object = new TextField();
        $this->entity->set("test",$object);
    }

    public function testSetNumberOnTextField()
    {
        $num = 2;
        $this->entity->set("test",$num);
        $result = $this->entity->get("test");

        $this->assertEquals(2,$result);
    }

    public function testValidateWithNoValues()
    {
        $result = $this->entity->validate();

        $this->assertFalse($result);
    }

    public function testValidateWithOtherValue()
    {
        $this->entity->set("test","MyString");
        $result = $this->entity->validate();

        $this->assertFalse($result);
    }

    public function testValidateWithCorrectValue()
    {
        $this->entity->set("demo","SomeString");
        $result = $this->entity->validate();

        $this->assertTrue($result);
    }

    public function testSetAndGetName()
    {
        $this->entity->setName("MyEntityNumber56");
        $result = $this->entity->getName();

        $this->assertEquals("MyEntityNumber56",$result);
    }

    public function testSetNameSpecialChar()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->entity->setName("Some%value");
    }

    public function testSetNameWhiteSpace()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->entity->setName("Some value");
    }

    public function testSetNameNumberFirst()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->entity->setName("5SomeValue");
    }

    public function testSetAll()
    {
        $this->entity->setAll([
            "test" => "value1",
            "demo" => "value2"
        ]);
        $res1 = $this->entity->get("test");
        $res2 = $this->entity->get("demo");

        $this->assertEquals("value1",$res1);
        $this->assertEquals("value2",$res2);
    }

    public function testGetAll()
    {
        $this->entity->set("test","myVal 1");
        $this->entity->set("demo","myVal 2");
        $res = $this->entity->getAll();

        $this->assertInternalType("array",$res);
        $this->assertCount(2,$res);
        $this->assertEquals("myVal 1",$res["test"]);
        $this->assertEquals("myVal 2",$res["demo"]);
    }

    public function testGetAllEmpty()
    {
        $res = $this->entity->getAll();

        $this->assertInternalType("array",$res);
        $this->assertCount(2,$res);
        $this->assertNull($res["test"]);
        $this->assertNull($res["demo"]);
    }
}
 