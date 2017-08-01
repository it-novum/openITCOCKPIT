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
use Crate\PDO\PDO;

class ConnectionTestCase extends DBALFunctionalTestCase
{
    public function setUp()
    {
        $this->resetSharedConn();
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->resetSharedConn();
    }

    public function testBasicAuthConnection()
    {
        $auth = ['crate', 'secret'];
        $params = array(
            'driverClass' => 'Crate\DBAL\Driver\PDOCrate\Driver',
            'host' => 'localhost',
            'port' => 4200,
            'user' => $auth[0],
            'password' => $auth[1],
        );
        $conn = \Doctrine\DBAL\DriverManager::getConnection($params);
        $this->assertEquals($auth[0], $conn->getUsername());
        $this->assertEquals($auth[1], $conn->getPassword());
        $auth_attr = $conn->getWrappedConnection()->getAttribute(PDO::ATTR_HTTP_BASIC_AUTH);
        $this->assertEquals($auth_attr, $auth);
    }

    public function testGetConnection()
    {
      $this->assertInstanceOf('Doctrine\DBAL\Connection', $this->_conn);
      $this->assertInstanceOf('Crate\DBAL\Driver\PDOCrate\PDOConnection', $this->_conn->getWrappedConnection());
    }

    public function testGetDriver()
    {
        $this->assertInstanceOf('Crate\DBAL\Driver\PDOCrate\Driver', $this->_conn->getDriver());
    }

    public function testStatement()
    {
        $sql = 'SELECT * FROM sys.cluster';
        $stmt = $this->_conn->prepare($sql);
        $this->assertInstanceOf('Doctrine\DBAL\Statement', $stmt);
        $this->assertInstanceOf('Crate\PDO\PDOStatement', $stmt->getWrappedStatement());

    }

    public function testConnect()
    {
        $this->assertTrue($this->_conn->connect());

        $stmt = $this->_conn->query('select * from sys.cluster');
        $this->assertEquals(1, $stmt->rowCount());

        $row = $stmt->fetch();
        $this->assertEquals('crate', $row['name']);
    }

}

