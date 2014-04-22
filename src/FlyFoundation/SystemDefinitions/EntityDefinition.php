<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Exceptions\InvalidArgumentException;

class EntityDefinition extends DefinitionComponent{

    protected $name;
    /** @var EntityField[] */
    protected $fields;
    protected $validations;
    protected $indexes;

    public function getName()
    {
        $this->requireFinalized();
        return $this->name;
    }

    public function getFields()
    {
        $this->requireFinalized();
        return $this->fields;
    }

    public function getPersistentFields()
    {
        $this->requireFinalized();
        if(!is_array($this->fields)){
            return [];
        }
        $result = [];
        foreach($this->fields as $field){
            if($field instanceof PersistentField){
                $result[] = $field;
            }
        }
        return $result;
    }

    public function getRelationFields()
    {
        $this->requireFinalized();
        if(!is_array($this->fields)){
            return [];
        }
        $result = [];
        foreach($this->fields as $field){
            if($field instanceof RelationField){
                $result[] = $field;
            }
        }
        return $result;
    }

    public function getCalculatedFields()
    {
        if(!is_array($this->fields)){
            return [];
        }
        $result = [];
        foreach($this->fields as $field){
            if($field instanceof CalculatedField){
                $result[] = $field;
            }
        }
        return $result;
    }

    public function getField($name)
    {
        $this->requireFinalized();
        foreach($this->getFields() as $field){
            if($field->getName() == $name){
                return $field;
            }
        }
        throw new InvalidArgumentException("No field '".$name."' exists in the definition '".$this->getName()."'");
    }

    public function getValidations()
    {
        $this->requireFinalized();
        return $this->validations;
    }

    public function getIndexes()
    {
        $this->requireFinalized();
        return $this->indexes;
    }

    public function finalize()
    {
        parent::finalize();
        //TODO: Implement
    }

    protected function applyFields($fieldsData){
        foreach($fieldsData as $fieldData){
            $field = $this->getFactory()->load("\\FlyFoundation\\SystemDefinitions\\PersistentField");
            $field->applyOptions($fieldData);
            $field->setEntityDefinition($this);
            $this->fields[] = $field;
        }
    }
}