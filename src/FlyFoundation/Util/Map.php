<?php


namespace FlyFoundation\Util;


class Map implements Collection{
    private $data;

    public function __construct(array $data = array()){
        $this->data = $data;
    }

    public function clear(){
        $this->data = array();
    }

    public function containsKey($key){
        return array_key_exists($key, $this->data);
    }

    public function contains($value){
        return in_array($value,$this->data);
    }

    public function put($key, $value){
        $this->data[$key] = $value;
    }

    public function putAll(array $data){
        foreach($data as $k=>$v){
            $this->put($k,$v);
        }
    }

    public function remove($key){
        if($this->containsKey($key)){
            unset($this->data[$key]);
        }
    }

    public function get($key){
        if(!$this->containsKey($key)){
            return null;
        }
        return $this->data[$key];
    }

    public function size(){
        return count($this->data);
    }

    public function isEmpty(){
        return count($this->data) < 1;
    }

    public function asArray(){
        return $this->data;
    }
}