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

namespace CrateTest\PDO;

use Crate\PDO\Exception\PDOException;
use Crate\PDO\PDO;
use PHPUnit_Framework_TestCase;
use ReflectionMethod;

class PDOParseDSNTest extends PHPUnit_Framework_TestCase
{
    private function parseDSN($dsn)
    {
        $method = new ReflectionMethod('Crate\PDO\PDO', 'parseDSN');
        $method->setAccessible(true);
        return $method->invoke(null, $dsn);
    }

    private function serversFromDsnParts(array $dsnParts)
    {
        $method = new ReflectionMethod('Crate\PDO\PDO', 'serversFromDsnParts');
        $method->setAccessible(true);
        return $method->invoke(null, $dsnParts);
    }

    public function testParseDSNSingleHost()
    {
        $parts = $this->parseDSN('crate:localhost:4200');
        $servers = $this->serversFromDsnParts($parts);

        $this->assertEquals(1, count($servers));
        $this->assertEquals('localhost:4200', $servers[0]);
    }

    public function testParseDSNMultipleHosts()
    {
        $parts = $this->parseDSN('crate:crate1.example.com:4200,crate2.example.com:4200');
        $servers = $this->serversFromDsnParts($parts);

        $this->assertEquals(2, count($servers));
        $this->assertEquals('crate1.example.com:4200', $servers[0]);
        $this->assertEquals('crate2.example.com:4200', $servers[1]);
    }

    public function testParseDSNMissingName()
    {
        $dsn = 'localhost:4200';

        $this->setExpectedException('Crate\PDO\Exception\PDOException', sprintf('Invalid DSN %s', $dsn));
        $this->parseDSN($dsn);
    }

    public function testParseDSNEmpty()
    {
        $this->setExpectedException('Crate\PDO\Exception\PDOException', sprintf('Invalid DSN %s', ''));
        $this->parseDSN('');
    }

    public function testParseDSNInvalid()
    {
        $dsn = 'crate:localhost,demo.crate.io';

        $this->setExpectedException('Crate\PDO\Exception\PDOException', sprintf('Invalid DSN %s', $dsn));
        $this->parseDSN($dsn);
    }

    public function testParseDSNSingleHostWithSchema()
    {
        $dsn = 'crate:localhost:4200/my_schema';
        $servers = $this->parseDSN($dsn);

        $this->assertEquals(2, count($servers));
        $this->assertEquals('my_schema', $servers[1]);
    }

    public function testParseDSNInvalidSchema()
    {
        $dsn = array(
            'crate:localhost:4200/österreich',
            'crate:localhost:4200/mk++je',
            'crate:localhost:4200/copyright©'
        );

        foreach ($dsn as &$val) {
            try {
                $this->parseDSN($val);
            } catch (PDOException $e) {
                $this->assertEquals(substr( $e->getMessage(), 0, 11 ), 'Invalid DSN');
            }
        }
    }
}
