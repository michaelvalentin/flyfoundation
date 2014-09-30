<?php

namespace ModelTests\GenericEntityTests;

use FlyFoundation\Models\EntityFields\DateField;
use TestApp\Models\MockPersistentField;

require_once __DIR__.'/../../test-init.php';

class DateFieldTest extends \PHPUnit_Framework_TestCase {
    /** @var  DateField $field */
    private $field;

    protected function setUp()
    {
        $this->field = new DateField();

        return parent::setUp();
    }

    public function testAcceptsValueString()
    {
        $result = $this->field->acceptsValue("this is a string");
        $this->assertFalse($result);
    }

    public function testAcceptsValueNumber()
    {
        $result = $this->field->acceptsValue(48);
        $this->assertFalse($result);
    }

    public function testAcceptsValueObject()
    {
        $object = new MockPersistentField();
        $result = $this->field->acceptsValue($object);
        $this->assertFalse($result);
    }

    public function testAcceptsValueDateTime()
    {
        $result = $this->field->acceptsValue(new \DateTime());
        $this->assertTrue($result);
    }

    public function testAcceptsValueBoolean()
    {
        $result = $this->field->acceptsValue(true);
        $this->assertFalse($result);
    }

    public function testSetGetLegalValue()
    {
        $date = new \DateTime("10-12-2014 10:55:17");
        $this->field->setValue($date);
        $result = $this->field->getValue();

        $compdate = new \DateTime("10-12-2014 08:34:49");

        $this->assertEquals($compdate->format("%D-%M-%Y"),$result->format("%D-%M-%Y"));
    }

    public function testSetIllegalValue()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->field->setValue("test");
    }
}
 