<?php

namespace Larapack\DoctrineSupport\Managers;

use Doctrine\DBAL\Schema\MySqlSchemaManager as DoctrineMySqlSchemaManager;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Str;
use Larapack\DoctrineSupport\Column;

class MySqlSchemaManager extends DoctrineMySqlSchemaManager
{
    /**
     * Gets Table Column Definition for Enum Column Type.
     *
     * @param array $tableColumn
     *
     * @return \Doctrine\DBAL\Schema\Column
     */
    protected function getPortableTableEnumColumnDefinition(array $tableColumn)
    {
        $tableColumn = array_change_key_case($tableColumn, CASE_LOWER);

        $dbType = strtolower($tableColumn['type']);
        $dbType = strtok($dbType, '(), ');

        $type = $this->_platform->getDoctrineTypeMapping($dbType);

        // In cases where not connected to a database DESCRIBE $table does not return 'Comment'
        if (isset($tableColumn['comment'])) {
            $type = $this->extractDoctrineTypeFromComment($tableColumn['comment'], $type);
            $tableColumn['comment'] = $this->removeDoctrineTypeFromComment($tableColumn['comment'], $type);
        }

        $options = [
            'length'        => null,
            'unsigned'      => false,
            'fixed'         => null,
            'default'       => isset($tableColumn['default']) ? $tableColumn['default'] : null,
            'notnull'       => (bool) ($tableColumn['null'] != 'YES'),
            'scale'         => null,
            'precision'     => null,
            'autoincrement' => false,
            'comment'       => isset($tableColumn['comment']) && $tableColumn['comment'] !== ''
                ? $tableColumn['comment']
                : null,
        ];

        $column = new Column($tableColumn['field'], Type::getType($type), $options);

        if (isset($tableColumn['collation'])) {
            $column->setPlatformOption('collation', $tableColumn['collation']);
        }

        $column->setCustomSchemaOption('options', $this->getEnumOptions($tableColumn));

        return $column;
    }

    /**
     * {@inheritdoc}
     */
    protected function _getPortableTableColumnDefinition($tableColumn)
    {
        $keys = array_change_key_case($tableColumn, CASE_LOWER);

        $type = strtolower($keys['type']);
        $type = strtok($type, '(), ');

        $method = Str::camel("get_portable_table_{$type}_column_definition");

        if (method_exists($this, $method)) {
            return $this->$method($tableColumn);
        }

        return parent::_getPortableTableColumnDefinition($tableColumn);
    }

    /**
     * Get enum options from the column.
     *
     * @param $tableColumn
     *
     * @return array
     */
    protected function getEnumOptions($tableColumn)
    {
        $type = $tableColumn['type'];

        if (Str::startsWith($type, 'enum(') && Str::endsWith($type, ')')) {
            return explode("','", trim(substr($type, strlen('enum('), -1), "'"));
        }

        return [];
    }
}
