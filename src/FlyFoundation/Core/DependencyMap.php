<?php


namespace FlyFoundation\Core;


use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Util\Map;
use FlyFoundation\Util\ValueList;

//!!TODO: The dependency instance should not be an instance, but a closure (anonymous function) producing the instance...

class DependencyMap extends Map{
    public function putDependency($traitName, $dependencyInstance, $singleton = false)
    {
        $startsWithBackslash = preg_match("/^\\\/",$traitName);

        if($startsWithBackslash){
            throw new InvalidArgumentException(
                "The traitName '$traitName' should not start with a backslash.
                It is not a valid class name, and will not be matched by the
                dependency loader"
            );
        }

        parent::put($traitName,[$dependencyInstance,$singleton]);
    }

    public function put($key, $value)
    {
        $this->putDependency($key, $value);
    }
} 