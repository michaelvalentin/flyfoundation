<?php


namespace FlyFoundation\Core;


interface FileLoader {
    public function findFile($fileName);

    public function findTemplate($name);

    public function findEntityDefinition($name);

    public function findPage($name);
} 