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
namespace Crate\Test\DBAL\Functional\Schema;

use Crate\DBAL\Types\MapType;
use Crate\DBAL\Types\TimestampType;
use Crate\Test\DBAL\DBALFunctionalTestCase;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;

class SchemaManagerTest extends DBALFunctionalTestCase
{
    /**
     * @var \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    protected $_sm;

    public function setUp()
    {
        parent::setUp();
        $this->_sm = $this->_conn->getSchemaManager();
    }

    public function tearDown()
    {
        foreach ($this->_sm->listTableNames() as $tableName) {
            $this->_sm->dropTable($tableName);
        }
    }

    public function testListTables()
    {
        $this->createTestTable('list_tables_test');
        $tables = $this->_sm->listTables();

        $this->assertInternalType('array', $tables);
        $this->assertTrue(count($tables) > 0, "List Tables has to find at least one table named 'list_tables_test'.");

        $foundTable = false;
        foreach ($tables AS $table) {
            $this->assertInstanceOf('Doctrine\DBAL\Schema\Table', $table);
            if (strtolower($table->getName()) == 'list_tables_test') {
                $foundTable = true;

                $this->assertTrue($table->hasColumn('id'));
                $this->assertTrue($table->hasColumn('test'));
                $this->assertTrue($table->hasColumn('foreign_key_test'));
            }
        }

        $this->assertTrue( $foundTable , "The 'list_tables_test' table has to be found.");
    }

    public function createListTableColumns()
    {
        $table = new Table('list_table_columns');
        $table->addColumn('text', Type::STRING);
        $table->addColumn('ts', TimestampType::NAME);
        $table->addColumn('num_float_double', Type::FLOAT);
        $table->addColumn('num_short', Type::SMALLINT);
        $table->addColumn('num_int', Type::INTEGER);
        $table->addColumn('num_long', Type::BIGINT);

        // OBJECT schema definition via platform options
        $mapOpts = array(
            'type' => MapType::STRICT,
            'fields' => array(
                new Column('id',  Type::getType('integer'), array()),
                new Column('name',  Type::getType('string'), array()),
            ),
        );
        $table->addColumn('obj', 'map',
            array('platformOptions'=>$mapOpts));

        // OBJECT schema definition via columnDefinition
        $table->addColumn('obj2', 'map',
            array('columnDefinition'=>'OBJECT (STRICT) AS ( id INTEGER, name STRING )'));

        // ARRAY schema definition via platform options
        $arrOpts = array(
            'type' => Type::FLOAT,
        );
        $table->addColumn('arr_float', 'array',
            array('platformOptions'=>$arrOpts));

        // ARRAY schema definition via columnDefinition
        $table->addColumn('arr_str', 'array',
            array('columnDefinition'=>'ARRAY (STRING)'));
        $table->addColumn('arr_obj', 'array',
            array('columnDefinition'=>'ARRAY (OBJECT (IGNORED) AS ( id INTEGER, name STRING ))'));

        return $table;
    }

    public function testListTableColumns()
    {
        $table = $this->createListTableColumns();

        $this->_sm->dropAndCreateTable($table);

        $columns = $this->_sm->listTableColumns('list_table_columns');

        $this->assertArrayHasKey('text', $columns);
        $this->assertEquals('text', strtolower($columns['text']->getname()));
        $this->assertInstanceOf('Doctrine\DBAL\Types\StringType', $columns['text']->gettype());

        $this->assertEquals('ts', strtolower($columns['ts']->getname()));
        $this->assertInstanceOf('Crate\DBAL\Types\TimestampType', $columns['ts']->gettype());

        $this->assertEquals('num_float_double', strtolower($columns['num_float_double']->getname()));
        $this->assertInstanceOf('Doctrine\DBAL\Types\FloatType', $columns['num_float_double']->gettype());

        $this->assertArrayHasKey('num_short', $columns);
        $this->assertEquals('num_short', strtolower($columns['num_short']->getname()));
        $this->assertInstanceOf('Doctrine\DBAL\Types\SmallIntType', $columns['num_short']->gettype());

        $this->assertArrayHasKey('num_int', $columns);
        $this->assertEquals('num_int', strtolower($columns['num_int']->getname()));
        $this->assertInstanceOf('Doctrine\DBAL\Types\IntegerType', $columns['num_int']->gettype());

        $this->assertArrayHasKey('num_long', $columns);
        $this->assertEquals('num_long', strtolower($columns['num_long']->getname()));
        $this->assertInstanceOf('Doctrine\DBAL\Types\BigIntType', $columns['num_long']->gettype());

        $this->assertEquals('obj', strtolower($columns['obj']->getname()));
        $this->assertInstanceOf('Crate\DBAL\Types\MapType', $columns['obj']->gettype());

        $this->assertEquals("obj['id']", strtolower($columns["obj['id']"]->getname()));
        $this->assertInstanceOf('Doctrine\DBAL\Types\IntegerType', $columns["obj['id']"]->gettype());

        $this->assertEquals("obj['name']", strtolower($columns["obj['name']"]->getname()));
        $this->assertInstanceOf('Doctrine\DBAL\Types\StringType', $columns["obj['name']"]->gettype());

        $this->assertEquals('obj2', strtolower($columns['obj2']->getname()));
        $this->assertInstanceOf('Crate\DBAL\Types\MapType', $columns['obj2']->gettype());

        $this->assertEquals("obj2['id']", strtolower($columns["obj2['id']"]->getname()));
        $this->assertInstanceOf('Doctrine\DBAL\Types\IntegerType', $columns["obj2['id']"]->gettype());

        $this->assertEquals("obj2['name']", strtolower($columns["obj2['name']"]->getname()));
        $this->assertInstanceOf('Doctrine\DBAL\Types\StringType', $columns["obj2['name']"]->gettype());

        $this->assertEquals('arr_float', strtolower($columns['arr_float']->getname()));
        $this->assertInstanceOf('Crate\DBAL\Types\ArrayType', $columns['arr_float']->gettype());

        $this->assertEquals('arr_str', strtolower($columns['arr_str']->getname()));
        $this->assertInstanceOf('Crate\DBAL\Types\ArrayType', $columns['arr_str']->gettype());

        $this->assertEquals('arr_obj', strtolower($columns['arr_obj']->getname()));
        $this->assertInstanceOf('Crate\DBAL\Types\ArrayType', $columns['arr_obj']->gettype());
    }


    public function testCreateSchema()
    {
        $this->createTestTable('test_table');

        $schema = $this->_sm->createSchema();
        $this->assertTrue($schema->hasTable('test_table'));
    }

    /**
     * @param string $name
     * @param array $data
     */
    protected function createTestTable($name = 'test_table', $data = array())
    {
        $options = array();
        if (isset($data['options'])) {
            $options = $data['options'];
        }

        $table = $this->getTestTable($name, $options);
        $this->_sm->dropAndCreateTable($table);
    }

