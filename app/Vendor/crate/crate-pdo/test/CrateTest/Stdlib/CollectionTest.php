<?php
/**
 * @author Antoine Hedgcock
 */

namespace CrateTest\Stdlib;

use Crate\Stdlib\Collection;
use PHPUnit_Framework_TestCase;

/**
 * Class CollectionTest
 *
 * @coversDefaultClass \Crate\Stdlib\Collection
 * @covers ::<!public>
 *
 * @group unit
 */
class CollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $rows = [
        [1, 'hello'],
        [2, 'world']
    ];

    /**
     * @var array
     */
    private $columns = ['id', 'name'];

    /**
     * @var Collection
     */
    private $collection;

    /**
     * @covers ::__construct
     */
    protected function setUp()
    {
        $this->collection = new Collection($this->rows, $this->columns, 0, count($this->rows));
    }

    /**
     * @covers ::map
     */
    public function testMap()
    {
        $result = $this->collection->map(function(array $row) {
            return implode(':', $row);
        });

        $this->assertEquals(['1:hello', '2:world'], $result);
    }

    /**
     * @covers ::getColumnIndex
     */
    public function testGetColumnIndex()
    {
        $this->assertNull($this->collection->getColumnIndex('helloWorld'));

        $this->assertEquals(0, $this->collection->getColumnIndex('id'));
        $this->assertEquals(1, $this->collection->getColumnIndex('name'));
    }

    /**
     * @covers ::getColumns
     */
    public function testGetColumns()
    {
        $this->assertEquals(['id' => 0, 'name' => 1], $this->collection->getColumns());
        $this->assertEquals(['id', 'name'], $this->collection->getColumns(false));
    }

    /**
     * @covers ::getColumns
     */
    public function testGetColumnsSameColumnTwice() {
        $this->collection = new Collection([], ['id', 'id'], 0, 2);
        $this->assertEquals(['id' => 0, 'id' => 1], $this->collection->getColumns());
    }

    /**
     * @covers ::getRows
     */
    public function testGetRows()
    {
        $this->assertEquals($this->rows, $this->collection->getRows());
    }

    /**
     * @covers ::count
     */
    public function testCount()
    {
        $this->assertEquals(count($this->rows), $this->collection->count());
    }

    public function testIterator()
    {
        $this->assertInstanceOf('Iterator', $this->collection);

        foreach ($this->collection as $index => $row) {
            $this->assertEquals($this->rows[$index], $row);
        }
    }
}
