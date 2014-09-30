<?php

namespace Basefly\Controllers;


use FlyFoundation\Controllers\AbstractController;
use FlyFoundation\Core\Response;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Models\Forms\Builders\TextFieldBuilder;
use FlyFoundation\Models\Forms\FormFields\TextField;
use FlyFoundation\Models\Forms\GenericForm;

class FormController extends AbstractController
{
    use AppConfig;

    public function view(Response $response, array $arguments)
    {

        $form = new GenericForm();

        $textFieldA = new TextField();
        $textFieldB = new TextField();

        $textFieldABuilder = new TextFieldBuilder($form, $textFieldA);
        $textFieldABuilder
            ->setName('field_a')
            ->setLabel('Field A')
            ->addClass('input-text')
            ->setRequired('This field is required!');

        $textFieldBBuilder = new TextFieldBuilder($form, $textFieldB);
        $textFieldBBuilder
            ->setName('field_b')
            ->addClass('input-text')
            ->setLabel('Field B');


        $response->setDataValue('form', $form->asArray());

        $response->setDataValue('test', 'Awesome');

        $response->wrapInTemplateFile('form');

        return $response;
    }
} 