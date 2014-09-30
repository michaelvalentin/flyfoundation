<?php


namespace FlyFoundation\Models;


interface Entity {
    public function __construct(array $data = []);

    /**
     * @return EntityField[]
     */
    public function getFields();

    /**
     * @return EntityValidation[]
     */
    public function getValidations();

    /**
     * @return bool
     */
    public function validate();

    /**
     * @return string[]
     */
    public function getErrors();
}