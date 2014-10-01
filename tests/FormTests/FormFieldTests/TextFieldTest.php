<?php

require_once __DIR__ . '/../../test-init.php';

use FlyFoundation\Models\Forms\FormFields\TextField;

class TextFieldTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var TextField
     */
    private $textField;

    protected function setUp()
    {
        $this->textField = new TextField();
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf("\\FlyFoundation\\Models\\Forms\\FormFields\\FormField", $this->textField);
    }

    public function testSetGetName()
    {
        $this->textField->setName('demo');
        $this->assertSame('demo', $this->textField->getName());
    }

    public function testSetGetLabel()
    {
        $this->textField->setLabel('demo label');
        $this->assertSame('demo label', $this->textField->getLabel());
    }

    public function testSetGetValue()
    {
        $this->textField->setValue('demo value');
        $this->assertSame('demo value', $this->textField->getValue());
    }

    public function testAddGetClasses()
    {
        $this->textField->addClass('test');
        $this->assertSame(array('test'), $this->textField->getClasses());
        $this->textField->addClass('test2');
        $this->assertSame(array('test', 'test2'), $this->textField->getClasses());
    }

    public function testRemoveClass()
    {
        $this->textField->addClass('test');
        $this->textField->addClass('test2');
        $this->assertSame(array('test', 'test2'), $this->textField->getClasses());
        $this->textField->removeClass('test');
        $this->assertSame(array('test2'), $this->textField->getClasses());
    }

    public function testFieldHTML()
    {
        $this->textField->setName('demo');
        $this->textField->addClass('demo-class');
        $this->textField->setValue('demo value');
        $this->assertEquals('<input name="demo" class="demo-class" type="text" value="demo value" />', $this->textField->getFieldHTML());
    }

    public function testAsArray()
    {
        $expected = array(
            'name' => 'demo',
            'label' => 'demo label',
            'fieldHTML' => '<input name="demo" class="demo-class" type="text" value="demo value" />',
            'classes' => array('demo-class'),
            'value' => 'demo value'
        );
        $this->textField->setName('demo');
        $this->textField->setLabel('demo label');
        $this->textField->setValue('demo value');
        $this->textField->addClass('demo-class');
        $this->assertEquals($expected, $this->textField->asArray());
    }
}