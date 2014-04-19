<?php


namespace FlyFoundation\SystemDefinitions;


abstract class DefinitionComponent {

    public function applyOptions(array $options)
    {

    }

    public abstract function finalize();
} 