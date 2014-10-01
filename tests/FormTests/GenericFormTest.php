<?php

require_once __DIR__ . '/../test-init.php';

use FlyFoundation\Models\Forms\GenericForm;
use FlyFoundation\Models\Forms\FormFields\TextField;
use FlyFoundation\Models\Forms\FormValidations\Required;

class GenericFormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GenericForm
     */
    private $form;

    /**
     * @var TextField
     */
    private $field;

    /**
     * @var Required
     */
    private $validation;

    protected function setUp()
    {
        parent::setUp();

        $this->form = new GenericForm();

        $this->field = new TextField();
        $this->field->setName('demo');
        $this->field->setValue('5');

        $this->validation = new Required();
        $this->validation->setName('required-demo');
        $this->validation->setFields([$this->field]);
        $this->validation->setErrorText('demo error');
    }

    public function testAddField()
    {
        $this->form->addField($this->field);
        $this->assertSame(array('demo' => $this->field), $this->form->getFields());
    }

    public function testRemoveField()
    {
        $this->form->addField($this->field);
        $this->form->removeField('demo');
        $this->assertSame(array(), $this->form->getFields());
    }

    public function testValidate()
    {
        $this->form->addValidation($this->validation);
        $this->assertSame(true, $this->form->validate());
    }

    public function testValidateFail()
    {
        $this->field->setValue('');
        $this->validation->setFields([$this->field]);
        $this->form->addValidation($this->validation);
        $this->assertSame(false, $this->form->validate());
    }

    public function testRemoveValidation()
    {
        $this->field->setValue('');
        $this->validation->setFields([$this->field]);
        $this->form->addValidation($this->validation);
        $this->assertSame(false, $this->form->validate());
        $this->form->removeValidation('required-demo');
        $this->assertSame(true, $this->form->validate());
    }

    public function testGetErrors()
    {
        $this->field->setValue('');
        $this->validation->setFields([$this->field]);
        $this->form->addValidation($this->validation);
        $this->assertSame(false, $this->form->validate());
        $this->assertSame(array('demo error'), $this->form->getErrors());
    }

    public function testAsArray()
    {
        $this->form->addField($this->field);
        $this->field->setValue('');
        $this->validation->setFields([$this->field]);
        $this->form->addValidation($this->validation);
        $this->assertSame(false, $this->form->validate());
        $expected = array(
            'fields' => array($this->field->asArray()),
            'errors' => array('demo error')
        );
        $this->assertSame($expected, $this->form->asArray());
    }

    public function testAddTextField()
    {
        $textFieldBuilder = $this->form->addTextField();
        $this->assertInstanceOf('\\FlyFoundation\\Models\\Forms\\Builders\\TextFieldBuilder', $textFieldBuilder);
    }

    public function testAddSelectList()
    {
        $selectListBuilder = $this->form->addSelectList();
        $this->assertInstanceOf('\\FlyFoundation\\Models\\Forms\\Builders\\SelectListBuilder', $selectListBuilder);
    }
}