<?php

namespace FlyFoundation\Models\Forms;


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