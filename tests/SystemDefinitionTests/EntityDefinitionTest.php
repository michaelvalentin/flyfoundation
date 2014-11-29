<?php

use FlyFoundation\SystemDefinitions\EntityDefinition;
use FlyFoundation\SystemDefinitions\FieldDefinition;
use FlyFoundation\SystemDefinitions\PersistentFieldDefinition;
use FlyFoundation\SystemDefinitions\ValidationDefinition;

require_once __DIR__ . '/../test-init.php';

class AlternateFieldDefinition extends FieldDefinition
{

}

class EntityDefinitionTest extends PHPUnit_Framework_TestCase {

    /** @var EntityDefinition */
    protected $entityDef;

    protected function setUp()
    {
        $this->entityDef = new EntityDefinition();
    }

    public function testGetEmptyFields()
    {
        $res = $this->entityDef->getFieldDefinitions();
        $this->assertTrue(is_array($res));
        $this->assertEmpty($res);
    }

    public function testGetNotExistingField()
    {
       $field = new PersistentFieldDefinition();
       $field->setName("Test");
       $this->entityDef->setFieldDefinitions([$field]);
       $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
       $res = $this->entityDef->getFieldDefinition("Demo");
    }

    public function testGetExistingField()
    {
        $field = new PersistentFieldDefinition();
        $field->setName("Test");
        $this->entityDef->setFieldDefinitions([$field]);
        $res = $this->entityDef->getFieldDefinition("Test");

        $this->assertInstanceOf("\\FlyFoundation\\SystemDefinitions\\PersistentFieldDefinition",$res);
    }

    public function testSetGetFields()
    {
        $fields = [
            new PersistentFieldDefinition(),
            new PersistentFieldDefinition()
        ];
        $fields[0]->setName("Test");
        $fields[1]->setName("Demo");

        $resultFields = [
            "Test" => $fields[0],
            "Demo" => $fields[1]
        ];

        $this->entityDef->setFieldDefinitions($fields);
        $res = $this->entityDef->getFieldDefinitions();
        $this->assertSame($resultFields, $res);
    }

    public function testContainsFieldDefinition()
    {
        $field = new PersistentFieldDefinition();
        $field->setName("Test");
        $this->entityDef->setFieldDefinitions([$field]);
        $res1 = $this->entityDef->containsFieldDefinition("Test");
        $res2 = $this->entityDef->containsFieldDefinition("Demo");

        $this->assertTrue($res1);
        $this->assertFalse($res2);
    }

    public function testGetPersistentFieldDefinitions()
    {
        $fields = [
            new PersistentFieldDefinition(),
            new AlternateFieldDefinition(),
            new PersistentFieldDefinition(),
            new PersistentFieldDefinition(),
            new AlternateFieldDefinition()
        ];

        $fields[0]->setName("Test1");
        $fields[1]->setName("Test2");
        $fields[2]->setName("Test3");
        $fields[3]->setName("Test4");
        $fields[4]->setName("Test5");

        $resultFields = [
            "Test1" => $fields[0],
            "Test3" => $fields[2],
            "Test4" => $fields[3]
        ];

        $this->entityDef->setFieldDefinitions($fields);

        $res = $this->entityDef->getPersistentFieldDefinitions();

        $this->assertSame($resultFields, $res);
    }

    public function testGetEmptyValidations()
    {
        $res = $this->entityDef->getValidationDefinitions();

        $this->assertTrue(is_array($res));
        $this->assertEmpty($res);
    }

    public function testSetGetValidations()
    {
        $validations = array(
            new ValidationDefinition(),
            new ValidationDefinition(),
            new ValidationDefinition()
        );

        $this->entityDef->setValidationDefinitions($validations);

        $res = $this->entityDef->getValidationDefinitions();

        $this->assertSame($validations, $res);
    }
}
 