<?php

namespace Dbml\Dbml;

use Cake\Database\Schema\TableSchema;

class TypeMaps {

    public static function getTypeMap($driverClass) {
        switch ($driverClass) {
            case 'Cake\Database\Driver\Mysql':
                // Stolen from: https://github.com/cakephp/cakephp/blob/4.x/src/Database/Schema/MysqlSchemaDialect.php#L363-L382
                return [
                    TableSchema::TYPE_TINYINTEGER          => 'TINYINT',
                    TableSchema::TYPE_SMALLINTEGER         => 'SMALLINT',
                    TableSchema::TYPE_INTEGER              => 'INTEGER',
                    TableSchema::TYPE_BIGINTEGER           => 'BIGINT',
                    TableSchema::TYPE_BINARY_UUID          => 'BINARY(16)',
                    TableSchema::TYPE_BOOLEAN              => 'BOOLEAN',
                    TableSchema::TYPE_FLOAT                => 'FLOAT',
                    TableSchema::TYPE_DECIMAL              => 'DECIMAL',
                    TableSchema::TYPE_DATE                 => 'DATE',
                    TableSchema::TYPE_TIME                 => 'TIME',
                    TableSchema::TYPE_DATETIME             => 'DATETIME',
                    TableSchema::TYPE_DATETIME_FRACTIONAL  => 'DATETIME',
                    TableSchema::TYPE_TIMESTAMP            => 'TIMESTAMP',
                    TableSchema::TYPE_TIMESTAMP_FRACTIONAL => 'TIMESTAMP',
                    TableSchema::TYPE_TIMESTAMP_TIMEZONE   => 'TIMESTAMP',
                    TableSchema::TYPE_CHAR                 => 'CHAR',
                    TableSchema::TYPE_UUID                 => 'CHAR(36)',
                    TableSchema::TYPE_JSON                 => 'JSON',

                    TableSchema::TYPE_BINARY => 'BLOB',
                    TableSchema::TYPE_STRING => 'VARCHAR',
                    TableSchema::TYPE_TEXT   => 'TEXT',
                ];

            case 'Cake\Database\Driver\Postgres':
                // Stolen from: https://github.com/cakephp/cakephp/blob/4.x/src/Database/Schema/PostgresSchemaDialect.php#L418-L435
                return [
                    TableSchema::TYPE_TINYINTEGER          => 'SMALLINT',
                    TableSchema::TYPE_SMALLINTEGER         => 'SMALLINT',
                    TableSchema::TYPE_BINARY_UUID          => 'UUID',
                    TableSchema::TYPE_BOOLEAN              => 'BOOLEAN',
                    TableSchema::TYPE_FLOAT                => 'FLOAT',
                    TableSchema::TYPE_DECIMAL              => 'DECIMAL',
                    TableSchema::TYPE_DATE                 => 'DATE',
                    TableSchema::TYPE_TIME                 => 'TIME',
                    TableSchema::TYPE_DATETIME             => 'TIMESTAMP',
                    TableSchema::TYPE_DATETIME_FRACTIONAL  => 'TIMESTAMP',
                    TableSchema::TYPE_TIMESTAMP            => 'TIMESTAMP',
                    TableSchema::TYPE_TIMESTAMP_FRACTIONAL => 'TIMESTAMP',
                    TableSchema::TYPE_TIMESTAMP_TIMEZONE   => 'TIMESTAMPTZ',
                    TableSchema::TYPE_UUID                 => 'UUID',
                    TableSchema::TYPE_CHAR                 => 'CHAR',
                    TableSchema::TYPE_JSON                 => 'JSONB',

                    TableSchema::TYPE_BINARY => 'BYTEA',
                    TableSchema::TYPE_STRING => 'VARCHAR',
                    TableSchema::TYPE_TEXT   => 'TEXT',
                ];

            case 'Cake\Database\Driver\Sqlite':
                // Stolen from: https://github.com/cakephp/cakephp/blob/4.x/src/Database/Schema/SqliteSchemaDialect.php#L365-L384
                return [
                    TableSchema::TYPE_BINARY_UUID          => 'BINARY(16)',
                    TableSchema::TYPE_UUID                 => 'CHAR(36)',
                    TableSchema::TYPE_CHAR                 => 'CHAR',
                    TableSchema::TYPE_TINYINTEGER          => 'TINYINT',
                    TableSchema::TYPE_SMALLINTEGER         => 'SMALLINT',
                    TableSchema::TYPE_INTEGER              => 'INTEGER',
                    TableSchema::TYPE_BIGINTEGER           => 'BIGINT',
                    TableSchema::TYPE_BOOLEAN              => 'BOOLEAN',
                    TableSchema::TYPE_FLOAT                => 'FLOAT',
                    TableSchema::TYPE_DECIMAL              => 'DECIMAL',
                    TableSchema::TYPE_DATE                 => 'DATE',
                    TableSchema::TYPE_TIME                 => 'TIME',
                    TableSchema::TYPE_DATETIME             => 'DATETIME',
                    TableSchema::TYPE_DATETIME_FRACTIONAL  => 'DATETIMEFRACTIONAL',
                    TableSchema::TYPE_TIMESTAMP            => 'TIMESTAMP',
                    TableSchema::TYPE_TIMESTAMP_FRACTIONAL => 'TIMESTAMPFRACTIONAL',
                    TableSchema::TYPE_TIMESTAMP_TIMEZONE   => 'TIMESTAMPTIMEZONE',
                    TableSchema::TYPE_JSON                 => 'TEXT',

                    TableSchema::TYPE_BINARY => 'BYTEA',
                    TableSchema::TYPE_STRING => 'VARCHAR',
                    TableSchema::TYPE_TEXT   => 'TEXT',
                ];

            case 'Cake\Database\Driver\Sqlserver':
                // Stolen from: https://github.com/cakephp/cakephp/blob/4.x/src/Database/Schema/SqlserverSchemaDialect.php#L419-L438
                return [
                    TableSchema::TYPE_TINYINTEGER          => 'TINYINT',
                    TableSchema::TYPE_SMALLINTEGER         => 'SMALLINT',
                    TableSchema::TYPE_INTEGER              => 'INTEGER',
                    TableSchema::TYPE_BIGINTEGER           => 'BIGINT',
                    TableSchema::TYPE_BINARY_UUID          => 'UNIQUEIDENTIFIER',
                    TableSchema::TYPE_BOOLEAN              => 'BIT',
                    TableSchema::TYPE_CHAR                 => 'NCHAR',
                    TableSchema::TYPE_FLOAT                => 'FLOAT',
                    TableSchema::TYPE_DECIMAL              => 'DECIMAL',
                    TableSchema::TYPE_DATE                 => 'DATE',
                    TableSchema::TYPE_TIME                 => 'TIME',
                    TableSchema::TYPE_DATETIME             => 'DATETIME2',
                    TableSchema::TYPE_DATETIME_FRACTIONAL  => 'DATETIME2',
                    TableSchema::TYPE_TIMESTAMP            => 'DATETIME2',
                    TableSchema::TYPE_TIMESTAMP_FRACTIONAL => 'DATETIME2',
                    TableSchema::TYPE_TIMESTAMP_TIMEZONE   => 'DATETIME2',
                    TableSchema::TYPE_UUID                 => 'UNIQUEIDENTIFIER',
                    TableSchema::TYPE_JSON                 => 'NVARCHAR(MAX)',

                    TableSchema::TYPE_BINARY => 'VARBINARY',
                    TableSchema::TYPE_STRING => 'NVARCHAR',
                    TableSchema::TYPE_TEXT   => 'TEXT',
                ];

            default:
                throw new \Exception("Unsupported SQL dialect %s", $driverClass);
        }


    }

}
