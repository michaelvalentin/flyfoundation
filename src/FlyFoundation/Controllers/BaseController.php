<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\Response;
use FlyFoundation\Core\SystemQuery;

interface BaseController{

    public function beforeApp();

    public function beforeController(SystemQuery $query);

    public function afterController(SystemQuery $query);

    public function afterApp();
}