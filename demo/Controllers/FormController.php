<?php

namespace Basefly\Controllers;


use FlyFoundation\Controllers\AbstractController;
use FlyFoundation\Core\Response;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Models\Forms\Required;
use FlyFoundation\Models\Forms\StandardForm;
use FlyFoundation\Models\Forms\TextField;

class FormController extends AbstractController
{
    use AppConfig;

    public function view(Response $response, array $arguments)
    {

        $form = new StandardForm();

        $title = new TextField();

        $title->setName('title');
        $title->setLabel('Set the title');
        $title->addClass('input-text required');

        $form->addField($title);

        $required = new Required();
        $required->setName('required');
        $required->setFields($form->getFields());

        $form->addValidation($required);

        $response->setDataValue('form', $form->asArray());

        $response->setContent("{{#form}}{{#fields}}{{name}}{{/fields}}{{/form}}");

        return $response;
    }
} 