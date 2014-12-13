<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Exceptions\InvalidClassException;
use FlyFoundation\Exceptions\NotImplementedException;
use FlyFoundation\Factory;
use FlyFoundation\Forms\GenericForm;
use FlyFoundation\Models\EntityFields\BooleanField;
use FlyFoundation\Models\EntityFields\DateField;
use FlyFoundation\Models\EntityFields\DateTimeField;
use FlyFoundation\Models\EntityFields\EntityField;
use FlyFoundation\Models\EntityFields\FloatField;
use FlyFoundation\Models\EntityFields\IntegerField;
use FlyFoundation\Models\EntityFields\TextField;
use FlyFoundation\Models\EntityValidations\MaximumLength;
use FlyFoundation\Models\EntityValidations\MinimumLength;
use FlyFoundation\Models\EntityValidations\Required;
use FlyFoundation\Models\GenericEntity;
use FlyFoundation\SystemDefinitions\EntityDefinition;
use FlyFoundation\SystemDefinitions\FieldDefinition;
use FlyFoundation\SystemDefinitions\FieldType;
use FlyFoundation\SystemDefinitions\ValidationDefinition;
use FlyFoundation\SystemDefinitions\ValidationType;
use Guzzle\Common\Exception\InvalidArgumentException;

class FormFactory extends AbstractFactory
{

    public function __construct()
    {
        $this->genericNamingRegExp = "/^(?<entityname>.*)(?<formtype>Create|Update)Form$/";
        $this->genericClassName = "\\FlyFoundation\\Forms\\GenericForm";
        $this->genericInterface = "\\FlyFoundation\\Forms\\GenericForm";
    }

    protected function prepareGenericEntity($result, $entityName)
    {
        /** @var GenericForm $result */
        //TODO: Prepare properly!
        return $result;
    }

    protected function prepareGenericEntityWithDefinition($entity, EntityDefinition $entityDefinition)
    {
        $formType = $this->getFormType();
        $methodName = "prepare".$formType."Form";
        return $this->$methodName($entity, $entityDefinition);
    }

    private function prepareCreateForm(GenericForm $form, EntityDefinition $entityDefinition)
    {
        foreach($entityDefinition->getFieldDefinitions() as $field)
        {
            if(!$field->getSetting("NoFormField"))
            {
                $this->addFormField($form, $field);
            }
        }

        return $form;
    }

    private function prepareUpdateForm(GenericForm $form, EntityDefinition $entityDefinition)
    {
        return $this->prepareCreateForm($form, $entityDefinition);
    }

    private function getFormType()
    {
        if(isset($this->genericNamingMatches["formtype"])){
            return $this->genericNamingMatches["formtype"];
        }else{
            return "Create";
        }
    }

    private function addFormField(GenericForm $form, FieldDefinition $field)
    {
        /** @var \FlyFoundation\Forms\FormFields\TextField $formField */
        $formField = Factory::load("\\FlyFoundation\\Forms\\FormFields\\TextField");
        $formField->setName($field->getName());
        $formField->setLabel($field->getName().": ");
        $form->addField($formField);
        //TODO: Implement something more sophisticated!
    }
}