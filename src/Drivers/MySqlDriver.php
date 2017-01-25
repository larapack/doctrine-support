<?php

namespace Larapack\DoctrineSupport\Drivers;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOMySql\Driver;
use Larapack\DoctrineSupport\Managers\MySqlSchemaManager;

class MySqlDriver extends Driver
{
    /**
     * {@inheritdoc}
     */
    public function getSchemaManager(Connection $conn)
    {
        return new MySqlSchemaManager($conn);
    }
}
