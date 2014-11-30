<?php


namespace FlyFoundation\Util;


use FlyFoundation\Exceptions\InvalidArgumentException;

class Stack
{
    protected $stack;

    public function __construct($values = array()) {
        $this->stack = array_reverse($values);
    }

    public function push($item) {
        array_unshift($this->stack, $item);
    }

    public function pop() {
        if ($this->isEmpty()) {
            throw new InvalidArgumentException('Stack is empty!');
        } else {
            return array_shift($this->stack);
        }
    }

    public function top() {
        return current($this->stack);
    }

    public function isEmpty() {
        return empty($this->stack);
    }
}