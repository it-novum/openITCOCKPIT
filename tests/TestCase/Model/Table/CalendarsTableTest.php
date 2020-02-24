<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CalendarsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CalendarsTable Test Case
 */
class CalendarsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CalendarsTable
     */
    public $Calendars;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Calendars',
        'app.CalendarHolidays'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Calendars') ? [] : ['className' => CalendarsTable::class];
        $this->Calendars = TableRegistry::getTableLocator()->get('Calendars', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Calendars);

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
     * Test getCalendarsIndex method
     *
     * @return void
     */
    public function testGetCalendarsIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test formatResultAsCake2 method
     *
     * @return void
     */
    public function testFormatResultAsCake2()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test formatFirstResultAsCake2 method
     *
     * @return void
     */
    public function testFormatFirstResultAsCake2()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test formatListAsCake2 method
     *
     * @return void
     */
    public function testFormatListAsCake2()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test emptyArrayIfNull method
     *
     * @return void
     */
    public function testEmptyArrayIfNull()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test paginate method
     *
     * @return void
     */
    public function testPaginate()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test paginateCake4 method
     *
     * @return void
     */
    public function testPaginateCake4()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test scroll method
     *
     * @return void
     */
    public function testScroll()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test scrollCake4 method
     *
     * @return void
     */
    public function testScrollCake4()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
