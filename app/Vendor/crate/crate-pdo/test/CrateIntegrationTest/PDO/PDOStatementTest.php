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

/**
 * Class PDOStatementTest
 *
 * @coversNothing
 *
 * @group integration
 */
class PDOStatementTest extends AbstractIntegrationTest
{
    public function testFetchColumn()
    {
        $this->insertRows(5);

        $statement = $this->pdo->prepare('SELECT id FROM test_table');

        $result = [];

        while ($columnValue = $statement->fetchColumn()) {
            $result[] = $columnValue;
        }

        $this->assertEquals([1, 2, 3, 4, 5], $result);
    }

    public function testFetchBound()
    {
        $expected = [
            ['id' => 1, 'name' => 'first'],
            ['id' => 2, 'name' => 'second'],
            ['id' => 3, 'name' => 'third'],
        ];

        foreach ($expected as $row) {
            $this->insertRow($row['id'], $row['name']);
        }

        $id    = null;
        $name  = null;
        $index = 0;

        $statement = $this->pdo->prepare('SELECT id, name FROM test_table');
        $statement->bindColumn('id', $id);
        $statement->bindColumn('name', $name);

        while ($row = $statement->fetch(PDO::FETCH_BOUND)) {

            $this->assertEquals($expected[$index]['id'], $id);
            $this->assertEquals($expected[$index]['name'], $name);

            $index++;
        }

        $this->assertEquals(3, $index);
    }

    public function testFetchAllWithNumStyle()
    {
        $expected = [
            [1, 'first'],
            [2, 'second'],
            [3, 'third'],
        ];

        foreach ($expected as $row) {
            $this->insertRow($row[0], $row[1]);
        }

        $statement = $this->pdo->prepare('SELECT id, name FROM test_table');
        $statement->execute();

        $this->assertEquals($expected, $statement->fetchAll(PDO::FETCH_NUM));
    }

    public function testFetchAllWithAssocStyle()
    {
        $expected = [
            ['id' => 1, 'name' => 'first'],
            ['id' => 2, 'name' => 'second'],
            ['id' => 3, 'name' => 'third'],
        ];

        foreach ($expected as $row) {
            $this->insertRow($row['id'], $row['name']);
        }

        $statement = $this->pdo->prepare('SELECT id, name FROM test_table');
        $statement->execute();

        $this->assertEquals($expected, $statement->fetchAll(PDO::FETCH_ASSOC));
    }

    public function testFetchSameColumnTwiceWithAssocStyle()
    {
        $this->insertRows(3);
        $expected = [
            ['id' => 1, 'id' => 1],
            ['id' => 2, 'id' => 2],
            ['id' => 3, 'id' => 3],
        ];

        $statement = $this->pdo->prepare('SELECT id, id FROM test_table');
        $statement->execute();

        $this->assertEquals($expected, $statement->fetchAll(PDO::FETCH_ASSOC));
    }

    public function testFetchAllWithBothStyle()
    {
        $expected = [
            [0 => 1, 'id' => 1, 1 => 'first', 'name' => 'first'],
            [0 => 2, 'id' => 2, 1 => 'second', 'name' => 'second'],
            [0 => 3, 'id' => 3, 1 => 'third', 'name' => 'third'],
        ];

        foreach ($expected as $row) {
            $this->insertRow($row['id'], $row['name']);
        }

        $statement = $this->pdo->prepare('SELECT id, name FROM test_table');
        $statement->execute();

        // In theory this should be assertSame, but implementing that would be incredibly slow
        $this->assertEquals($expected, $statement->fetchAll(PDO::FETCH_BOTH));
    }

    public function testFetchAllWithFuncStyle()
    {
        $expected = [
            ['id' => 1, 'name' => 'first'],
            ['id' => 2, 'name' => 'second'],
            ['id' => 3, 'name' => 'third'],
        ];

        foreach ($expected as $row) {
            $this->insertRow($row['id'], $row['name']);
        }

        $statement = $this->pdo->prepare('SELECT id, name FROM test_table');
        $statement->execute();

        $index    = 0;
        $callback = function ($id, $name) {
            return sprintf('%d:%s', $id, $name);
        };

        $resultSet = $statement->fetchAll(PDO::FETCH_FUNC, $callback);

        foreach ($resultSet as $result) {
            $this->assertEquals(sprintf('%d:%s', $expected[$index]['id'], $expected[$index]['name']), $result);
            $index++;
        }

        $this->assertEquals(count($expected), $index);
    }

