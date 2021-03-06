<?php

namespace Basefly\Controllers;


use FlyFoundation\Controllers\AbstractController;
use FlyFoundation\Core\Response;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Factory;
use FlyFoundation\Models\Forms\GenericForm;

class FormController extends AbstractController
{
    use AppConfig;

    /**
     * @var GenericForm
     */
    private $form;

    public function view(array $arguments)
    {

        $this->form = Factory::load('\\FlyFoundation\\Models\\Forms\\GenericForm');

        $this->form->addTextField()
            ->setName('field_a')
            ->setLabel('Field A')
            ->addClass('input-text')
            ->setRequired('This field is required!');

        $this->form->addTextField()
            ->setName('field_b')
            ->addClass('input-text')
            ->setLabel('Field B');

        $this->form->addSelectList()
            ->setName('field_c')
            ->setLabel('Field C')
            ->setOptions(array('Value 1', 'Value 2', 'Value 3'));

        $this->form->addTextArea()
            ->setName('field_d')
            ->setLabel('Field D')
            ->addClass('input-text')
            ->setRequired('This field is required!');

        if($data = $this->form->getData()){

            $this->getAppResponse()->data->put('data', $data);

        }

        $this->getAppResponse()->data->put('form', $this->form->asArray());

        $this->getAppResponse()->wrapInTemplateFile('form');
    }

    public function viewRespondsTo(array $arguments)
    {
        return true;
    }
} 