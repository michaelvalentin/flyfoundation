<?php

require_once __DIR__ . '/../test-init.php';


class TextFieldTest extends \PHPUnit_Framework_TestCase
{

    public function testInstantiation()
    {
        $formField = new \FlyFoundation\Models\Forms\FormFields\TextField();
        $this->assertInstanceOf("\\FlyFoundation\\Models\\Forms\\FormFields\\FormField", $formField);
    }

    public function testSettersGetters()
    {
        $formField = new \FlyFoundation\Models\Forms\FormFields\TextField();

        $formField->setName('testName');
        $this->assertEquals('testName', $formField->getName());

        $formField->setLabel('testLabel');
        $this->assertEquals('testLabel', $formField->getLabel());

        $formField->setValue('testValue');
        $this->assertEquals('testValue', $formField->getValue());

        $formField->addClass('testClass');
        $this->assertEquals(array('testClass'), $formField->getClasses());

        $formField->removeClass('testClass');
        $this->assertEquals(array(), $formField->getClasses());
    }

    public function testFieldHTML()
    {
        $formField = new \FlyFoundation\Models\Forms\FormFields\TextField();
        $formField->setName('testName');
        $formField->addClass('testClass');
        $this->assertEquals('<input name="testName" class="testClass" type="text" value="" />', $formField->getFieldHTML());
    }

    public function testAsArray()
    {
        $formField = new \FlyFoundation\Models\Forms\FormFields\TextField();
        $formField->setName('testName');
        $formField->setLabel('testLabel');
        $formField->setValue('testValue');
        $formField->addClass('testClass');
        $this->assertEquals(array(
            'name' => 'testName',
            'label' => 'testLabel',
            'fieldHTML' => '<input name="testName" class="testClass" type="text" value="testValue" />',
            'classes' => array(
                'testClass'
            ),
            'value' => 'testValue'
        ), $formField->asArray());
    }
}