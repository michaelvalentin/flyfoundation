<?php


namespace FlyFoundation\Views;


use FlyFoundation\Core\Generic;

class GenericView extends AbstractView implements Generic{

    protected $entityName;

    /**
     * @param string $name
     */
    public function setEntityName($name)
    {
        $this->entityName = $name;
    }

    /**
     * @return void
     */
    public function afterConfiguration()
    {
        // TODO: Implement afterConfiguration() method.
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }
}