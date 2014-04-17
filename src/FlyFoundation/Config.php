<?php

namespace FlyFoundation;
use FlyFoundation\Core\RoutingList;
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

    /** @var array  */
    private $data;

    /** @var bool  */
    private $locked;

    /** @var \FlyFoundation\Util\ClassMap  */
    public $classOverrides;

    /** @var \FlyFoundation\Util\ValueList  */
    public $baseSearchPaths;

    /** @var \FlyFoundation\Util\ValueList  */
    public $modelSearchPaths;

    /** @var \FlyFoundation\Util\ValueList  */
    public $viewSearchPaths;

    /** @var \FlyFoundation\Util\ValueList  */
    public $controllerSearchPaths;

    /** @var \FlyFoundation\Util\ValueList  */
    public $databaseSearchPaths;

    /** @var \FlyFoundation\Util\ValueList  */
    public $entityDefinitionSearchPaths;

    /** @var Util\DirectoryList */
    public $templateDirectories;

    /** @var \FlyFoundation\Util\DirectoryList  */
    public $entityDefinitionDirectories;

    /** @var \FlyFoundation\Util\DirectoryList  */
    public $baseFileDirectories;

    /** @var \FlyFoundation\Core\RoutingList  */
    public $routing;

    public function __construct(){
        $this->data = array();
        $this->locked = false;
        $this->classOverrides = new ClassMap();
        $this->baseSearchPaths = new ValueList();
        $this->modelSearchPaths = new ValueList();
        $this->viewSearchPaths = new ValueList();
        $this->controllerSearchPaths = new ValueList();
        $this->databaseSearchPaths = new ValueList();
        $this->entityDefinitionSearchPaths = new ValueList();
        $this->templateDirectories = new DirectoryList();
        $this->entityDefinitionDirectories = New DirectoryList();
        $this->baseFileDirectories = new DirectoryList();
        $this->routing = new RoutingList();
    }

    public function set($key,$value)
    {
        if($this->isLocked()){
            throw new InvalidOperationException("Configuration is locked, and can not be modified");
        }
        $this->data[$key] = $value;
    }

    public function setDefault($key,$value)
    {
        if(!isset($this->data[$key])){
            $this->set($key,$value);
        }
    }

    public function get($key)
    {
        return $this->data[$key];
    }

    public function setMany(array $data)
    {
        if($this->isLocked()){
            throw new InvalidOperationException("Configuration is locked, and can not be modified");
        }
        $this->data = array_merge($this->data,$data);
    }

    public function getAll()
    {
        return $this->data;
    }

    public function lock()
    {
        $this->locked = true;
    }

    public function isLocked()
    {
        return $this->locked;
    }
}