<?php

namespace FlyFoundation\Core;


interface FileHandler {

    public function read($fileName);

    public function write($data);

    public function delete($fileName);

} 