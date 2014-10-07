<?php

namespace FlyFoundation\Database;


class GenericCondition implements Condition
{
    private $type;
    private $invert;
    private $values;
    private $fieldName;

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
    }

    public function equal($value)
    {
        $this->type = 'equal';
        $this->values = array($value);
    }

    public function lessThan($value)
    {
        $this->type = 'lessThan';
        $this->values = array($value);
    }

    public function greaterThan($value)
    {
        $this->type = 'greaterThan';
        $this->values = array($value);
    }

    public function lessThanOrEqual($value)
    {
        $this->type = 'lessThanOrEqual';
        $this->values = array($value);
    }

    public function greaterThanOrEqual($value)
    {
        $this->type = 'greaterThanOrEqual';
        $this->values = array($value);
    }

    public function isLike($value)
    {
        $this->type = 'isLike';
        $this->values = array($value);
    }

    public function isNull()
    {
        $this->type = 'isNull';
    }

    public function isTrue()
    {
        $this->type = 'isTrue';
    }

    public function isIn(array $values)
    {
        $this->type = 'isIn';
        $this->values = $values;
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

    private function buildString()
    {
        $string = $this->quote($this->fieldName).' ';
        switch($this->type){
            case 'equal':
                if($this->invert) $string .= '!= ?';
                else $string .= '== ?';
                break;

            case 'lessThan':
                if($this->invert) $string .= '> ?';
                else $string .= '< ?';
                break;

            case 'greaterThan':
                if($this->invert) $string .= '< ?';
                else $string .= '> ?';
                break;

            case 'lessThanOrEqual':
                if($this->invert) $string .= '>= ?';
                else $string .= '<= ?';
                break;

            case 'greaterThanOrEqual':
                if($this->invert) $string .= '<= ?';
                else $string .= '>= ?';
                break;

            case 'isLike':
                if($this->invert) $string .= 'NOT LIKE ?';
                else $string .= 'LIKE ?';
                break;

            case 'isNull':
                if($this->invert) $string .= 'IS NOT NULL';
                else $string .= 'IS NULL';
                break;

            case 'isTrue':
                if($this->invert) $string .= 'IS NOT TRUE';
                else $string .= 'IS TRUE';
                break;

            case 'isIn':
                if($this->invert) $string .= 'NOT IN(';
                else $string .= 'IN(';
                foreach($this->values as $i => $value)
                {
                    $string .= '?';
                    if($i+1 != count($this->values)) $string .= ', ';
                    else $string .= ')';
                }
                break;
        }
        return $string;
    }

} 