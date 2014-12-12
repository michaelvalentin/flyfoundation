<?php

namespace ModelTests\GenericEntityTests;

use FlyFoundation\Models\EntityFields\IntegerField;

require_once __DIR__.'/../../test-init.php';

class IntegerFieldTest extends \PHPUnit_Framework_TestCase {
    /** @var  IntegerField $field */
    private $field;

    protected function setUp()
    {
        $this->field = new IntegerField();

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
        $this->assertTrue($result);
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
        $this->assertFalse($result);
    }

    public function testSetGetLegalValue()
    {
        $this->field->setValue(41);
        $result = $this->field->getValue();

        $this->assertEquals(41,$result);
    }

    public function testSetIllegalValue()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->field->setValue("test");
    }
}
 