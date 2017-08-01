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

namespace Crate\Test\DBAL\Functional;

use Crate\DBAL\Types\MapType;
use Crate\Test\DBAL\DBALFunctionalTestCase;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\Type;
use PDO;

class WriteTest extends DBALFunctionalTestCase
{
    static private $generated = false;

    public function setUp()
    {
        parent::setUp();

        if (self::$generated === false) {
            self::$generated = true;
            /* @var $sm \Doctrine\DBAL\Schema\AbstractSchemaManager */
            $table = new \Doctrine\DBAL\Schema\Table("write_table");
            $table->addColumn('test_int', 'integer');
            $table->addColumn('test_string', 'string');
            $table->addColumn('test_float', 'float');
            $table->addColumn('test_array', 'array', array('columnDefinition'=>'ARRAY(STRING)'));

            $platformOptions = array(
                'type'   => MapType::STRICT,
                'fields' => array(
                    new Column('id',    Type::getType('integer'), array()),
                    new Column('name',  Type::getType('string'), array()),
                    new Column('value', Type::getType('float'), array()),
                ),
            );
            $table->addColumn('test_obj', MapType::NAME, array('platformOptions'=>$platformOptions));

            $sm = $this->_conn->getSchemaManager();
            $sm->createTable($table);
        }
    }

    public function tearDown()
    {
        if (self::$generated === true) {
            $this->execute('drop table write_table');
            self::$generated = false;
        }
    }


    /**
     * @group DBAL-80
     */
    public function testExecuteUpdateFirstTypeIsNull()
    {
        $sql = "INSERT INTO write_table (test_string, test_int) VALUES (?, ?)";
        $this->_conn->executeUpdate($sql, array("text", 1111), array(null, PDO::PARAM_INT));
        $this->refresh('write_table');

        $sql = "SELECT test_obj, test_string, test_int FROM write_table WHERE test_string = ? AND test_int = ?";
        $this->assertEquals($this->_conn->fetchColumn($sql, array("text", 1111)), null);
        $this->assertEquals($this->_conn->fetchColumn($sql, array("text", 1111), 1), "text");
        $this->assertEquals($this->_conn->fetchColumn($sql, array("text", 1111), 2), 1111);
    }

    public function testExecuteUpdate()
    {
        $sql = "INSERT INTO write_table (test_int) VALUES ( " . $this->_conn->quote(1, PDO::PARAM_INT) . ")";
        $affected = $this->_conn->executeUpdate($sql);

        $this->assertEquals(1, $affected, "executeUpdate() should return the number of affected rows!");
    }

    public function testExecuteUpdateWithTypes()
    {
        $sql = "INSERT INTO write_table (test_int, test_string) VALUES (?, ?)";
        $affected = $this->_conn->executeUpdate($sql, array(1, 'foo'), array(\PDO::PARAM_INT, \PDO::PARAM_STR));

        $this->assertEquals(1, $affected, "executeUpdate() should return the number of affected rows!");
    }

    public function testPrepareRowCountReturnsAffectedRows()
    {
        $sql = "INSERT INTO write_table (test_int, test_string) VALUES (?, ?)";
        $stmt = $this->_conn->prepare($sql);

        $stmt->bindValue(1, 1);
        $stmt->bindValue(2, "foo");
        $stmt->execute();

        $this->assertEquals(1, $stmt->rowCount());
    }

    public function testPrepareWithPdoTypes()
    {
        $sql = "INSERT INTO write_table (test_int, test_string) VALUES (?, ?)";
        $stmt = $this->_conn->prepare($sql);

        $stmt->bindValue(1, 1, \PDO::PARAM_INT);
        $stmt->bindValue(2, "foo", \PDO::PARAM_STR);
        $stmt->execute();

        $this->assertEquals(1, $stmt->rowCount());
    }

    public function testPrepareWithDbalTypes()
    {
        $sql = "INSERT INTO write_table (test_int, test_string, test_float, test_obj) VALUES (?, ?, ?, ?)";
        $stmt = $this->_conn->prepare($sql);

        $stmt->bindValue(1, 1, Type::getType('integer'));
        $stmt->bindValue(2, "foo", Type::getType('string'));
        $stmt->bindValue(3, 3.141592, Type::getType('float'));
        $stmt->bindValue(4, array('id'=>1, 'name'=>'christian', 'value'=>1.234), Type::getType('map'));
        $stmt->execute();

        $this->assertEquals(1, $stmt->rowCount());
    }

    public function testPrepareWithDbalTypeNames()
    {
        $sql = "INSERT INTO write_table (test_int, test_string, test_float, test_map, test_bool) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->_conn->prepare($sql);

        $stmt->bindValue(1, 1, 'integer');
        $stmt->bindValue(2, "foo", 'string');
        $stmt->bindValue(3, 3.141592, 'float');
        $stmt->bindValue(4, array('id'=>1, 'name'=>'christian', 'value'=>1.234), 'map');
        $stmt->bindValue(5, true, 'boolean');
        $stmt->execute();

        $this->assertEquals(1, $stmt->rowCount());
    }

    public function insertRows()
    {
        $this->assertEquals(1, $this->_conn->insert('write_table', array(
            'test_int' => 1,
            'test_string' => 'foo',
            'test_float' => 1.234,
            'test_array' => array('foo','bar'),
            'test_obj' => array('id'=>1, 'name'=>'foo', 'value'=>1.234),
        ), array('integer','string','float','array','map')));
        $this->assertEquals(1, $this->_conn->insert('write_table', array(
            'test_int' => 2,
            'test_string' => 'bar',
            'test_float' => 2.345,
            'test_array' => array('bar','foo'),
            'test_obj' => array('id'=>2, 'name'=>'bar', 'value'=>2.345),
        ), array('integer','string','float','array','map')));

        $this->refresh('write_table');
    }

    public function testInsert()
    {
        $this->insertRows();
    }

    public function testDelete()
    {
        $this->insertRows();

        $this->assertEquals(1, $this->_conn->delete('write_table', array('test_int' => 2)));
        $this->refresh('write_table');
        $this->assertEquals(1, count($this->_conn->fetchAll('SELECT * FROM write_table')));

        $this->assertEquals(1, $this->_conn->delete('write_table', array('test_int' => 1)));
        $this->refresh('write_table');
        $this->assertEquals(0, count($this->_conn->fetchAll('SELECT * FROM write_table')));
    }

    public function testUpdate()
    {
        $this->insertRows();

        $this->assertEquals(1, $this->_conn->update('write_table', array('test_string' => 'bar'), array('test_string' => 'foo')));
        $this->refresh('write_table');
        $this->assertEquals(2, $this->_conn->update('write_table', array('test_string' => 'baz'), array('test_string' => 'bar')));
        $this->refresh('write_table');
        $this->assertEquals(0, $this->_conn->update('write_table', array('test_string' => 'baz'), array('test_string' => 'bar')));
    }

}