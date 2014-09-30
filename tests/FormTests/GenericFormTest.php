<?php

require_once __DIR__ . '/../test-init.php';

use FlyFoundation\Models\Forms\GenericForm;
use FlyFoundation\Models\Forms\FormFields\TextField;
use FlyFoundation\Models\Forms\FormValidations\Required;

class GenericFormTest extends \PHPUnit_Framework_TestCase
{
    public function testRemoveField()
    {
        $form = new GenericForm();

        $fieldA = new TextField();
        $fieldA->setName('fieldA');

        $fieldB = new TextField();
        $fieldB->setName('fieldB');

        $fieldC = new TextField();
        $fieldC->setName('fieldC');

        $form->addField($fieldA);
        $form->addField($fieldB);
        $form->addField($fieldC);

        $form->removeField('fieldB');

        $this->assertArrayNotHasKey('fieldB', $form->getFields());
        $this->assertCount(2, $form->getFields());
    }
}