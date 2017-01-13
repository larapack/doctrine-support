<?php

namespace Larapack\DoctrineSupport\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnumType extends Type
{
    const ENUM = 'enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $options = array_get($fieldDeclaration, 'options', []);
        $optionsString = count($options) ? "'".implode("','", $options)."'" : "''";

        return "ENUM({$optionsString})";
    }

    public function getName()
    {
        return static::ENUM;
    }
}