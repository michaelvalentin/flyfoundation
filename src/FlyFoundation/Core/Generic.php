<?php


namespace FlyFoundation\Core;


interface Generic {

    /**
     * @return string
     */
    public function getEntityName();

    /**
     * @return void
     */
    public function afterConfiguration();
} 