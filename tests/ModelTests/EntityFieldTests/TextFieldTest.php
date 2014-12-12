<?php

namespace ModelTests\GenericEntityTests;

use FlyFoundation\Models\EntityFields\TextField;

require_once __DIR__.'/../../test-init.php';

class TextFieldTest extends \PHPUnit_Framework_TestCase {
    /** @var  TextField $field */
    private $field;

    protected function setUp()
    {
        $this->field = new TextField();

        return parent::setUp();
    }

    public function testAcceptsValueString()
    {
        $result = $this->field->acceptsValue("this is a string");
        $this->assertTrue($result);
    }

    public function testAcceptsValueNumber()
    {
        $result = $this->field->acceptsValue(48);
        $this->assertTrue($result);
    }

    public function testAcceptsValueObject()
    {
        $result = $this->field->acceptsValue(new \DateTime());
        $this->assertFalse($result);
    }

    public function testAcceptsValueBoolean()
    {
        $result = $this->field->acceptsValue(true);
        $this->assertFalse($result);
    }

    public function testSetGetLegalValue()
    {
        $this->field->setValue("This is my test!");
        $result = $this->field->getValue();

        $this->assertEquals("This is my test!",$result);
    }

    public function testSetIllegalValue()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->field->setValue(new \DateTime());
    }
}
 