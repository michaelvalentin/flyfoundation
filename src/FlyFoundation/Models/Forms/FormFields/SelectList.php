<?php

namespace FlyFoundation\Models\Forms\FormFields;


class SelectList extends FormField {

    private $options;

    /**
     * @return string
     */
    public function getFieldHTML()
    {
        $name = $this->getName();
        $classes = implode(' ', $this->getClasses());
        $value = $this->getValue();

        $html = '<select name="'.$name.'" class="'.$classes.'">';
        foreach($this->options as $option){
            $selected = ($option === $value ? ' selected="selected"' : '');
            $html .= '<option'.$selected.'>'.$option.'</option>';
        }
        $html .= '</select>';

        return $html;
    }

    public function setOptions($options){
        $this->options = $options;
    }
}