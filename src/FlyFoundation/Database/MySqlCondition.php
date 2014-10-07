<?php

namespace FlyFoundation\Database;


class MySqlCondition implements Condition
{
    private $type;
    private $invert;
    private $rawValue;
    private $values;
    private $fieldName;
    private $expression;

    /**
     * @param string $fieldName
     */
    public function __construct($fieldName)
    {
        $this->fieldName = $fieldName;
        $this->values = array();
    }

    public function invert()
    {
        $this->invert = $this->invert != true;
        if(isset($this->type)){
            $this->{$this->type}($this->rawValue);
        }
    }


    public function equal($value)
    {
        $this->type = 'equal';
        $this->setValue($value);
        if($this->invert) $this->expression = '!= ?';
        else $this->expression =  '== ?';
    }

    public function lessThan($value)
    {
        $this->type = 'lessThan';
        $this->setValue($value);
        if($this->invert) $this->expression = '> ?';
        else $this->expression = '< ?';
    }

    public function greaterThan($value)
    {
        $this->type = 'greaterThan';
        $this->setValue($value);
        if($this->invert) $this->expression = '< ?';
        else $this->expression = '> ?';
    }

    public function lessThanOrEqual($value)
    {
        $this->type = 'lessThanOrEqual';
        $this->setValue($value);
        if($this->invert) $this->expression = '>= ?';
        else $this->expression = '<= ?';
    }

    public function greaterThanOrEqual($value)
    {
        $this->type = 'greaterThanOrEqual';
        $this->setValue($value);
        if($this->invert) $this->expression = '<= ?';
        else $this->expression = '>= ?';
    }

    public function isLike($value)
    {
        $this->type = 'isLike';
        $this->setValue($value);
        if($this->invert) $this->expression = 'NOT LIKE ?';
        else $this->expression = 'LIKE ?';
    }

    public function isNull()
    {
        $this->type = 'isNull';
        if($this->invert) $this->expression = 'IS NOT NULL';
        else $this->expression = 'IS NULL';
    }

    public function isTrue()
    {
        $this->type = 'isTrue';
        if($this->invert) $this->expression = 'IS NOT TRUE';
        else $this->expression = 'IS TRUE';
    }

    public function isIn(array $values)
    {
        $this->type = 'isIn';
        $this->setValue($values);
        if($this->invert) $this->expression = 'NOT IN(';
        else $this->expression = 'IN(';
        foreach($this->values as $i => $value)
        {
            $this->expression .= '?';
            if($i+1 != count($this->values)) $this->expression .= ', ';
            else $this->expression .= ')';
        }
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->buildString();
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param string $field
     */
    private function quote($field)
    {
        return "`".$field."`";
    }

    private function setValue($value)
    {
        $this->rawValue = $value;
        if(is_array($value)){
            $this->values = $value;
        } else {
            $this->values = array($value);
        }
    }

    private function buildString()
    {
        return $this->quote($this->fieldName).' '.$this->expression;
    }

} 