<?php

namespace FlyFoundation\Models\Forms;


class TextField extends FormField{

    /**
     * @return string
     */
    public function getFieldHTML()
    {
        $classString = implode(' ',$this->getClasses());

        $html = '<input
        name="'.$this->getName().'"
        class="'.$classString.'"
        type="text"
        value="'.(!is_array($this->getValue()) ? $this->getValue() : '').'" />';

        return $html;
    }

    /**
     * @return array
     */
    public function asArray()
    {
        $output = array();

        $output['name'] = $this->getName();
        $output['label'] = $this->getLabel();
        $output['fieldHTML'] = $this->getFieldHTML();
        $output['classes'] = $this->getClasses();
        $output['value'] = $this->getValue();

        return $output;
    }
}