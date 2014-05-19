<?php

namespace FlyFoundation\Models;

use FlyFoundation\SystemDefinitions\EntityDefinition;

class OpenPersistentEntity extends PersistentEntity
{

    public function __construct(EntityDefinition $entityDefinition, array $data = array())
    {
        $this->entityDefinition = $entityDefinition;
        $this->fromArray($data);
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
        return $this->columnValuePairs[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->columnValuePairs[$key] = $value;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->get("id");
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return $this->columnValuePairs;
    }

    public function fromArray(array $data)
    {
        $this->columnValuePairs = $data;
    }
}