<?php

namespace FlyFoundation\Forms\Builders;


use FlyFoundation\Forms\FormFields\SelectList;
use FlyFoundation\Forms\GenericForm;

class SelectListBuilder extends FormFieldBuilder
{
    public function __construct(GenericForm $form, SelectList $field)
    {
        $this->form = $form;
        $this->field = $field;
    }

    public function setOptions($options)
    {
        $this->field->setOptions($options);
        return $this;
    }
} 