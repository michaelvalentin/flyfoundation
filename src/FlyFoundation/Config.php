<?php

namespace FlyFoundation;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Util\ClassMap;
use FlyFoundation\Util\DirectoryList;
use FlyFoundation\Util\ValueList;

/**
 * Class Config
 *
 * Configurations that are global and NOT user editable. The configuration must be locked at application
 * runtime, and is from that point immutable.
 *
 * @package Core
 */
class Config {
    private $data;
    private $locked;

    /**
     * @var Util\ClassMap
     */
    public $classOverrides;

    /**
     * @var Util\ValueList
     */
    public $baseSearchPaths;

    /**
     * @var Util\ValueList
     */
    public $modelSearchPaths;

    /**
     * @var Util\ValueList
     */
    public $viewSearchPaths;

    /**
     * @var Util\ValueList
     */
    public $controllerSearchPaths;

    /**
     * @var Util\ValueList
     */
    public $databaseSearchPaths;

    /**
     * @var Util\DirectoryList
     */
    public $templateDirectories;

    public function __construct(){
        $this->data = array();
        $this->locked = false;
        $this->classOverrides = new ClassMap();
        $this->baseSearchPaths = new ValueList();
        $this->modelSearchPaths = new ValueList();
        $this->viewSearchPaths = new ValueList();
        $this->controllerSearchPaths = new ValueList();
        $this->databaseSearchPaths = new ValueList();
        $this->templateDirectories = new DirectoryList();
    }

    public function set($key,$value){
        $this->data[$key] = $value;
    }

    public function get($key){
        if($this->isLocked()){
            throw new InvalidOperationException("Configuration is locked, and can not be modified");
        }
        return $this->data[$key];
    }

    public function setMany(array $data){
        if($this->isLocked()){
            throw new InvalidOperationException("Configuration is locked, and can not be modified");
        }
        $this->data = array_merge($this->data,$data);
    }

    public function getAll(){
        return $this->data;
    }

    public function lock(){
        $this->locked = true;
    }

    public function isLocked(){
        return $this->locked;
    }
}