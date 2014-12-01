<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Util\Enum;

abstract class FieldDefinition extends DefinitionComponent
{
    protected $fieldType;
    /** @var  EntityDefinition */
    private $entityDefinition;

    /**
     * @param EntityDefinition $entityDefinition
     */
    public function setEntityDefinition(EntityDefinition &$entityDefinition)
    {
        $this->entityDefinition = $entityDefinition;
    }

    /**
     * @return EntityDefinition
     */
    public function getEntityDefinition()
    {
        return $this->entityDefinition;
    }

    public function setType($fieldType)
    {
        $this->requireOpen();
        if(!FieldType::isValidType($fieldType)){
            throw new InvalidArgumentException(
                "The field type supplied is not valid. Use eg: FieldType::Integer"
            );
        }
        $this->fieldType = $fieldType;
    }

    public function getType()
    {
        return $this->fieldType;
    }
}

abstract class FieldType extends Enum
{
    const Integer = 1;
    const Float = 2;
    const String = 3;
    const DateTime = 4;
    const Date = 5;
    const Time = 6;
    const Boolean = 7;
}