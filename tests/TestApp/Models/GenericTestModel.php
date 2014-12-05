<?php


namespace TestApp\Models;


use FlyFoundation\Models\EntityFields\IntegerField;
use FlyFoundation\Models\EntityFields\TextField;
use FlyFoundation\Models\EntityValidations\Required;
use FlyFoundation\Models\OpenGenericEntity;

class GenericTestModel extends OpenGenericEntity{
    public function afterConfiguration()
    {
        parent::afterConfiguration();

        $field1 = new TextField();
        $field1->setName("test");
        $validation1 = new Required();
        $validation1->setName("require-demo");
        $validation1->setFields([$field1]);

        $field2 = new TextField();
        $field2->setName("demo");

        $field3 = new IntegerField();
        $field3->setName("id");

        $this->addField($field1);
        $this->addField($field2);
        $this->addField($field3);
        $this->addValidation($validation1);
    }
} 