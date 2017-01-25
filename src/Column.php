<?php

namespace Larapack\DoctrineSupport;

use Doctrine\DBAL\Schema\Column as DoctrineColumn;

class Column extends DoctrineColumn
{
    /**
     * Set allowed enum options.
     *
     * @param array $options
     *
     * @return $this
     */
    public function setAllowed(array $options)
    {
        $this->setCustomSchemaOption('options', $options);

        return $this;
    }
}
