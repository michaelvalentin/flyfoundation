<?php


namespace FlyFoundation\Util;


class ValueList implements Collection{

    private $data;

    public function __construct(array $data = array())
    {
        $this->data = array();
        foreach($data as $d){
            $this->add($d);
        }
    }

    public function add($element)
    {
        $this->data[] = $element;
    }

    public function addAll(array $elements)
    {
        foreach($elements as $element)
        {
            $this->add($element);
        }
    }

    public function remove($element)
    {
        foreach($this->data as $index => $entry)
        {
            if($element == $entry){
                unset($this->data[$index]);
            }
        }
    }

    public function clear()
    {
        $this->data = array();
    }

    public function contains($element){
        return in_array($element,$this->data);
    }

    /**
     * @return int
     */
    public function size()
    {
        return count($this->data);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->data) < 1;
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return $this->data;
    }
}