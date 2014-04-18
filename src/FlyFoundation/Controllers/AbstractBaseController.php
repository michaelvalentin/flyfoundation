<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\Response;
use FlyFoundation\Models\Model;
use FlyFoundation\Views\View;

abstract class AbstractBaseController extends AbstractController{

    public abstract function render();
}