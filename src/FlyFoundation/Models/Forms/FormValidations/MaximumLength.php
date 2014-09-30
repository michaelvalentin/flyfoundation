<?php

namespace FlyFoundation\Models\Forms\FormValidations;


class MaximumLength extends FormValidation{

    /**
     * @var int
     */
    private $limit;

    public function __construct()
    {
        $this->limit = 0;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        foreach($this->fields as $field){
            if(count($field->getValue()) > $this->limit) return false;
        }
        return true;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }
} 