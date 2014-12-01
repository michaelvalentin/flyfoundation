<?php


namespace FlyFoundation\LsdParser;


use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\SystemDefinitions\DefinitionComponent;
use FlyFoundation\SystemDefinitions\EntityDefinition;
use FlyFoundation\SystemDefinitions\FieldType;
use FlyFoundation\SystemDefinitions\PersistentFieldDefinition;
use FlyFoundation\SystemDefinitions\SystemDefinition;
use FlyFoundation\SystemDefinitions\ValidationDefinition;
use FlyFoundation\SystemDefinitions\ValidationType;

class DirectiveInterpreter {

    /**
     * @param DirectiveTreeNode $directiveTree
     * @return SystemDefinition
     */
    public function getSystemDefinition(DirectiveTreeNode $directiveTree)
    {
        $systemDefinition = new SystemDefinition();
        $this->addDirectives($directiveTree->getChildNodes(), $systemDefinition);
        return $systemDefinition;
    }

    private function addDirectives(array $directiveTreeNodes, DefinitionComponent &$parentComponent)
    {
        $fullClassName = get_class($parentComponent);
        $className = explode("\\",$fullClassName)[2];
        $methodName = "addDirectiveTo".$className;

        foreach($directiveTreeNodes as $node)
        {
            $this->$methodName($node, $parentComponent);
        }
    }

    private function addDirectiveToSystemDefinition(DirectiveTreeNode $node, SystemDefinition &$systemDefinition)
    {
        switch($node->getType())
        {
            case DirectiveType::Setting :
                $this->addSetting($systemDefinition, $node);
                break;

            case DirectiveType::Entity :
                $entity = new EntityDefinition();
                $entity->setOrigin($node->getOrigin());
                $entity->setName($node->getLabel());
                $this->addDirectives($node->getChildNodes(), $entity);
                $systemDefinition->addEntityDefinition($entity);
                break;

            default :
                throw new InvalidArgumentException(
                    "The system definition can only have sub-directives of type Entity or Setting, and hence the definition in ".$node->getOrigin()." is invalid."
                );
                break;
        }
    }

    private function addDirectiveToEntityDefinition(DirectiveTreeNode $node, EntityDefinition &$entityDefinition)
    {
        switch($node->getType())
        {
            case DirectiveType::Setting :
                $this->addSetting($entityDefinition, $node);
                break;

            case DirectiveType::Validation :
                $validation = new ValidationDefinition();
                $validation->setEntityDefinition($entityDefinition);
                $validation->setOrigin($node->getOrigin());
                $validationType = ValidationType::typeFromName($node->getLabel());
                $validation->setType($validationType);
                if($node->getValue()){
                    $validation->setSetting("Value",$node->getValue());
                }
                $this->addDirectives($node->getChildNodes(), $validation);
                $entityDefinition->addValidationDefinition($validation);
                break;

            case DirectiveType::PersistentEntityField :
                $field = new PersistentFieldDefinition();
                $field->setEntityDefinition($entityDefinition);
                $field->setOrigin($node->getOrigin());
                $field->setName($node->getLabel());
                if(!$node->getValue()){
                    throw new InvalidArgumentException(
                        "The field defined in (".$node->getOrigin().") does not have a type, which is required for entity fields"
                    );
                }
                $fieldType = FieldType::typeFromName($node->getValue());
                if(!$fieldType){
                    throw new InvalidArgumentException(
                        "The field defined in (".$node->getOrigin().") is not a valid type of field, which is required for entity fields"
                    );
                }
                $field->setType($fieldType);
                $this->addDirectives($node->getChildNodes(), $field);
                $entityDefinition->addFieldDefinition($field);
                break;

            default :
                throw new InvalidArgumentException(
                    "Entities can only contain directives of type setting, validation or entity field, and hence the definition in ".$node->getOrigin()." is invalid."
                );
                break;
        }
    }

    private function addDirectiveToValidationDefinition(DirectiveTreeNode $node, ValidationDefinition &$validationDefinition)
    {
        switch($node->getType())
        {
            case DirectiveType::Setting :
                $this->addSetting($validationDefinition, $node);
                break;

            default :
                throw new InvalidArgumentException(
                    "Validations can only contain directives of type setting and hence the definition in ".$node->getOrigin()." is invalid."
                );
                break;
        }
    }

    private function addDirectiveToPersistentFieldDefinition(DirectiveTreeNode $node, PersistentFieldDefinition &$fieldDefinition)
    {
        switch($node->getType())
        {
            case DirectiveType::Setting :
                $this->addSetting($fieldDefinition, $node);
                break;

            case DirectiveType::Validation :
                $validation = new ValidationDefinition();
                $entityDefinition = $fieldDefinition->getEntityDefinition();
                $validation->setEntityDefinition($entityDefinition);
                $validation->setOrigin($node->getOrigin());
                $validationType = ValidationType::typeFromName($node->getLabel());
                if(!$validationType){
                    throw new InvalidArgumentException(
                        "The type of the validation in ".$node->getOrigin()." could not be recognised."
                    );
                }
                $validation->setType($validationType);
                if($node->getValue()){
                    $validation->setSetting("value",$node->getValue());
                }
                $validation->addFieldName($fieldDefinition->getName());
                $this->addDirectives($node->getChildNodes(), $validation);
                $fieldDefinition->getEntityDefinition()->addValidationDefinition($validation);
                break;

            default :
                throw new InvalidArgumentException(
                    "Entity fields can only contain directives of type setting or validation, and hence the definition in ".$node->getOrigin()." is invalid."
                );
                break;
        }
    }

    private function addSetting(DefinitionComponent &$definition, DirectiveTreeNode $node)
    {
        $name = $node->getLabel();
        $value = $this->convertSettingValue($node->getValue());
        $definition->setSetting($name, $value);
    }

    private function convertSettingValue($value)
    {
        $date = \DateTime::createFromFormat("Y-m-d H:i:s", $value);
        if($date){
            return $date;
        }

        return $value;
    }
} 