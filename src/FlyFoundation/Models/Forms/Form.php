<?php

namespace FlyFoundation\Models\Forms;


use FlyFoundation\Models\Model;

interface Form extends Model
{
    /**
     * @param FormField $field
     * @return void
     */
    public function addField(FormField $field);

    /**
     * @param string $fieldName
     * @return void
     */
    public function removeField($fieldName);

    /**
     * @return FormField[]
     */
    public function getFields();

    /**
     * @param FormValidation $formValidation
     * @return void
     */
    public function addValidation(FormValidation $formValidation);

    /**
     * @param string $validationName
     * @return void
     */
    public function removeValidation($validationName);

    /**
     * @return bool
     */
    public function validate();

    /**
     * @return string[]
     */
    public function getData();

    /**
     * @return FormError[]
     */
    public function getErrors();
}