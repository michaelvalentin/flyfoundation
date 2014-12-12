<?php

namespace ModelTests\GenericEntityTests;

use FlyFoundation\Models\EntityFields\BooleanField;

require_once __DIR__.'/../../test-init.php';

class BooleanFieldTest extends \PHPUnit_Framework_TestCase {
    /** @var  BooleanField $field */
    private $field;

    protected function setUp()
    {
        $this->field = new BooleanField();

        return parent::setUp();
    }

    public function testAcceptsValueString()
    {
        $result = $this->field->acceptsValue("this is a string");
        $this->assertFalse($result);
    }

    public function testAcceptsValueInteger()
    {
        $result = $this->field->acceptsValue(48);
        $this->assertFalse($result);
    }

    public function testAcceptsValueFloat()
    {
        $result = $this->field->acceptsValue(48.49);
        $this->assertFalse($result);
    }

    public function testAcceptsValueObject()
    {
        $result = $this->field->acceptsValue(new \DateTime());
        $this->assertFalse($result);
    }

    public function testAcceptsValueBoolean()
    {
        $result = $this->field->acceptsValue(true);
        $this->assertTrue($result);
    }

    public function testSetGetLegalValue()
    {
        $this->field->setValue(true);
        $result = $this->field->getValue();

        $this->assertEquals(true,$result);
        $this->assertNotEquals(false,$result);
    }

    public function testSetIllegalValue()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->field->setValue(1);
    }
}
 