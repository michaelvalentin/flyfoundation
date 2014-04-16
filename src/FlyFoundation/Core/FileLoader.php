<?php


namespace FlyFoundation\Core;


interface FileLoader {
    public function findFile($path);

    public function findTemplate($name);

    public function findEntityDefinition($name);
} 