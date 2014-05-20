<?php


namespace FlyFoundation\Core;


use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Util\Map;
use FlyFoundation\Util\ValueList;

class DependencyMap extends Map{
    public function putDependency($traitName, $dependencyInstance, $singleton = false)
    {
        parent::put($traitName,[$dependencyInstance,$singleton]);
    }

    public function put($key, $value)
    {
        $this->putDependency($key, $value);
    }
} 