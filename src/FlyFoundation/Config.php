<?php

namespace FlyFoundation;
use FlyFoundation\Core\DependencyMap;
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

    /** @var \FlyFoundation\Util\ValueList  */
    public $modelSearchPaths;

    /** @var \FlyFoundation\Util\ValueList  */
    public $viewSearchPaths;

    /** @var \FlyFoundation\Util\ValueList  */
    public $controllerSearchPaths;

    /** @var \FlyFoundation\Util\ValueList  */
    public $databaseSearchPaths;

    /** @var \FlyFoundation\Util\DirectoryList */
    public $pageDirectories;

    /** @var \FlyFoundation\Util\DirectoryList */
    public $templateDirectories;

    /** @var \FlyFoundation\Core\RoutingList  */
    public $routing;

    /** @var  DependencyMap */
    public $dependencies;

    /** @var ClassMap */
    public $implementations;

    public function __construct(){
        $this->data = array();
        $this->locked = false;
        $this->modelSearchPaths = new ValueList();
        $this->viewSearchPaths = new ValueList();
        $this->controllerSearchPaths = new ValueList();
        $this->databaseSearchPaths = new ValueList();
        $this->pageDirectories = new DirectoryList();
        $this->templateDirectories = new DirectoryList();
        $this->routing = new RoutingList();
        $this->baseController = null;
        $this->dependencies = new DependencyMap();
        $this->implementations = new ClassMap();
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

    public function get($key, $default = null)
    {
        if(!isset($this->data[$key])){
            return $default;
        }
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

    public function getImplementation($className)
    {
        while($this->implementations->containsKey($className)){
            $className = $this->implementations->get($className);
        }
        return $className;
    }
}