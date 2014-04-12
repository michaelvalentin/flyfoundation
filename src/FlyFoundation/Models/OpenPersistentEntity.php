<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

namespace FlyFoundation\Models;

use FlyFoundation\SystemDefinitions\EntityDefinition;

class OpenPersistentEntity extends PersistentEntity
{
    private $entityDefinition;
    private $data;

    public function __construct(EntityDefinition $entityDefinition)
    {
        $this->entityDefinition = $entityDefinition;
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
        return $this->data[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        // TODO: Implement getId() method.
    }
}