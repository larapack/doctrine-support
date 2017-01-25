<?php

namespace Larapack\DoctrineSupport\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class EnumType extends Type
{
    const ENUM = 'enum';

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $options = array_get($fieldDeclaration, 'options', []);
        $optionsString = count($options) ? "'".implode("','", $options)."'" : "''";

        return "ENUM({$optionsString})";
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return static::ENUM;
    }
}
