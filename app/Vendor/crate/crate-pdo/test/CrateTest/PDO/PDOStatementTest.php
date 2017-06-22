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

use Crate\PDO\PDO;
use Crate\PDO\PDOStatement;
use Crate\Stdlib\Collection;
use Crate\Stdlib\CollectionInterface;
use Crate\Stdlib\CrateConst;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Tests for {@see \Crate\PDO\PDOStatement}
 *
 * @coversDefaultClass \Crate\PDO\PDOStatement
 * @covers ::<!public>
 *
 * @group unit
 * @group statement
 */
class PDOStatementTest extends PHPUnit_Framework_TestCase
{
    const SQL = 'SELECT * FROM table_name';

    /**
     * @var PDO|PHPUnit_Framework_MockObject_MockObject
     */
    protected $pdo;

    /**
     * @var PDOStatement
     */
    protected $statement;

    /**
     * @var mixed
     */
    protected $callbackReturnValue;

    /**
     * @var array
     */
    protected $callbackCallParams;

    protected function setUp()
    {
        $this->pdo = $this->getMock('Crate\PDO\PDOInterface');


        $callback = function() {

            $args = func_get_args();

            if ($this->callbackCallParams !== null) {
                $this->assertEquals($args, $this->callbackCallParams);
            }

            return $this->callbackReturnValue;
        };

        $this->statement = new PDOStatement($this->pdo, $callback, static::SQL, []);
    }

    /**
     * @return CollectionInterface
     */
    private function getPopulatedCollection()
    {
        $data = [
            [1, 'foo', false],
            [2, 'bar', true],
        ];

        $columns = ['id', 'name', 'active'];

        return new Collection($data, $columns, 0, count($data));
    }

    /**
     * @covers ::__construct
     */
    public function testInstantiation()
    {
        $this->assertInstanceOf('Crate\PDO\PDOStatement', $this->statement);
        $this->assertInstanceOf('PDOStatement', $this->statement);
    }

    /**
     * @covers ::execute
     */
    public function testExecuteWithErrorResponse()
    {
        $this->callbackReturnValue = ['code' => 1337, 'message' => 'failed'];

        $this->assertFalse($this->statement->execute());

        list ($ansiErrorCode, $driverCode, $message) = $this->statement->errorInfo();

        $this->assertEquals(1337, $driverCode);
        $this->assertEquals('failed', $message);
    }

    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $parameters = ['bar'];

        $this->callbackCallParams  = [$this->statement, static::SQL, $parameters];
        $this->callbackReturnValue = $this->getPopulatedCollection();

