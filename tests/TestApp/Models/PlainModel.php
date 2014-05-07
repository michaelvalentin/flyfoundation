<?php


namespace TestApp\Models;


use FlyFoundation\Models\Model;

class PlainModel implements Model{

    private $data;

    /**
     * @return array
     */
    public function asArray()
    {
        if($this->data !== null){
            return $this->data;
        }
        return [];
    }

    public function fromArray(array $data)
    {
        $this->data = $data;
    }
}