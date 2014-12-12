<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Exceptions\InvalidClassException;
use FlyFoundation\Factory;
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

class ModelFactory extends AbstractFactory
{

    public function __construct()
    {
        $this->genericNamingRegExp = "/^(.*)$/";
        $this->genericClassName = "\\FlyFoundation\\Models\\OpenGenericEntity";
        $this->genericInterface = "\\FlyFoundation\\Models\\GenericEntity";
    }

    protected function prepareGenericEntity($result, $entityName)
    {
        /** @var GenericEntity $result */
        //TODO: Prepare properly!
        $result->setName($entityName);
        return $result;
    }

    protected function prepareGenericEntityWithDefinition($entity, EntityDefinition $entityDefinition)
    {
        /** @var GenericEntity $entity */
        foreach($entityDefinition->getFieldDefinitions() as $fieldDef)
        {
            $field = $this->buildFieldFromDefinition($fieldDef);
            $entity->addField($field);
        }
        foreach($entityDefinition->getValidationDefinitions() as $validationDef)
        {
            $validation = $this->buildValidationFromDefinition($validationDef);
            $entity->addValidation($validation);
        }
        return $entity;
    }

    // TODO: Consider if building the entity should be done in a seperate class.. Can get a lot more complicated over time.. And one might want to introduce new features..

    private function buildFieldFromDefinition(FieldDefinition $fieldDef)
    {
        $field = $this->createFieldFromType($fieldDef->getType());
        $field->setName($fieldDef->getName());
        return $field;
    }

    private function buildValidationFromDefinition(ValidationDefinition $validationDef)
    {
        $validation = $this->createValidationFromType($validationDef->getType(), $validationDef);
        $validationName = ValidationType::nameFromType($validationDef->getType())."_".implode("_",$validationDef->getFieldNames());
        $validation->setName($validationName);
        $validation->setFieldNames($validationDef->getFieldNames());
        return $validation;
    }

    /**
     * @param $type
     * @return EntityField
     */
    private function createFieldFromType($type)
    {
        switch($type){
            case FieldType::Boolean :
                return new BooleanField();
            case FieldType::String :
                return new TextField();
            case FieldType::Date :
                return new DateField();
            case FieldType::DateTime :
                return new DateTimeField();
            case FieldType::Integer :
                return new IntegerField();
            case FieldType::Float :
                return new FloatField();
            default :
                throw new InvalidArgumentException(
                    "The model factory does not recognise the FieldType ".$type." (".(FieldType::isValidType($type) ? FieldType::nameFromType($type) : "Unknown field type").")"
                );
        }
    }

    private function createValidationFromType($type, ValidationDefinition $validationDefinition)
    {
        switch($type){
            case ValidationType::Required :
                return new Required();
            case ValidationType::MinimumLength :
                $result = new MinimumLength();
                $result->setLimit($validationDefinition->getSetting("Value"));
                return $result;
            case ValidationType::MaximumLength :
                $result = new MaximumLength();
                $result->setLimit($validationDefinition->getSetting("Value"));
                return $result;
            default :
                throw new InvalidArgumentException(
                    "The model factory does not recognise the ValidationType ".$type." (".(ValidationType::isValidType($type) ? ValidationType::nameFromType($type) : "Unknown validation type").")"
                );
        }
    }
}