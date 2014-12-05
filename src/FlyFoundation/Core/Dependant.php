<?php


namespace FlyFoundation\Core;


interface Dependant {
    /**
     * @return void
     */
    public function onDependenciesLoaded();
} 