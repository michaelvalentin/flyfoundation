<?php

namespace FlyFoundation\Models;
use Exceptions\InvalidArgumentException;

/**
 * Class JsonContentModel
 *
 * A simple model to load data from a Json file in the contents folder
 *
 * @package Models
 */
class JsonContentModel implements Model{

    private $model_file;
    private $data;

    /**
     * @param bool $model_name The name of the json file to load without extension
     */
    public function __construct($model_name = false){
        if($model_name){
            $this->SetModelFile($model_name);
        }
    }

    /**
     * @param $model_name The name of the json file to load without extension
     */
    public function SetModelFile($model_name){
        $filename = BASEDIR.DS."..".DS."content".DS.$model_name.".json";
        if(file_exists($filename)) $this->model_file = $filename;
    }

    /**
     * Get the data of this model as an array
     *
     * @return array
     */
    public function asArray()
    {
        if($this->data == null){
            $this->readDataFromJsonModel();
        }
        return $this->data;
    }

    public function fromArray(array $data)
    {
        $this->data = $data;
    }

    private function readDataFromJsonModel()
    {
        if(file_exists($this->model_file)){
            throw new InvalidArgumentException('File: "'.$this->model_file.'" does not exist, and the JSON model '+
            'can not render as expected.');
        }
        $this->data = json_decode(file_get_contents($this->model_file),true);
        if(json_last_error() != JSON_ERROR_NONE){
            //TODO: Set debug warning with json_last_error_msg() included
        }
    }
}