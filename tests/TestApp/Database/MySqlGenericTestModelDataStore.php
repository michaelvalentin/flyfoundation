<?php


namespace TestApp\Database;


use FlyFoundation\Database\MySqlGenericDataStore;

class MySqlGenericTestModelDataStore extends MySqlGenericDataStore{
    public function onDependenciesLoaded()
    {
        parent::onDependenciesLoaded();

        $this->getMySqlDatabase()->exec(
            "DROP TABLE IF EXISTS generic_test_model;

             CREATE TABLE IF NOT EXISTS `generic_test_model` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `test` varchar(255) NOT NULL,
                `demo` varchar(255),
                PRIMARY KEY (`id`)
             ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
        );

        $this->setEntityName("generic_test_model");
        $field1 = new \FlyFoundation\Database\Fields\IntegerField();
        $field1->setInIdentifier();
        $field1->setAutoIncrement();
        $field1->setRequired();
        $field1->setName("id");
        $field2 = new \FlyFoundation\Database\Fields\TextField();
        $field2->setName("test");
        $field2->setMaxLength(255);
        $field2->setRequired();
        $field3 = new \FlyFoundation\Database\Fields\TextField();
        $field3->setName("demo");
        $this->addField($field1);
        $this->addField($field2);
        $this->addField($field3);
    }
}