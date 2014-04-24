<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Exceptions\InvalidArgumentException;

class EntityDefinition extends DefinitionComponent{

    /** @var string */
    protected $name;
    /** @var EntityField[] */
    protected $fields;
    /** @var  EntityValidation[] */
    protected $validations;
    /** @var  EntityIndex[] */
    protected $indexes;

    /**
     * @return string
     */
    public function getName()
    {
        $this->requireFinalized();
        return $this->name;
    }

    /**
     * @return EntityField[]
     */
    public function getFields()
    {
        $this->requireFinalized();
        return $this->fields;
    }

    public function getField($name){
        $this->requireFinalized();
        if(!$this->hasField($name)){
            throw new InvalidArgumentException("The entity definition ".$this->getName()." does not contain a field ".$name);
        }
        return $this->fields[$name];
    }

    public function hasField($name){
        $this->requireFinalized();
        return isset($this->fields[$name]);
    }

    /**
     * @return array
     */
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
        if(!is_array($this->fields) || count($this->fields)<1){
            throw new InvalidArgumentException("An entity definition must have at least one field.");
        }
        parent::finalize();
    }

    protected function applyFields(array $fieldsData)
    {
        foreach($fieldsData as $fieldData)
        {
            $field = $this->getFactory()->load("\\FlyFoundation\\SystemDefinitions\\PersistentField");
            $field->applyOptions($fieldData);
            $field->setEntityDefinition($this);
            if(!isset($fieldData["name"])){
                throw new InvalidArgumentException("A field must have a name to be in an entity definition.");
            }
            $this->fields[$fieldData["name"]] = $field;
        }
    }
}