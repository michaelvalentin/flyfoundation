<?php

use FlyFoundation\Models\EntityValidations\Required;

require_once __DIR__.'/../../test-init.php';


class RequiredTest extends \PHPUnit_Framework_TestCase {
    /** @var Required */
    private $validation;

    protected function setUp()
    {
        $this->validation = new Required();
    }

    public function testWithNoFields()
    {
        $result = $this->validation->validate([
            "test" => "demo"
        ]);
        $this->assertTrue($result);
    }

    public function testValidateOneFieldNoValue()
    {
        $this->validation->addFieldName("MyField");
        $result = $this->validation->validate([
            "myfield" => "demo",
            "some_field" => 5,
            "third-field" => new DateTime()
        ]);
        $this->assertFalse($result);
    }

    public function testValidateOneFieldHasValue()
    {
        //TODO: Implement
    }

    public function testValidateOneFieldIsFalse()
    {
        //TODO: Implement
    }

    public function testValidateOnFieldIsZero()
    {
        //TODO: Implement
    }

    public function testValidateTwoFieldsOneMissing()
    {
        //TODO: Implement
    }

    public function testValidateTwoFieldsNoneMissing()
    {
        //TODO: Implement
    }
}
 