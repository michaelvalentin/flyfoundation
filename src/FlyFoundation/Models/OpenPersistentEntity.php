<?php

namespace FlyFoundation\Models;

use FlyFoundation\SystemDefinitions\EntityDefinition;

class OpenPersistentEntity extends PersistentEntity
{

    public function __construct(EntityDefinition $entityDefinition, array $data = array())
    {
        $this->entityDefinition = $entityDefinition;
        foreach($data as $key=>$value){
            $this->set($key, $value);
        }
    }

    public function getDefinition()
    {
        return $this->entityDefinition;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->getPersistentValue($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->setPersistentValue($key, $value);
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return $this->columnValuePairs;
    }
}