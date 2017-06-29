<?php
/**
 * Licensed to CRATE Technology GmbH("Crate") under one or more contributor
 * license agreements.  See the NOTICE file distributed with this work for
 * additional information regarding copyright ownership.  Crate licenses
 * this file to you under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.  You may
 * obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.  See the
 * License for the specific language governing permissions and limitations
 * under the License.
 *
 * However, if you have executed another commercial license agreement
 * with Crate these terms will supersede the license and you may use the
 * software solely pursuant to the terms of the relevant commercial agreement.
 */
namespace Crate\DBAL\Platforms;

use Crate\DBAL\Types\MapType;
use Crate\DBAL\Types\TimestampType;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Event\SchemaCreateTableColumnEventArgs;
use Doctrine\DBAL\Event\SchemaCreateTableEventArgs;
use Doctrine\DBAL\Events;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Identifier;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\TableDiff;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;

class CratePlatform extends AbstractPlatform
{

    const TIMESTAMP_FORMAT =  'Y-m-d\TH:i:s';
    const TIMESTAMP_FORMAT_TZ =  'Y-m-d\TH:i:sO';

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();
        $this->initializeDoctrineTypeMappings();
        if (!Type::hasType(MapType::NAME)) {
            Type::addType(MapType::NAME, 'Crate\DBAL\Types\MapType');
        }
        if (!Type::hasType(TimestampType::NAME)) {
            Type::addType(TimestampType::NAME, 'Crate\DBAL\Types\TimestampType');
        }
        Type::overrideType('array', 'Crate\DBAL\Types\ArrayType');
    }

    /**
     * {@inheritDoc}
     */
    public function getSubstringExpression($value, $from = 0, $length = null)
    {
        if ($length === null) {
            return 'SUBSTR(' . $value . ', ' . $from . ')';
        }

        return 'SUBSTR(' . $value . ', ' . $from . ', ' . $length . ')';
    }

    /**
     * {@inheritDoc}
     */
    public function getNowExpression()
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegexpExpression()
    {
        return 'LIKE';
    }

    /**
     * {@inheritDoc}
     */
    public function getDateDiffExpression($date1, $date2)
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsSequences()
    {
        return false;
    }

    /**
     * If we want to support Schemas, we need to implement 
     * getListNamespacesSQL and getCreateSchemaSQL methods
     * 
     * {@inheritDoc}
     */
    public function supportsSchemas()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsIdentityColumns()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsIndexes()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsCommentOnStatement()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsForeignKeyConstraints()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsForeignKeyOnUpdate()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsViews()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function prefersSequences()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getListDatabasesSQL()
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getListTablesSQL()
    {
        return "SELECT table_name, schema_name FROM information_schema.tables " .
            "WHERE schema_name = 'doc' OR schema_name = 'blob'";
    }

    /**
     * {@inheritDoc}
     */
    public function getListTableColumnsSQL($table, $database = null)
    {
        $t = explode('.', $table);
        if (count($t) == 1) {
            array_unshift($t, 'doc');
        }
        // todo: make safe
        return "SELECT * from information_schema.columns " .
            "WHERE table_name = '$t[1]' AND schema_name = '$t[0]'";
    }

    /**
     * {@inheritDoc}
     */
    public function getListTableConstraintsSQL($table, $database = null)
    {
        $t = explode('.', $table);
        if (count($t) == 1) {
            array_unshift($t, 'doc');
        }
        // todo: make safe
        return "SELECT constraint_name, constraint_type from information_schema.table_constraints " .
            "WHERE table_name = '$t[1]' AND schema_name = '$t[0]' AND constraint_type = 'PRIMARY_KEY'";
    }

    /**
     * {@inheritDoc}
     */
    public function getAlterTableSQL(TableDiff $diff)
    {
        $sql = array();
        $commentsSQL = array();
        $columnSql = array();

        foreach ($diff->addedColumns as $column) {
            if ($this->onSchemaAlterTableAddColumn($column, $diff, $columnSql)) {
                continue;
            }

            $query = 'ADD ' . $this->getColumnDeclarationSQL($column->getQuotedName($this), $column->toArray());
            $sql[] = 'ALTER TABLE ' . $diff->name . ' ' . $query;
            if ($comment = $this->getColumnComment($column)) {
                $commentsSQL[] = $this->getCommentOnColumnSQL($diff->name, $column->getName(), $comment);
            }
        }

        if (count($diff->removedColumns) > 0) {
            throw DBALException::notSupported("Alter Table: drop columns");
        }
        if (count($diff->changedColumns) > 0) {
            throw DBALException::notSupported("Alter Table: change column options");
        }
        if (count($diff->renamedColumns) > 0) {
            throw DBALException::notSupported("Alter Table: rename columns");
        }

        $tableSql = array();

        if (!$this->onSchemaAlterTable($diff, $tableSql)) {
            if ($diff->newName !== false) {
                throw DBALException::notSupported("Alter Table: rename table");
            }

            $sql = array_merge($sql, $this->_getAlterTableIndexForeignKeySQL($diff), $commentsSQL);
        }

        return array_merge($sql, $tableSql, $columnSql);
    }

    /**
     * {@inheritDoc}
     */
    public function getColumnDeclarationSQL($name, array $field)
    {
        if (isset($field['columnDefinition'])) {
            $columnDef = $this->getCustomTypeDeclarationSQL($field);
        } else {
            $typeDecl = $field['type']->getSqlDeclaration($field, $this);
            $columnDef = $typeDecl;
        }

        return $name . ' ' . $columnDef;
    }

    /**
     * Generate table index column declaration
     * @codeCoverageIgnore
     */
    public function getIndexDeclarationSQL($name, Index $index)
    {
        $columns = $index->getQuotedColumns($this);
        $name = new Identifier($name);

        if (count($columns) == 0) {
            throw new \InvalidArgumentException("Incomplete definition. 'columns' required.");
        }

        return 'INDEX ' . $name->getQuotedName($this) . ' USING FULLTEXT ('. $this->getIndexFieldDeclarationListSQL($columns) . ')';
    }

    /**
     * {@inheritDoc}
     *
     * Crate wants boolean values converted to the strings 'true'/'false'.
     */
    public function convertBooleans($item)
    {
        if (is_array($item)) {
            foreach ($item as $key => $value) {
                if (is_bool($value)) {
                    $item[$key] = ($value) ? 'true' : 'false';
                } elseif (is_numeric($value)) {
                    $item[$key] = ($value > 0) ? 'true' : 'false';
                }
            }
        } else {
            if (is_bool($item)) {
                $item = ($item) ? 'true' : 'false';
            } elseif (is_numeric($item)) {
                $item = ($item > 0) ? 'true' : 'false';
            }
        }

        return $item;
    }

    /**
     * {@inheritDoc}
     */
    public function getBooleanTypeDeclarationSQL(array $field)
    {
        return 'BOOLEAN';
    }

    /**
     * {@inheritDoc}
     */
    public function getIntegerTypeDeclarationSQL(array $field)
    {
        return 'INTEGER';
    }

    /**
     * {@inheritDoc}
     */
    public function getBigIntTypeDeclarationSQL(array $field)
    {
        return 'LONG';
    }

    /**
     * {@inheritDoc}
     */
    public function getSmallIntTypeDeclarationSQL(array $field)
    {
        return 'SHORT';
    }

    /**
     * {@inheritDoc}
     */
    public function getFloatDeclarationSQL(array $field)
    {
        return 'DOUBLE';
    }

    /**
     * {@inheritDoc}
     */
    public function getDecimalTypeDeclarationSQL(array $columnDef)
    {
        return 'DOUBLE';
    }

    /**
     * {@inheritDoc}
     */
    public function getDateTimeTypeDeclarationSQL(array $fieldDeclaration)
    {
        return 'TIMESTAMP';
    }

    /**
     * {@inheritDoc}
     */
    public function getDateTimeTzTypeDeclarationSQL(array $fieldDeclaration)
    {
        return 'TIMESTAMP';
    }

    /**
     * {@inheritDoc}
     */
    public function getDateTypeDeclarationSQL(array $fieldDeclaration)
    {
        return 'TIMESTAMP';
    }

    /**
     * {@inheritDoc}
     */
    public function getTimeTypeDeclarationSQL(array $fieldDeclaration)
    {
        return 'TIMESTAMP';
    }

    /**
     * {@inheritDoc}
     */
    protected function _getCommonIntegerTypeDeclarationSQL(array $columnDef)
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    protected function getVarcharTypeDeclarationSQLSnippet($length, $fixed)
    {
        return 'STRING';
    }

    /**
     * {@inheritDoc}
     */
    public function getClobTypeDeclarationSQL(array $field)
    {
        return 'STRING';
    }

    /**
     * Gets the SQL snippet used to declare an OBJECT column type.
     *
     * @param array $field
     *
     * @return string
     */
    public function getMapTypeDeclarationSQL(array $field, array $options)
    {
        $type = array_key_exists('type', $options) ? $options['type'] : MapType::DYNAMIC;

        $fields = array_key_exists('fields', $options) ? $options['fields'] : array();
        $columns = array();
        foreach ($fields as $field) {
            $columns[$field->getQuotedName($this)] = $this->prepareColumnData($field);
        }
        $objectFields = $this->getColumnDeclarationListSQL($columns);

        $declaration = count($columns) > 0 ? ' AS ( ' . $objectFields . ' )' : '';
        return 'OBJECT ( ' . $type . ' )' . $declaration ;
    }

    /**
     * Gets the SQL snippet used to declare an ARRAY column type.
     *
     * @param array $field
     *
     * @return string
     */
    public function getArrayTypeDeclarationSQL(array $field, array $options)
    {
        $type = array_key_exists('type', $options) ? $options['type'] : Type::STRING;
        return 'ARRAY ( ' . Type::getType($type)->getSQLDeclaration($field, $this) . ' )';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'crate';
    }

    /**
     * {@inheritDoc}
     *
     * PostgreSQL returns all column names in SQL result sets in lowercase.
     */
    public function getSQLResultCasing($column)
    {
        return strtolower($column);
    }

    /**
     * {@inheritDoc}
     */
    public function getDateTimeTzFormatString()
    {
        return self::TIMESTAMP_FORMAT_TZ;
    }

    /**
     * {@inheritDoc}
     */
    public function getDateTimeFormatString()
    {
        return self::TIMESTAMP_FORMAT;
    }

    /**
     * {@inheritDoc}
     */
    public function getDateFormatString()
    {
        return self::TIMESTAMP_FORMAT;
    }

    /**
     * {@inheritDoc}
     */
    public function getTimeFormatString()
    {
        return self::TIMESTAMP_FORMAT;
    }

    /**
     * {@inheritDoc}
     */
    public function getTruncateTableSQL($tableName, $cascade = false)
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getReadLockSQL()
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    protected function initializeDoctrineTypeMappings()
    {
        $this->doctrineTypeMapping = array(
            'short'         => 'smallint',
            'integer'       => 'integer',
            'long'          => 'bigint',
            'int'           => 'integer',
            'bool'          => 'boolean',
            'boolean'       => 'boolean',
            'string'        => 'string',
            'float'         => 'float',
            'double'        => 'float',
            'timestamp'     => 'timestamp',
            'object'        => 'map',
            'string_array'  => 'array',
            'float_array'   => 'array',
            'double_array'  => 'array',
            'integer_array' => 'array',
            'object_array'  => 'array',
            'array'         => 'array',
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getVarcharMaxLength()
    {
        return PHP_INT_MAX;
    }

    /**
     * {@inheritDoc}
     */
    protected function getReservedKeywordsClass()
    {
        return 'Crate\DBAL\Platforms\Keywords\CrateKeywords';
    }

    /**
     * {@inheritDoc}
     */
    public function getBlobTypeDeclarationSQL(array $field)
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * {@inheritDoc}
     * Gets the SQL statement(s) to create a table with the specified name, columns and constraints
     * on this platform.
     *
     * @param Table $table The name of the table.
     * @param integer $createFlags
     *
     * @return array The sequence of SQL statements.
     */
    public function getCreateTableSQL(Table $table, $createFlags = self::CREATE_INDEXES)
    {
        if (!is_int($createFlags)) {
            $msg = "Second argument of CratePlatform::getCreateTableSQL() has to be integer.";
            throw new \InvalidArgumentException($msg);
        }

        if (count($table->getColumns()) === 0) {
            throw DBALException::noColumnsSpecifiedForTable($table->getName());
        }

        $tableName = $table->getQuotedName($this);
        $options = $table->getOptions();
        $options['uniqueConstraints'] = array();
        $options['indexes'] = array();
        $options['primary'] = array();

        if (($createFlags&self::CREATE_INDEXES) > 0) {
            foreach ($table->getIndexes() as $index) {
                /* @var $index Index */
                if ($index->isPrimary()) {
                    $platform = $this;
                    $options['primary'] = array_map(function ($columnName) use ($table, $platform) {
                        return $table->getColumn($columnName)->getQuotedName($platform);
                    }, $index->getColumns());
                    $options['primary_index'] = $index;
                } else if ($index->isUnique()) {
                    throw DBALException::notSupported("Unique constraints are not supported. Use `primary key` instead");
                } else {
                    $options['indexes'][$index->getName()] = $index;
                }
            }
        }

        $columnSql = array();
        $columns = array();

        foreach ($table->getColumns() as $column) {
            if (null !== $this->_eventManager &&
                $this->_eventManager->hasListeners(Events::onSchemaCreateTableColumn)) {

                $eventArgs = new SchemaCreateTableColumnEventArgs($column, $table, $this);
                $this->_eventManager->dispatchEvent(Events::onSchemaCreateTableColumn, $eventArgs);

                $columnSql = array_merge($columnSql, $eventArgs->getSql());

                if ($eventArgs->isDefaultPrevented()) {
                    continue;
                }
            }
            $columns[$column->getQuotedName($this)] = $this->prepareColumnData($column, $options['primary']);
        }

        if (null !== $this->_eventManager && $this->_eventManager->hasListeners(Events::onSchemaCreateTable)) {
            $eventArgs = new SchemaCreateTableEventArgs($table, $columns, $options, $this);
            $this->_eventManager->dispatchEvent(Events::onSchemaCreateTable, $eventArgs);

            if ($eventArgs->isDefaultPrevented()) {
                return array_merge($eventArgs->getSql(), $columnSql);
            }
        }

        $sql = $this->_getCreateTableSQL($tableName, $columns, $options);
        if ($this->supportsCommentOnStatement()) {
            foreach ($table->getColumns() as $column) {
                if ($this->getColumnComment($column)) {
                    $sql[] = $this->getCommentOnColumnSQL(
                        $tableName,
                        $column->getName(),
                        $this->getColumnComment($column)
                    );
                }
            }
        }

        return array_merge($sql, $columnSql);
    }

    /**
     * {@inheritDoc}
     */
    protected function _getCreateTableSQL($tableName, array $columns, array $options = array())
    {
        $columnListSql = $this->getColumnDeclarationListSQL($columns);

        if (isset($options['primary']) && ! empty($options['primary'])) {
            $keyColumns = array_unique(array_values($options['primary']));
            $columnListSql .= ', PRIMARY KEY(' . implode(', ', $keyColumns) . ')';
        }

        if (isset($options['indexes']) && ! empty($options['indexes'])) {
            foreach ($options['indexes'] as $index => $definition) {
                $columnListSql .= ', ' . $this->getIndexDeclarationSQL($index, $definition);
            }
        }
 
        if (isset($options['foreignKeys'])) {
            throw DBALException::notSupported("Create Table: foreign keys");
        }

        $query = 'CREATE TABLE ' . $tableName . ' (' . $columnListSql . ')';
        return array($query);
    }

    /**
     * @param \Doctrine\DBAL\Schema\Column $column The name of the table.
     * @param array List of primary key column names
     *
     * @return array The column data as associative array.
     */
    public function prepareColumnData($column, $primaries = array())
    {
        if ($column->hasCustomSchemaOption("unique") ? $column->getCustomSchemaOption("unique") : false) {
            throw DBALException::notSupported("Unique constraints are not supported. Use `primary key` instead");
        }

        $columnData = array();
        $columnData['name'] = $column->getQuotedName($this);
        $columnData['type'] = $column->getType();
        $columnData['length'] = $column->getLength();
        $columnData['notnull'] = $column->getNotNull();
        $columnData['fixed'] = $column->getFixed();
        $columnData['unique'] = false;
        $columnData['version'] = $column->hasPlatformOption("version") ? $column->getPlatformOption("version") : false;

        if (strtolower($columnData['type']) == "string" && $columnData['length'] === null) {
            $columnData['length'] = 255;
        }

        $columnData['unsigned'] = $column->getUnsigned();
        $columnData['precision'] = $column->getPrecision();
        $columnData['scale'] = $column->getScale();
        $columnData['default'] = $column->getDefault();
        $columnData['columnDefinition'] = $column->getColumnDefinition();
        $columnData['autoincrement'] = $column->getAutoincrement();
        $columnData['comment'] = $this->getColumnComment($column);
        $columnData['platformOptions'] = $column->getPlatformOptions();

        if (in_array($column->getName(), $primaries)) {
            $columnData['primary'] = true;
        }
        return $columnData;
    }

    /**
     * {@inheritDoc}
     */
    public function getCreateDatabaseSQL($database)
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getDropDatabaseSQL($database)
    {
        throw DBALException::notSupported(__METHOD__);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCreateForeignKeySQL(ForeignKeyConstraint $foreignKey, $table)
    {
        throw DBALException::notSupported(__METHOD__);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getGuidTypeDeclarationSQL(array $field)
    {
        throw DBALException::notSupported(__METHOD__);
    }
}
