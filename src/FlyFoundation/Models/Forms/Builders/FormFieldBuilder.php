<?php

namespace FlyFoundation\Models\Forms\Builders;

use FlyFoundation\Models\Forms\FormValidations\MaximumLength;
use FlyFoundation\Models\Forms\FormValidations\MinimumLength;
use FlyFoundation\Models\Forms\FormValidations\Required;
use FlyFoundation\Models\Forms\GenericForm;
use FlyFoundation\Models\Forms\FormFields\FormField;

abstract class FormFieldBuilder
{
    /**
     * @var GenericForm
     */
    protected $form;

    /**
     * @var FormField
     */
    protected $field;

    public function __construct(GenericForm $form, FormField $field)
    {
        $this->form = $form;
        $this->field = $field;
    }

    public function setName($name)
    {
        $this->field->setName($name);
        $this->form->addField($this->field);
        return $this;
    }

    public function setLabel($label)
    {
        $this->field->setLabel($label);
        return $this;
    }

    public function addClass($class)
    {
        $this->field->addClass($class);
        return $this;
    }

    public function setRequired($errorText)
    {
        $required = new Required();
        $required->setName($this->field->getName().'-Required');
        $required->setFields(array($this->field));
        $required->setErrorText($errorText);

        $this->field->addClass('required');
        $this->form->addValidation($required);
    }

    public function setMinimumLength($limit, $errorText)
    {
        $minimumLength = new MinimumLength();
        $minimumLength->setName($this->field->getName().'-MinimumLength');
        $minimumLength->setFields($this->field);
        $minimumLength->setLimit($limit);
        $minimumLength->setErrorText($errorText);

        $this->form->addValidation($minimumLength);
    }

    public function setMaximumLength($limit, $errorText)
    {
        $maximumLength = new MaximumLength();
        $maximumLength->setName($this->field->getName().'-MaximumLength');
        $maximumLength->setFields($this->field);
        $maximumLength->setLimit($limit);
        $maximumLength->setErrorText($errorText);

        $this->form->addValidation($maximumLength);
    }
}