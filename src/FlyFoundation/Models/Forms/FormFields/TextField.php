<?php

namespace FlyFoundation\Models\Forms\FormFields;

class TextField extends FormField{

    /**
     * @return string
     */
    public function getFieldHTML()
    {
        $name = $this->getName();
        $classString = implode(' ',$this->getClasses());
        $value = $this->getValue();

        $html = '<input name="'.$name.'" class="'.$classString.'" type="text" value="'.$value.'" />';

        return $html;
    }

}