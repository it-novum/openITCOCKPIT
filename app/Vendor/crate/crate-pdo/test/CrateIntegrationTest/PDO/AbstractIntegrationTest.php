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

namespace CrateIntegrationTest\PDO;

use Crate\PDO\PDO;
use PHPUnit_Framework_TestCase;

abstract class AbstractIntegrationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PDO
     */
    protected $pdo;

    protected function setUp()
    {
        $this->pdo = new PDO('crate:localhost:4200', null, null, []);
        $query = 'CREATE TABLE test_table (id INTEGER PRIMARY KEY, name STRING,';
        $query .= 'int_type INTEGER, long_type LONG, boolean_type BOOLEAN,';
        $query .= 'double_type DOUBLE, float_type FLOAT, array_type ARRAY(INTEGER),';
        $query .= 'object_type OBJECT) CLUSTERED INTO 1 SHARDS WITH (number_of_replicas = 0)';
        $this->pdo->query($query);
    }

    protected function tearDown()
    {
        $this->pdo->query('DROP TABLE test_table');
    }

    protected function insertRows($count = 1)
    {
        for ($i = 1; $i <= $count; $i++) {
            $this->pdo->exec(sprintf("INSERT INTO test_table (id, name) VALUES (%d, 'hello world')", $i));
        }

        $this->pdo->query('refresh table test_table');
    }

    protected function insertRow($id, $name)
    {
        $this->pdo->exec(sprintf("INSERT INTO test_table (id, name) VALUES (%d, '%s')", $id, $name));
        $this->pdo->query('refresh table test_table');
    }
}
