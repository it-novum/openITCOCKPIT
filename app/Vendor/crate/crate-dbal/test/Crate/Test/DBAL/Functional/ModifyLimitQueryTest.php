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

use Crate\Test\DBAL\DBALFunctionalTestCase;
use Doctrine\DBAL\DBALException;


class ModifyLimitQueryTest extends DBALFunctionalTestCase
{
    private static $tableCreated = false;

    public function setUp()
    {
        parent::setUp();

        if (!self::$tableCreated) {
            /* @var $sm \Doctrine\DBAL\Schema\AbstractSchemaManager */
            $table = new \Doctrine\DBAL\Schema\Table("modify_limit_table");
            $table->addColumn('test_int', 'integer');
            $table->setPrimaryKey(array('test_int'));

            $table2 = new \Doctrine\DBAL\Schema\Table("modify_limit_table2");
            $table2->addColumn('id', 'integer', array('autoincrement' => true));
            $table2->addColumn('test_int', 'integer');
            $table2->setPrimaryKey(array('id'));

            $sm = $this->_conn->getSchemaManager();
            $sm->createTable($table);
            $sm->createTable($table2);
            self::$tableCreated = true;
        }
    }

    public function tearDown()
    {
        parent::tearDown();
        if (self::$tableCreated) {
            $sm = $this->_conn->getSchemaManager();
            try {
                $sm->dropTable('modify_limit_table');
                $sm->dropTable('modify_limit_table2');
                self::$tableCreated = false;
            } catch (DBALException $e) {}
        }
    }

    public function testModifyLimitQuerySimpleQuery()
    {
        $this->_conn->insert('modify_limit_table', array('test_int' => 1));
        $this->_conn->insert('modify_limit_table', array('test_int' => 2));
        $this->_conn->insert('modify_limit_table', array('test_int' => 3));
        $this->_conn->insert('modify_limit_table', array('test_int' => 4));

        $this->refresh('modify_limit_table');

        $sql = "SELECT * FROM modify_limit_table ORDER BY test_int ASC";

        $this->assertLimitResult(array(1, 2, 3, 4), $sql, 10, 0);
        $this->assertLimitResult(array(1, 2), $sql, 2, 0);
        $this->assertLimitResult(array(3, 4), $sql, 2, 2);
    }

    public function testModifyLimitQueryOrderBy()
    {
        $this->_conn->insert('modify_limit_table', array('test_int' => 1));
        $this->_conn->insert('modify_limit_table', array('test_int' => 2));
        $this->_conn->insert('modify_limit_table', array('test_int' => 3));
        $this->_conn->insert('modify_limit_table', array('test_int' => 4));

        $this->refresh('modify_limit_table');

        $sql = "SELECT * FROM modify_limit_table ORDER BY test_int DESC";

        $this->assertLimitResult(array(4, 3, 2, 1), $sql, 10, 0);
        $this->assertLimitResult(array(4, 3), $sql, 2, 0);
        $this->assertLimitResult(array(2, 1), $sql, 2, 2);
    }

    public function testModifyLimitQueryGroupBy()
    {
        $this->_conn->insert('modify_limit_table2', array('test_int' => 1, 'id' => 1));
        $this->_conn->insert('modify_limit_table2', array('test_int' => 1, 'id' => 2));
        $this->_conn->insert('modify_limit_table2', array('test_int' => 1, 'id' => 3));
        $this->_conn->insert('modify_limit_table2', array('test_int' => 2, 'id' => 4));
        $this->_conn->insert('modify_limit_table2', array('test_int' => 2, 'id' => 5));

        $this->refresh('modify_limit_table2');

        $sql = "SELECT test_int FROM modify_limit_table2 GROUP BY test_int";
        $this->assertLimitResult(array(1, 2), $sql, 10, 0);
        $this->assertLimitResult(array(1), $sql, 1, 0);
        $this->assertLimitResult(array(2), $sql, 1, 1);
    }

    public function assertLimitResult($expectedResults, $sql, $limit, $offset)
    {
        $p = $this->_conn->getDatabasePlatform();
        $data = array();
        foreach ($this->_conn->fetchAll($p->modifyLimitQuery($sql, $limit, $offset)) AS $row) {
            $row = array_change_key_case($row, CASE_LOWER);
            $data[] = $row['test_int'];
        }
        $this->assertEquals($expectedResults, $data);
    }
}