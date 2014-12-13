<?php


namespace Basefly\Controllers;


use FlyFoundation\Controllers\AbstractController;
use FlyFoundation\Factory;

class DemoController extends AbstractController{
    public function create(array $arguments = [])
    {
        $form = Factory::loadForm("BlogPost","Create");

        $this->getAppResponse()->data->put('form', $form->asArray());

        $this->getAppResponse()->wrapInTemplateFile('form');
    }

    public function createRespondsTo(array $arguments = [])
    {
        return true;
    }
} 