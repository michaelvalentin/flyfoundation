<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

namespace FlyFoundation\Models;

use FlyFoundation\SystemDefinitions\EntityDefinition;

class OpenPersistentEntity extends PersistentEntity
{

    public function __construct(EntityDefinition $entityDefinition, array $data = array())
    {
        $this->entityDefinition = $entityDefinition;
        $this->columnValuePairs = $data;
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
        // TODO: Implement getId() method.
    }

    /**
     * @return array
     */
    public function asArray()
    {
        // TODO: Implement asArray() method.
    }

    public function fromArray(array $data)
    {
        // TODO: Implement fromArray() method.
    }
}