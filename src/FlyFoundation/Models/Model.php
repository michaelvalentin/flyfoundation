<?php

namespace FlyFoundation\Models;

interface Model {
    /**
     * @return array
     */
    public function asArray();

    public function fromArray(array $data);
} 