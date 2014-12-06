<?php


namespace TestApp\Controllers;


use FlyFoundation\Controllers\AbstractController;
use FlyFoundation\Core\Response;

class TestAppSpecialController extends AbstractController{
    public function show(array $arguments)
    {
    }

    public function showRespondsTo(array $arguments)
    {
        return true;
    }

    public function delete(array $arguments)
    {
    }

    public function deleteRespondsTo(array $arguments)
    {
        return true;
    }

    public function showAll(array $arguments)
    {
    }

    public function showAllRespondsTo(array $arguments)
    {
        return true;
    }

    public function showFrontPage(array $arguments)
    {
    }

    public function showFrontPageRespondsTo(array $arguments)
    {
        return true;
    }
} 