    protected function getTestTable($name, $options=array())
    {
        $table = new Table($name, array(), array(), array(), false, $options);
        $table->setSchemaConfig($this->_sm->createSchemaConfig());
        $table->addColumn('id', 'integer', array('notnull' => true));
        $table->setPrimaryKey(array('id'));
        $table->addColumn('test', 'string', array('length' => 255));
        $table->addColumn('foreign_key_test', 'integer');
        return $table;
    }

    protected function getTestCompositeTable($name)
    {
        $table = new Table($name, array(), array(), array(), false, array());
        $table->setSchemaConfig($this->_sm->createSchemaConfig());
        $table->addColumn('id', 'integer', array('notnull' => true));
        $table->addColumn('other_id', 'integer', array('notnull' => true));
        $table->setPrimaryKey(array('id', 'other_id'));
        $table->addColumn('test', 'string', array('length' => 255));
        $table->addColumn('test_other', 'string', array('length' => 255));
        return $table;
    }

    protected function assertHasTable($tables, $tableName)
    {
        $foundTable = false;
        foreach ($tables AS $table) {
            $this->assertInstanceOf('Doctrine\DBAL\Schema\Table', $table, 'No Table instance was found in tables array.');
            if (strtolower($table->getName()) == 'list_tables_test_new_name') {
                $foundTable = true;
            }
        }
        $this->assertTrue($foundTable, "Could not find new table");
    }

}
