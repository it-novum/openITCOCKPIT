<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CalendarHolidaysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CalendarHolidaysTable Test Case
 */
class CalendarHolidaysTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CalendarHolidaysTable
     */
    public $CalendarHolidays;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CalendarHolidays',
        'app.Calendars'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CalendarHolidays') ? [] : ['className' => CalendarHolidaysTable::class];
        $this->CalendarHolidays = TableRegistry::getTableLocator()->get('CalendarHolidays', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CalendarHolidays);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