        $this->assertTrue($this->statement->execute($parameters));
    }

    /**
     * @covers ::bindParam
     */
    public function testBindParam()
    {
        $initial  = 'foo';
        $expected = 'bar';

        $this->callbackCallParams  = [$this->statement, static::SQL, [0 => $expected]];
        $this->callbackReturnValue = $this->getPopulatedCollection();

        $this->statement->bindParam(1, $initial);

        // Update bar prior to calling execute
        $initial = $expected;

        $this->statement->execute();
    }

    /**
     * @covers ::bindParam
     */
    public function testBindParamInvalidPosition()
    {
        $initial  = 'foo';
        $expected = 'bar';

        $this->callbackCallParams  = [$this->statement, static::SQL, [0 => $expected]];
        $this->callbackReturnValue = $this->getPopulatedCollection();

        $this->setExpectedException('Crate\PDO\Exception\UnsupportedException');
        $this->statement->bindParam(0, $initial);
    }

    /**
     * @covers ::bindColumn
     */
    public function testBindColumn()
    {
        $column     = 'column';
        $value      = 'value1';
        $type       = PDO::PARAM_STR;
        $maxlen     = 1000;
        $driverData = null;

        $this->statement->bindColumn($column, $value, $type, $maxlen, $driverData);

        $reflection = new ReflectionClass('Crate\PDO\PDOStatement');

        $property = $reflection->getProperty('columnBinding');
        $property->setAccessible(true);

        $columnBinding = $property->getValue($this->statement);

        $this->assertArrayHasKey($column, $columnBinding);
        $this->assertEquals($value, $columnBinding[$column]['ref']);

        $value = 'value2';

        $this->assertEquals($value, $columnBinding[$column]['ref']);
    }

    public function bindValueParameterProvider()
    {
        return [
            [PDO::PARAM_INT, '1', 1],
            [PDO::PARAM_NULL, '1', null],
            [PDO::PARAM_BOOL, '1', true],
            [PDO::PARAM_STR, '1', '1']
        ];
    }

    /**
     * @dataProvider bindValueParameterProvider
     * @covers ::bindValue
     *
     * @param int    $type
     * @param string $value
     * @param mixed  $expectedValue
     */
    public function testBindValue($type, $value, $expectedValue)
    {
        $this->statement->bindValue(1, $value, $type);

        $reflection = new ReflectionClass('Crate\PDO\PDOStatement');

        $property = $reflection->getProperty('parameters');
        $property->setAccessible(true);

        $castedValue = $property->getValue($this->statement);

        $this->assertSame($expectedValue, $castedValue[0]);
    }

    /**
     * @covers ::fetch
     */
    public function testFetchWithUnsuccessfulExecution()
    {
        $this->callbackReturnValue = ['code' => 1337, 'message' => 'expected failure'];

        $this->assertFalse($this->statement->fetch());
    }

    /**
     * @covers ::fetch
     */
    public function testFetchWithEmptyResult()
    {
        $collection = $this->getMock('Crate\Stdlib\CollectionInterface');
        $collection
            ->expects($this->once())
            ->method('valid')
            ->will($this->returnValue(false));

        $this->callbackReturnValue = $collection;

        $this->assertFalse($this->statement->fetch(PDO::FETCH_NUM));
    }

    /**
     * @covers ::fetch
     */
    public function testFetchWithBoundStyle()
    {
        $id     = null;
        $name   = null;
        $active = null;

        $this->statement->bindColumn('id', $id, PDO::PARAM_INT);
        $this->statement->bindColumn('name', $name, PDO::PARAM_STR);
        $this->statement->bindColumn('active', $active, PDO::PARAM_BOOL);

        $this->assertNull($id);
        $this->assertNull($name);
        $this->assertNull($active);

        $this->callbackReturnValue = $this->getPopulatedCollection();

        $this->statement->fetch(PDO::FETCH_BOUND);

        $this->assertSame(1, $id);
        $this->assertSame('foo', $name);
        $this->assertFalse($active);

        $this->statement->fetch(PDO::FETCH_BOUND);

        $this->assertSame(2, $id);
        $this->assertSame('bar', $name);
        $this->assertTrue($active);
    }

    public function fetchStyleProvider()
    {
        return [
            [PDO::FETCH_NAMED, ['id' => 1, 'name' => 'foo', 'active' => false]],
            [PDO::FETCH_ASSOC, ['id' => 1, 'name' => 'foo', 'active' => false]],
            [PDO::FETCH_BOTH, [0 => 1, 1 => 'foo', 2 => false, 'id' => 1, 'name' => 'foo', 'active' => false]],
            [PDO::FETCH_NUM, [0 => 1, 1 => 'foo', 2 => false]]
        ];
    }

    /**
     * @dataProvider fetchStyleProvider
     * @covers ::fetch
     *
     * @param int   $fetchStyle
     * @param array $expected
     */
    public function testFetch($fetchStyle, array $expected)
    {
        $this->callbackReturnValue = $this->getPopulatedCollection();

        $result = $this->statement->fetch($fetchStyle);

        $this->assertEquals($expected, $result);
    }

    /**
     * @covers ::fetch
     */
    public function testFetchWithUnsupportedFetchStyle()
    {
        $this->setExpectedException('Crate\PDO\Exception\UnsupportedException');

        $this->callbackReturnValue = $this->getPopulatedCollection();

        $this->statement->fetch(PDO::FETCH_INTO);
    }

    /**
     * @covers ::rowCount
     */
    public function testRowCountWithFailedExecution()
    {
        $this->callbackReturnValue = ['code' => 1337, 'message' => 'expected failure'];

        $this->assertEquals(0, $this->statement->rowCount());
    }

    /**
     * @covers ::rowCount
     */
    public function testRowCount()
    {
        $this->callbackReturnValue = $this->getPopulatedCollection();
        $this->assertEquals(2, $this->statement->rowCount());
    }

    /**
     * @covers ::fetchColumn
     */
    public function testFetchColumnWithInvalidColumnNumberType()
    {
        $this->setExpectedException('Crate\PDO\Exception\InvalidArgumentException');
        $this->statement->fetchColumn('test');
    }

    /**
     * @covers ::fetchColumn
     */
    public function testFetchColumnWithFailedExecution()
    {
        $this->callbackReturnValue = ['code' => 1337, 'message' => 'expected failure'];
        $this->assertFalse($this->statement->fetchColumn());
    }

    /**
     * @covers ::fetchColumn
     */
    public function testFetchColumnWithWithEmptyCollection()
    {
        $collection = $this->getMock('Crate\Stdlib\CollectionInterface');
        $collection
            ->expects($this->once())
            ->method('valid')
            ->will($this->returnValue(false));

        $this->callbackReturnValue = $collection;
        $this->assertFalse($this->statement->fetchColumn());
    }

    /**
     * @covers ::fetchColumn
     */
    public function testFetchColumnWithInvalidColumnIndex()
    {
        $this->setExpectedException('Crate\PDO\Exception\OutOfBoundsException');

        $this->callbackReturnValue = $this->getPopulatedCollection();
        $this->statement->fetchColumn(10);
    }

    /**
     * @covers ::fetchColumn
     */
    public function testFetchColumn()
    {
        $this->callbackReturnValue = $this->getPopulatedCollection();

        $this->assertEquals(1, $this->statement->fetchColumn());
        $this->assertEquals(2, $this->statement->fetchColumn());
        $this->assertFalse($this->statement->fetchColumn());
    }

    /**
     * @covers ::fetchAll
     */
    public function testFetchAllWithFailedExecution()
    {
        $this->callbackReturnValue = ['code' => 1337, 'message' => 'expected failure'];

        $this->assertFalse($this->statement->fetchAll());
    }

    /**
     * @covers ::fetchAll
     */
    public function testFetchAllWithInvalidFetchStyle()
    {
        $this->setExpectedException('Crate\PDO\Exception\UnsupportedException');
        $this->callbackReturnValue = $this->getPopulatedCollection();

        $this->statement->fetchAll(PDO::FETCH_INTO);
    }

    /**
     * @return array
     */
    public function fetchAllStyleProvider()
    {
        return [
            [
                // Null mean it will use the default which is PDO::FETCH_BOTH
                null,
                [
                    [
                        0        => 1,
                        1        => 'foo',
                        2        => false,
                        'id'     => 1,
                        'name'   => 'foo',
                        'active' => false
                    ],
                    [
                        0        => 2,
                        1        => 'bar',
                        2        => true,
                        'id'     => 2,
                        'name'   => 'bar',
                        'active' => true
                    ]
                ]
            ],
            [
                PDO::FETCH_BOTH,
                [
                    [
                        0        => 1,
                        1        => 'foo',
                        2        => false,
                        'id'     => 1,
                        'name'   => 'foo',
                        'active' => false
                    ],
                    [
                        0        => 2,
                        1        => 'bar',
                        2        => true,
                        'id'     => 2,
                        'name'   => 'bar',
                        'active' => true
                    ]
                ]
            ],
            [
                PDO::FETCH_ASSOC,
                [
                    [
                        'id'     => 1,
                        'name'   => 'foo',
                        'active' => false
                    ],
                    [
                        'id'     => 2,
                        'name'   => 'bar',
                        'active' => true
                    ]
                ]
            ],
            [
                PDO::FETCH_NAMED,
                [
                    [
                        'id'     => 1,
                        'name'   => 'foo',
                        'active' => false
                    ],
                    [
                        'id'     => 2,
                        'name'   => 'bar',
                        'active' => true
                    ]
                ]
            ],
            [
                PDO::FETCH_NUM,
                [
                    [
                        0        => 1,
                        1        => 'foo',
                        2        => false,
                    ],
                    [
                        0        => 2,
                        1        => 'bar',
                        2        => true,
                    ]
                ]
            ],
            [
                PDO::FETCH_COLUMN,
                [
                    1,
                    2
                ]
            ],

        ];
    }

    /**
     * @dataProvider fetchAllStyleProvider
     * @covers ::fetchAll
     *
     * @param string $fetchStyle
     * @param array  $expected
     */
    public function testFetchAll($fetchStyle, array $expected)
    {
        $this->callbackReturnValue = $this->getPopulatedCollection();

        $this->pdo
            ->expects($this->any())
            ->method('getAttribute')
            ->with(PDO::ATTR_DEFAULT_FETCH_MODE)
            ->will($this->returnValue(PDO::FETCH_BOTH));


        $result = $this->statement->fetchAll($fetchStyle);

        $this->assertEquals($expected, $result);
    }

    /**
     * @covers ::fetchAll
     */
    public function testFetchAllWithFetchStyleFuncAndInvalidCallback()
    {
        $this->setExpectedException('Crate\PDO\Exception\InvalidArgumentException');
        $this->callbackReturnValue = $this->getPopulatedCollection();

        $this->statement->fetchAll(PDO::FETCH_FUNC, 'void');
    }

    /**
     * @covers ::fetchAll
     */
    public function testFetchAllBothSameColumnTwice() 
    {
        $columns = ['id', 'id', 'name'];
        $this->callbackReturnValue = new Collection([[1, 1, 'foo']], $columns, 0, 1);
        $result = $this->statement->fetchAll(PDO::FETCH_BOTH);

        $expected = [[0 => 1, 1 => 1, 2 => 'foo', 'id' => 1, 'id' => 1, 'name' => 'foo']];
        $this->assertEquals($expected, $result);
    }


    /**
     * @covers ::fetchAll
     */
    public function testFetchAllWithFetchStyleFunc()
    {
        $this->callbackReturnValue = $this->getPopulatedCollection();

        $result = $this->statement->fetchAll(PDO::FETCH_FUNC, function($id, $name, $active) {
            return $id;
        });

        $this->assertEquals([1, 2], $result);
    }

    /**
     * @covers ::fetchAll
     */
    public function testFetchAllWithFetchStyleColumnAndInvalidColumnIndexType()
    {
        $this->setExpectedException('Crate\PDO\Exception\InvalidArgumentException');
        $this->callbackReturnValue = $this->getPopulatedCollection();

        $this->statement->fetchAll(PDO::FETCH_COLUMN, 'test');
    }

    /**
     * @covers ::fetchAll
     */
    public function testFetchAllWithFetchStyleColumnAndInvalidColumnIndex()
    {
        $this->setExpectedException('Crate\PDO\Exception\OutOfBoundsException');
        $this->callbackReturnValue = $this->getPopulatedCollection();

        $this->statement->fetchAll(PDO::FETCH_COLUMN, 100);
    }

    /**
     * @covers ::fetchObject
     */
    public function testFetchObject()
    {
        $this->setExpectedException('Crate\PDO\Exception\UnsupportedException');
        $this->statement->fetchObject();
    }

    /**
     * @covers ::errorCode
     */
    public function testErrorCode()
    {
        $this->assertNull($this->statement->errorCode());

        $reflection = new ReflectionClass('Crate\PDO\PDOStatement');

        $property = $reflection->getProperty('errorCode');
        $property->setAccessible(true);
        $property->setValue($this->statement, 1337);

        $this->assertEquals(1337, $this->statement->errorCode());
    }

    /**
     * @return array
     */
    public function errorInfoAnsiCodeProvider()
    {
        return [
            [42000, CrateConst::ERR_INVALID_SQL, 'le error message'],
            ['Not available', 1337, 'le error message']
        ];
    }

    /**
     * @covers ::errorInfo
     * @dataProvider errorInfoAnsiCodeProvider
     *
     * @param mixed  $ansiCode
     * @param int    $errorCode
     * @param string $errorMessage
     */
    public function testErrorInfo($ansiCode, $errorCode, $errorMessage)
    {
        $this->assertNull($this->statement->errorInfo());

        $reflection = new ReflectionClass('Crate\PDO\PDOStatement');

        $errorCodeProp = $reflection->getProperty('errorCode');
        $errorCodeProp->setAccessible(true);
        $errorCodeProp->setValue($this->statement, $errorCode);

        $errorMessageProp = $reflection->getProperty('errorMessage');
        $errorMessageProp->setAccessible(true);
        $errorMessageProp->setValue($this->statement, $errorMessage);

        $this->assertEquals([$ansiCode, $errorCode, $errorMessage], $this->statement->errorInfo());
    }

    /**
     * @covers ::getAttribute
     */
    public function testGetAttribute()
    {
        $this->setExpectedException('Crate\PDO\Exception\UnsupportedException');
        $this->statement->getAttribute(null, null);
    }

    /**
     * @covers ::setAttribute
     */
    public function testSetAttribute()
    {
        $this->setExpectedException('Crate\PDO\Exception\UnsupportedException');
        $this->statement->setAttribute(null, null);
    }

    /**
     * @covers ::columnCount
     */
    public function testColumnCount()
    {
        $this->callbackReturnValue = $this->getPopulatedCollection();
        $this->assertEquals(3, $this->statement->columnCount());
    }

    /**
     * @covers ::columnCount
     */
    public function testColumnCountSameColumnTwice()
    {
        $data = [
            [1, 1],
            [2, 2],
        ];

        $this->callbackReturnValue = new Collection($data, ['id', 'id'], 0, 2);
        $this->assertEquals(2, $this->statement->columnCount());
    }

    /**
     * @covers ::getColumnMeta
     */
    public function testGetColumnMeta()
    {
        $this->setExpectedException('Crate\PDO\Exception\UnsupportedException');
        $this->statement->getColumnMeta(null);
    }

    /**
     * @covers ::setFetchMode
     */
    public function testSetFetchModeWithColumnAndMissingColNo()
    {
        $this->setExpectedException(
            'Crate\PDO\Exception\InvalidArgumentException',
            'fetch mode requires the colno argument'
        );

        $this->statement->setFetchMode(PDO::FETCH_COLUMN);
    }

    /**
     * @covers ::setFetchMode
     */
    public function testSetFetchModeWithColumnAndInvalidColNo()
    {
        $this->setExpectedException(
            'Crate\PDO\Exception\InvalidArgumentException',
            'colno must be an integer'
        );

        $this->statement->setFetchMode(PDO::FETCH_COLUMN, 'test');
    }

    /**
     * @covers ::setFetchMode
     */
    public function testSetFetchModeWithColumn()
    {
        $this->statement->setFetchMode(PDO::FETCH_COLUMN, 1);

        $reflection = new ReflectionClass('Crate\PDO\PDOStatement');

        $property = $reflection->getProperty('options');
        $property->setAccessible(true);;

        $options = $property->getValue($this->statement);

        $this->assertEquals(PDO::FETCH_COLUMN, $options['fetchMode']);
        $this->assertEquals(1, $options['fetchColumn']);
    }

    public function fetchModeStyleProvider()
    {
        return [
            [PDO::FETCH_ASSOC],
            [PDO::FETCH_NUM],
            [PDO::FETCH_BOTH],
            [PDO::FETCH_BOUND],
            [PDO::FETCH_NAMED]
        ];
    }

    /**
     * @covers ::setFetchMode
     * @dataProvider fetchModeStyleProvider
     *
     * @param int $fetchStyle
     */
    public function testSetFetchMode($fetchStyle)
    {
        $this->statement->setFetchMode($fetchStyle);

        $reflection = new ReflectionClass('Crate\PDO\PDOStatement');

        $property = $reflection->getProperty('options');
        $property->setAccessible(true);

        $options = $property->getValue($this->statement);

        $this->assertEquals($fetchStyle, $options['fetchMode']);
    }

    /**
     * @covers ::setFetchMode
     */
    public function testSetFetchModeWithInvalidFetchStyle()
    {
        $this->setExpectedException('Crate\PDO\Exception\UnsupportedException');
        $this->statement->setFetchMode(PDO::FETCH_INTO);
    }

    /**
     * @covers ::setFetchMode
     * @dataProvider fetchModeStyleProvider
     *
     * @param int $fetchStyle
     */
    public function testSetFetchModeWithInvalidExtraParam($fetchStyle)
    {
        $this->setExpectedException('Crate\PDO\Exception\InvalidArgumentException');
        $this->statement->setFetchMode($fetchStyle, 'fooBar');
    }

    /**
     * @covers ::nextRowset
     */
    public function testNextRowsetWithFailedExecution()
    {
        $this->callbackReturnValue = ['code' => 1337, 'message' => 'expected failure'];
        $this->assertFalse($this->statement->nextRowset());
    }

    /**
     * @covers ::nextRowset
     */
    public function testNextRowset()
    {
        $this->callbackReturnValue = $this->getPopulatedCollection();

        $this->assertTrue($this->statement->nextRowset());
        $this->assertFalse($this->statement->nextRowset());
    }

    /**
     * @covers ::debugDumpParams
     */
    public function testDumpDebugParams()
    {
        $this->setExpectedException('Crate\PDO\Exception\PDOException');
        $this->statement->debugDumpParams();
    }

    /**
     * @covers ::getIterator
     */
    public function testGetIterator()
    {
        $this->callbackReturnValue = $this->getPopulatedCollection();
        $this->statement->setFetchMode(PDO::FETCH_COLUMN, 0);

        $counter = 0;

        foreach ($this->statement->getIterator() as $id) {
            $this->assertEquals(++$counter, $id);
        }

        $this->assertEquals(2, $counter);
    }

    /**
     * @covers ::closeCursor
     */
    public function testCloseCursorReturnsTrue()
    {
        $this->assertTrue($this->statement->closeCursor());
    }

    /**
     * @covers ::typedValue
     */
    public function testTypedValue()
    {
        $method = new ReflectionMethod('Crate\PDO\PDOStatement', 'typedValue');
        $method->setAccessible(true);

        $this->assertEquals(1.23, $method->invoke($this->statement, 1.23, PDO::PARAM_FLOAT));
        $this->assertEquals(1.23, $method->invoke($this->statement, 1.23, PDO::PARAM_DOUBLE));

        $this->assertEquals(1, $method->invoke($this->statement, 1, PDO::PARAM_INT));
        $this->assertEquals(1, $method->invoke($this->statement, 1, PDO::PARAM_LONG));

        $this->assertEquals(null, $method->invoke($this->statement, 1, PDO::PARAM_NULL));

        $this->assertEquals("hello", $method->invoke($this->statement, "hello", PDO::PARAM_STR));
        $this->assertEquals("1234", $method->invoke($this->statement, 1234, PDO::PARAM_STR));
        $this->assertEquals("127.0.0.1", $method->invoke($this->statement, "127.0.0.1", PDO::PARAM_IP));

        $this->assertEquals([1, 2], $method->invoke($this->statement, [1, 2], PDO::PARAM_ARRAY));
        $this->assertEquals(["foo" =>  "bar"], $method->invoke($this->statement, ["foo" =>  "bar"], PDO::PARAM_ARRAY));

        $this->assertEquals(12345, $method->invoke($this->statement, 12345, PDO::PARAM_TIMESTAMP));
        $this->assertEquals(12345, $method->invoke($this->statement, "12345", PDO::PARAM_TIMESTAMP));
        $this->assertEquals("2014-03-04T18:45:20", $method->invoke($this->statement, "2014-03-04T18:45:20", PDO::PARAM_TIMESTAMP));

    }

    /**
     * @covers ::typedValue
     */
    public function testTypedValueInvalid()
    {
        $method = new ReflectionMethod('Crate\PDO\PDOStatement', 'typedValue');
        $method->setAccessible(true);

        $this->setExpectedException('Crate\PDO\Exception\PDOException');
        $method->invoke($this->statement, 1, PDO::PARAM_LOB);
    }

    /**
     * @covers ::replaceNamedParametersWithPositionals
     */
    public function testReplaceNamedParametersWithPositionals()
    {
        $method = new ReflectionMethod('Crate\PDO\PDOStatement', 'replaceNamedParametersWithPositionals');
        $method->setAccessible(true);
        $property = new ReflectionProperty('Crate\PDO\PDOStatement', 'namedToPositionalMap');
        $property->setAccessible(true);

        $sql = "select * from test_table where name = :name and hoschi = 'sld''fn:sdfsf' and id = :id";
        $sql_converted = $method->invoke($this->statement, $sql);
        $this->assertEquals("select * from test_table where name = ? and hoschi = 'sld''fn:sdfsf' and id = ?", $sql_converted);
        $nameToPositionalMap = $property->getValue($this->statement);
        $this->assertEquals("name", $nameToPositionalMap[1]);
        $this->assertEquals("id", $nameToPositionalMap[2]);
    }
    
    public function testReplaceNamedParametersWithPositionalsMultiple()
    {
        $method = new ReflectionMethod('Crate\PDO\PDOStatement', 'replaceNamedParametersWithPositionals');
        $method->setAccessible(true);
        $property = new ReflectionProperty('Crate\PDO\PDOStatement', 'namedToPositionalMap');
        $property->setAccessible(true);

        $sql = "update test_table set name = concat(name, :name) where id = :id and name != :name";
        $sql_converted = $method->invoke($this->statement, $sql);
        $this->assertEquals("update test_table set name = concat(name, ?) where id = ? and name != ?", $sql_converted);
        $nameToPositionalMap = $property->getValue($this->statement);
        $this->assertEquals("name", $nameToPositionalMap[1]);
        $this->assertEquals("id", $nameToPositionalMap[2]);
        $this->assertEquals("name", $nameToPositionalMap[3]);
    }
    
}

