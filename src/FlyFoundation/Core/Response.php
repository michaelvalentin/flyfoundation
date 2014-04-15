<?php


namespace FlyFoundation\Core;


interface Response {
    public function output($responseType = "Html");

    public function asArray();
} 