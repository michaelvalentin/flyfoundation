<?php

namespace Models;

/**
 * Class JsonContentModel
 *
 * A simple model to load data from a Json file in the contents folder
 *
 * @package Models
 */
class JsonContentModel implements \Models\Abstracts\IModel{

    protected $model_file;

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
    public function AsArray()
    {
        if($this->model_file){
            return json_decode(file_get_contents($this->model_file),true);
        }else{
            return array();
        }
    }
}