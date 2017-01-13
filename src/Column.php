<?php

namespace Larapack\DoctrineSupport;

use Doctrine\DBAL\Schema\Column as DoctrineColumn;

class Column extends DoctrineColumn
{
    public function setAllowed(array $options)
    {
        $this->setCustomSchemaOption('options', $options);

        return $this;
    }
}