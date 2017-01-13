<?php

namespace Larapack\DoctrineSupport\Connections;

use Illuminate\Database\MySqlConnection as IlluminateMySqlConnection;
use Larapack\DoctrineSupport\Drivers\MySqlDriver;

class MySqlConnection extends IlluminateMySqlConnection
{
    /**
     * Get the Doctrine DBAL driver.
     *
     * @return \Doctrine\DBAL\Driver\PDOMySql\Driver
     */
    protected function getDoctrineDriver()
    {
        return new MySqlDriver;
    }
}