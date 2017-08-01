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

use Crate\Stdlib\CrateConst;

/**
 * Integration tests for {@see \Crate\PDO\PDO}
 *
 * @coversNothing
 *
 * @group integration
 */
class PDOTest extends AbstractIntegrationTest
{
    public function testWithInvalidSQL()
    {
        $statement = $this->pdo->prepare('bogus sql');
        $statement->execute();

        $this->assertEquals(4000, $statement->errorCode());

        list ($ansiSQLError, $driverError, $driverMessage) = $statement->errorInfo();

        $this->assertEquals(42000, $ansiSQLError);
        $this->assertEquals(CrateConst::ERR_INVALID_SQL, $driverError);
        $this->assertContains('no viable alternative at input \'bogus\']', $driverMessage);
    }

    public function testDelete()
    {
        $this->insertRows(1);

        $statement = $this->pdo->prepare('DELETE FROM test_table WHERE id = 1');

        $this->assertTrue($statement->execute());
        $this->assertEquals(1, $statement->rowCount());
    }

    public function testDeleteWithMultipleAffectedRows()
    {
        $this->insertRows(5);

        $statement = $this->pdo->prepare('DELETE FROM test_table WHERE id > 1');

        $this->assertTrue($statement->execute());
        $this->assertEquals(4, $statement->rowCount());
    }

    public function testGetServerVersion()
    {
        $result = $this->pdo->getServerVersion();
        $this->assertRegExp("/[0-9]+\.[0-9]+\.[0-9]+/", $result);
    }
}
