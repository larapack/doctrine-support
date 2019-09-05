<?php

namespace Larapack\DoctrineSupport;

use Doctrine\DBAL\Types\Type;
use Illuminate\Database\Connection;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Larapack\DoctrineSupport\Connections\MySqlConnection;
use Larapack\DoctrineSupport\Types\EnumType;

class DoctrineSupportServiceProvider extends ServiceProvider
{
    /**
     * Connections and its types to attach to it.
     *
     * @var array
     */
    protected $types = [
        'mysql' => [
            'enum' => EnumType::class,
        ],
    ];

    /**
     * Register the application services.
     */
    public function register()
    {
        // Set resolver for MySQL
        Connection::resolverFor('mysql', function ($connection, $database, $prefix = '', array $config = []) {
            $connection = new MySqlConnection($connection, $database, $prefix, $config);

            // Add Doctrine types for better support
            $this->addDoctrineTypes($connection);

            return $connection;
        });
    }

    /**
     * Add Doctrine types for the connection.
     *
     * @param Connection $connection
     */
    protected function addDoctrineTypes(Connection $connection)
    {
        $name = $connection->getDriverName();

        foreach (Arr::get($this->types, $name, []) as $type => $handler) {
            if (!Type::hasType($type)) {
                Type::addType($type, $handler);
            }

            $connection->getDoctrineConnection()
                ->getDatabasePlatform()
                ->registerDoctrineTypeMapping($type, $type);
        }
    }
}
