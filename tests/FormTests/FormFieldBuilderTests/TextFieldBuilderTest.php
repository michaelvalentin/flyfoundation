<?php

require_once __DIR__ . '/../../test-init.php';

use FlyFoundation\Models\Forms\GenericForm;
use FlyFoundation\Models\Forms\FormFields\TextField;
use FlyFoundation\Models\Forms\Builders\TextFieldBuilder;

class TextFieldBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GenericForm
     */
    private $form;

    /**
     * @var TextField
     */
    private $textField;

    /**
     * @var TextFieldBuilder
     */
    private $textFieldBuilder;

    protected function setUp()
    {
        parent::setUp();

        $this->form = new GenericForm();
        $this->textField = new TextField();
        $this->textFieldBuilder = new TextFieldBuilder($this->form, $this->textField);
    }

    public function testSetName()
    {
        $return = $this->textFieldBuilder->setName('demo');
        $this->assertSame('demo', $this->textField->getName());
        $this->assertSame(array('demo' => $this->textField), $this->form->getFields());
        $this->assertSame($this->textFieldBuilder, $return);
    }

    public function testSetLabel()
    {
        $return = $this->textFieldBuilder->setLabel('demo label');
        $this->assertSame('demo label', $this->textField->getLabel());
        $this->assertSame($this->textFieldBuilder, $return);
    }

    public function testAddClass()
    {
        $return = $this->textFieldBuilder->addClass('demo-class');
        $this->assertSame(array('demo-class'), $this->textField->getClasses());
        $this->assertSame($this->textFieldBuilder, $return);
    }

    public function testSetRequired()
    {
        $return = $this->textFieldBuilder
            ->setName('demo')
            ->setRequired('demo error');
        $this->assertSame(array('required'), $this->textField->getClasses());
        $this->assertSame(false, $this->form->validate());
        $this->textField->setValue('demo value');
        $this->assertSame(true, $this->form->validate());
        $this->assertSame($this->textFieldBuilder, $return);
    }

    public function testSetMinimumLength()
    {
        $return = $this->textFieldBuilder
            ->setName('demo')
            ->setMinimumLength(3, 'demo error');

        $this->textField->setValue('de');
        $this->assertSame(false, $this->form->validate());

        $this->textField->setValue('dem');
        $this->assertSame(true, $this->form->validate());

        $this->textField->setValue(2);
        $this->assertSame(false, $this->form->validate());

        $this->textField->setValue(3);
        $this->assertSame(true, $this->form->validate());

        $this->assertSame($this->textFieldBuilder, $return);
    }

    public function testSetMaximumLength()
    {
        $return = $this->textFieldBuilder
            ->setName('demo')
            ->setMaximumLength(3, 'demo error');

        $this->textField->setValue('demo');
        $this->assertSame(false, $this->form->validate());

        $this->textField->setValue('dem');
        $this->assertSame(true, $this->form->validate());

        $this->textField->setValue(4);
        $this->assertSame(false, $this->form->validate());

        $this->textField->setValue(3);
        $this->assertSame(true, $this->form->validate());

        $this->assertSame($this->textFieldBuilder, $return);
    }

}

 