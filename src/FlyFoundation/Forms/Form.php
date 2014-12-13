<?php

namespace FlyFoundation\Forms;


use FlyFoundation\Forms\FormFields\FormField;
use FlyFoundation\Models\Model;

interface Form extends Model
{
    /**
     * @return FormField[]
     */
    public function getFields();

    /**
     * @return bool
     */
    public function validate();

    /**
     * @return string[]
     */
    public function getData();

    /**
     * @return string[]
     */
    public function getErrors();
}