    public function testBindParam()
    {
        $expected = [
            ['id' => 1, 'name' => 'first'],
            ['id' => 2, 'name' => 'second'],
            ['id' => 3, 'name' => 'third'],
        ];

        foreach ($expected as $row) {
            $this->insertRow($row['id'], $row['name']);
        }

        $name = 'second';
        $statement = $this->pdo->prepare('SELECT * FROM test_table where name = ?');
        $statement->bindParam(1, $name);
        $statement->execute();
        $this->assertEquals(1, $statement->rowCount());

        $resultSet = $statement->fetchAll(PDO::FETCH_NAMED);
        $this->assertEquals(2, $resultSet[0]['id']);
        $this->assertEquals($name, $resultSet[0]['name']);
    }

    public function testBindNamedParam()
    {
        $expected = [
            ['id' => 1, 'name' => 'first'],
            ['id' => 2, 'name' => 'second'],
            ['id' => 3, 'name' => 'third'],
        ];

        foreach ($expected as $row) {
            $this->insertRow($row['id'], $row['name']);
        }

        $name = 'second';
        $id = 2;
        $sql = 'SELECT * FROM test_table where name = :name and id = :id';

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam('name', $name);
        $statement->bindParam('id', $id);
        $statement->execute();
        $this->assertEquals(1, $statement->rowCount());

        $resultSet = $statement->fetchAll(PDO::FETCH_NAMED);
        $this->assertEquals(2, $resultSet[0]['id']);
        $this->assertEquals($name, $resultSet[0]['name']);

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':id', $id);
        $statement->execute();
        $this->assertEquals(1, $statement->rowCount());

        $resultSet = $statement->fetchAll(PDO::FETCH_NAMED);
        $this->assertEquals(2, $resultSet[0]['id']);
        $this->assertEquals($name, $resultSet[0]['name']);
    }

    public function testBindNamedParamUnordered()
    {
        $this->insertRows(2);

        $statement = $this->pdo->prepare('UPDATE test_table SET name = concat(name, :name) where id = :id');
        $statement->bindValue(':id', 1);
        $statement->bindValue(':name', '_abc');
        $statement->execute();

        $this->pdo->exec('REFRESH TABLE test_table');

        $statement = $this->pdo->prepare('SELECT name FROM test_table WHERE ID=1');
        $resultSet = $statement->fetch();
        $this->assertEquals('hello world_abc', $resultSet[0]);
    }

    public function testBindNamedParamMultiple()
    {
        $this->pdo->exec("INSERT INTO test_table (id, name, int_type) VALUES (1, 'hello', 1), (2, 'world', 1), (3, 'hello', 2), (4, 'world', 3)");
        $this->pdo->exec("REFRESH TABLE test_table");

        $statement = $this->pdo->prepare('update test_table set name = concat(name, :name) where int_type = :int_type and name != :name');
        $statement->bindValue(':int_type', 1, PDO::PARAM_INT);
        $statement->bindValue(':name', 'world', PDO::PARAM_STR);
        $statement->execute();

        $this->pdo->exec("REFRESH TABLE test_table");

        $statement = $this->pdo->prepare("SELECT id, name, int_type FROM test_table WHERE id=1");
        $resultSet = $statement->fetch();
        $this->assertEquals(1, $resultSet[0]);
        $this->assertEquals('helloworld', $resultSet[1]);
        $this->assertEquals(1, $resultSet[2]);
    }
    
    public function testBindValue()
    {
        $expected = [
            ['id' => 1, 'name' => 'first'],
            ['id' => 2, 'name' => 'second'],
            ['id' => 3, 'name' => 'third'],
        ];

        foreach ($expected as $row) {
            $this->insertRow($row['id'], $row['name']);
        }

        $statement = $this->pdo->prepare('SELECT * FROM test_table where name = ?');
        $statement->bindValue(1, 'second');
        $statement->execute();
        $this->assertEquals(1, $statement->rowCount());

        $resultSet = $statement->fetchAll(PDO::FETCH_NAMED);
        $this->assertEquals(2, $resultSet[0]['id']);
        $this->assertEquals('second', $resultSet[0]['name']);
    }

    public function testArrayValue()
    {
        $statement = $this->pdo->prepare('INSERT INTO test_table (id, array_type, object_type) VALUES(?, ?, ?)');
        $statement->bindValue(1, 1, PDO::PARAM_INT);
        $statement->bindValue(2, [1, 2], PDO::PARAM_ARRAY);
        $statement->bindValue(3, ["foo" => "bar"], PDO::PARAM_OBJECT);
        $statement->execute();
        $this->assertEquals(1, $statement->rowCount());

        $this->pdo->exec('REFRESH TABLE test_table');

        $statement = $this->pdo->prepare('SELECT id, array_type, object_type FROM test_table');
        $resultSet = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->assertEquals(1, $resultSet[0]['id']);
        $this->assertEquals([1, 2], $resultSet[0]['array_type']);
        $this->assertEquals(["foo" => "bar"], $resultSet[0]['object_type']);
    }

}
