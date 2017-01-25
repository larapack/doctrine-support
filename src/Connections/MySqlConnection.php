<?php

namespace Larapack\DoctrineSupport\Connections;

use Illuminate\Database\MySqlConnection as IlluminateMySqlConnection;
use Larapack\DoctrineSupport\Drivers\MySqlDriver;

class MySqlConnection extends IlluminateMySqlConnection
{
    /**
     * {@inheritdoc}
     */
    protected function getDoctrineDriver()
    {
        return new MySqlDriver();
    }
}
