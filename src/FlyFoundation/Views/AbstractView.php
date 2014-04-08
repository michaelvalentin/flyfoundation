<?php


namespace FlyFoundation\Views;


use FlyFoundation\Models\Model;

abstract class AbstractView implements View{
    private $data = array();

    public function setData(array $data){
        $this->data = array_merge($this->data,$data);
    }

    public function getData(){
        return $this->data;
    }

    public function setValue($key, $value){
        $this->data[$key] = $value;
    }

    public function getValue($key){
        return $this->data[$key];
    }

    public function output(){
        return $this->prepareData($this->data);
    }

    private function prepareData(array $data)
    {
        foreach($data as $l=>$v){
            if(is_array($v)){
                $data[$l] = $this->prepareData($v);
            }else{
                $data[$l] = $this->prepareValue($v);
            }
        }

        return $data;
    }

    private function prepareValue($value)
    {
        //TODO: Implement
        if($value instanceof Model){
            return $this->prepareData($value->asArray());
        }elseif(is_object($value)){
            return (string) $value;
        }else{
            return $value;
        }
    }
} 