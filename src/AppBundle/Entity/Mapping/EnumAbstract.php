<?php
namespace AppBundle\Entity\Mapping;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class EnumAbstract extends Type
{
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $values = array_map(function ($val) {
            return "'" . $val . "'";
        }, $this->getValues());

        return "ENUM(" . implode(", ", $values) . ") COMMENT '(DC2Type:" . $this->getName() . ")'";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, $this->getValues())) {
            throw new \InvalidArgumentException("Invalid '" . $this->getName() . "' value.");
        }
        return $value;
    }

    abstract public function getValues();
}
