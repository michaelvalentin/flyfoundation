<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\Response;

interface BaseController{

    public function beforeApp();

    public function beforeController();

    public function afterController();

    public function afterApp();
}