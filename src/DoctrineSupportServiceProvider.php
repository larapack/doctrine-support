<?php

namespace Larapack\DoctrineSupport;

use Doctrine\DBAL\Types\Type;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;
use Larapack\DoctrineSupport\Types\EnumType;
use Illuminate\Database\Schema\Grammars\MySqlGrammar;
use Larapack\DoctrineSupport\Connections\MySqlConnection;

class DoctrineSupportServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->registerMySqlDatabaseConnection();
    }

    protected function registerMySqlDatabaseConnection()
    {
        $this->app->singleton('db.connection.mysql', function ($app, $parameters) {
            // First, we list the passes parameters into single
            // variables. I do this because it is far easier
            // to read than using it as eg $parameters[0].
            list($connection, $database, $prefix, $config) = $parameters;

            // Next we can initialize the connection.
            $connection = new MySqlConnection($connection, $database, $prefix, $config);

            // Add Doctrine types for better support
            $this->addDoctrineTypes($connection);

            $connection->setSchemaGrammar(new MySqlGrammar);

            return $connection;
        });
    }

    /**
     * Add Doctrine types for better support.
     *
     * @param Connection $connection
     */
    protected function addDoctrineTypes(Connection $connection)
    {
        $name = $connection->getName();

        foreach (array_get($this->doctrineTypes, $name, []) as $type => $handler) {
            if (! Type::hasType($type)) {
                Type::addType($type, $handler);
            }

            $connection->getDoctrineConnection()
                ->getDatabasePlatform()
                ->registerDoctrineTypeMapping($type, $type);
        }
    }

    protected $doctrineTypes = [
        'mysql' => [
            'enum' => EnumType::class,
        ],
    ];
}