<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

namespace FlyFoundation\Models;


use FlyFoundation\Database\DataMapper;

abstract class PersistentEntity implements Entity, Model
{
    private $dataMapper;
    private $id;

    /**
     * @return DataMapper
     */
    public function getDataMapper()
    {
        return $this->dataMapper;
    }

    /**
     * @param DataMapper $dataMapper
     */
    public function setDataMapper(DataMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

    /**
     * @return integer
     */
    abstract public function getId();
}