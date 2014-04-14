<?php


namespace FlyFoundation\Models;


use FlyFoundation\SystemDefinitions\EntityDefinition;

interface Entity {
    public function __construct(EntityDefinition $entityDefinition, array $data = array());

    public function getDefinition();
} 