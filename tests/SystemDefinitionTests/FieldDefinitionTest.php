<?php

use FlyFoundation\SystemDefinitions\FieldDefinition;

class FieldDefinitionTestClass extends \FlyFoundation\SystemDefinitions\FieldDefinition
{

}

class FieldDefinitionTest extends PHPUnit_Framework_TestCase
{
    /** @var  FieldDefinition */
    protected $fieldDef;

    protected function setUp()
    {
        $this->fieldDef = new FieldDefinitionTestClass();
    }

    public function testGetEmptyType()
    {
        $res1 = $this->fieldDef->getType();
        $this->assertNull($res1);
    }

    public function testSetGetType()
    {
        $this->fieldDef->setType(\FlyFoundation\SystemDefinitions\FieldType::Date);

        $res = $this->fieldDef->getType();

        $this->assertEquals(\FlyFoundation\SystemDefinitions\FieldType::Date,$res);
    }

    public function testSetInvalidType()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->fieldDef->setType("invalid");
    }
}
 