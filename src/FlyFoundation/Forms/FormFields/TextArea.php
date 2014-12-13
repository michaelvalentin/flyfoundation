<?php

namespace FlyFoundation\Forms\FormFields;


class TextArea extends FormField {
    /**
     * @return string
     */
    public function getFieldHTML()
    {
        $name = $this->getName();
        $classes = implode(' ', $this->getClasses());
        $value = $this->getValue();
        $html = '<textarea name="'.$name.'" class="'.$classes.'">'.$value.'</textarea>';
        return $html;
    }
} 