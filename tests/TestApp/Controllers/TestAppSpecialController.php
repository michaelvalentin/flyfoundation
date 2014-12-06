<?php


namespace TestApp\Controllers;


use FlyFoundation\Controllers\AbstractController;
use FlyFoundation\Core\Response;

class TestAppSpecialController extends AbstractController{
    public function show(array $arguments)
    {
        return true;
    }

    public function delete(array $arguments)
    {
        return true;
    }

    public function showAll(array $arguments)
    {
        return true;
    }

    public function showFrontPage(array $arguments)
    {
        return true;
    }
} 