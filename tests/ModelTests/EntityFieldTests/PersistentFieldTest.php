<?php

namespace ModelTests\GenericEntityTests;

use TestApp\Models\MockPersistentField;

require_once __DIR__.'/../../test-init.php';

class PersistentFieldTest extends \PHPUnit_Framework_TestCase
{
    /** @var MockPersistentField $field */
    private $field;

    protected function setUp()
    {

        $this->field = new MockPersistentField();

        return parent::setUp();
    }

    public function testSetGetName()
    {
        $this->field->setName("anamehere");
        $result = $this->field->getName();

        $this->assertEquals("anamehere",$result);
    }

    public function testGetUnsetName()
    {
        $result = $this->field->getName();
        $this->assertNull($result);
    }

    public function testSetNameWithSpace()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->field->setName("aname here");
    }

    public function testSetNameWithLeadingSpace()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->field->setName(" anamehere");
    }

    public function testSetNameWithTrailingSpace()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->field->setName("anamehere ");
    }

    public function testSetNameWithSpecialCharachter()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->field->setName("%name");
    }

    public function testSetNameWithSpecialCharachter2()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->field->setName("na&me");
    }

    public function testSetNameWithSpecialCharachter3()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->field->setName("name?");
    }

    public function testSetNameStartingWithNumber()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->field->setName("8name");
    }

    public function testSetNameWithNumber()
    {
        $this->field->setName("na8me");
        $result = $this->field->getName();

        $this->assertEquals("na8me",$result);
    }

    public function testSetGetValueInteger()
    {
        $this->field->setValue(48);
        $result = $this->field->getValue();

        $this->assertEquals(48,$result);
    }

    public function testSetGetValueFloat()
    {
        $this->field->setValue(48.49);
        $result = $this->field->getValue();

        $this->assertEquals(48.49,$result);
    }

    public function testSetGetValueString()
    {
        $this->field->setValue("this is a STRING");
        $result = $this->field->getValue();

        $this->assertEquals("this is a STRING",$result);
    }

    public function testSetGetValueDate()
    {
        $date = new \DateTime();

        $this->field->setValue($date);
        $result = $this->field->getValue();

        $this->assertSame($date,$result);
    }

    public function testSetGetValueObject()
    {
        $object = new MockPersistentField();

        $this->field->setValue($object);
        $result = $this->field->getValue();

        $this->assertEquals($object,$result);
    }
}