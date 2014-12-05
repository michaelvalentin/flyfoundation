<?php


use FlyFoundation\SystemDefinitions\ValidationDefinition;
use FlyFoundation\SystemDefinitions\ValidationType;

class ValidationDefinitionTest extends PHPUnit_Framework_TestCase {
    /** @var  ValidationDefinition */
    protected $validationDef;

    protected function setUp()
    {
        $this->validationDef = new ValidationDefinition();
    }

    public function testGetEmptyType()
    {
        $res = $this->validationDef->getType();
        $this->assertNull($res);
    }

    public function testSetIllegalType()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->validationDef->setType("Test");
    }

    public function testSetGetType()
    {
        $this->validationDef->setType(ValidationType::GreaterThanOrEqual);
        $res = $this->validationDef->getType();
        $this->assertEquals(ValidationType::GreaterThanOrEqual,$res);
    }

    public function testGetEmptyFieldNames()
    {
        $res = $this->validationDef->getFieldNames();
        $this->assertTrue(is_array($res));
        $this->assertEmpty($res);
    }

    public function testSetNoneStringFieldNames()
    {
        $fieldNames = [
            "field1",
            new DateTime()
        ];

        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");

        $this->validationDef->setFieldNames($fieldNames);
    }

    public function testSetGetFieldNames()
    {
        $fieldNames = [
            "field1",
            "field2",
            "otherField"
        ];

        $this->validationDef->setFieldNames($fieldNames);

        $res = $this->validationDef->getFieldNames();

        $this->assertSame($fieldNames,$res);
    }
}
 