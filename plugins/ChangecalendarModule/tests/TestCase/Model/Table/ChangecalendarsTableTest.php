<?php
declare(strict_types=1);

namespace ChangecalendarModule\Test\TestCase\Model\Table;

use Cake\TestSuite\TestCase;
use ChangecalendarModule\Model\Table\ChangecalendarsTable;

/**
 * ChangecalendarModule\Model\Table\ChangecalendarsTable Test Case
 */
class ChangecalendarsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \ChangecalendarModule\Model\Table\ChangecalendarsTable
     */
    protected $Changecalendars;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'plugin.ChangecalendarModule.Changecalendars',
        'plugin.ChangecalendarModule.Containers',
        'plugin.ChangecalendarModule.Users',
        'plugin.ChangecalendarModule.ChangecalendarEvents',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Changecalendars') ? [] : ['className' => ChangecalendarsTable::class];
        $this->Changecalendars = $this->getTableLocator()->get('Changecalendars', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Changecalendars);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \ChangecalendarModule\Model\Table\ChangecalendarsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \ChangecalendarModule\Model\Table\ChangecalendarsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getChangecalendarsIndex method
     *
     * @return void
     * @uses \ChangecalendarModule\Model\Table\ChangecalendarsTable::getChangecalendarsIndex()
     */
    public function testGetChangecalendarsIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test existsById method
     *
     * @return void
     * @uses \ChangecalendarModule\Model\Table\ChangecalendarsTable::existsById()
     */
    public function testExistsById(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getCalendarByIdForEdit method
     *
     * @return void
     * @uses \ChangecalendarModule\Model\Table\ChangecalendarsTable::getCalendarByIdForEdit()
     */
    public function testGetCalendarByIdForEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test formatResultAsCake2 method
     *
     * @return void
     * @uses \ChangecalendarModule\Model\Table\ChangecalendarsTable::formatResultAsCake2()
     */
    public function testFormatResultAsCake2(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test formatFirstResultAsCake2 method
     *
     * @return void
     * @uses \ChangecalendarModule\Model\Table\ChangecalendarsTable::formatFirstResultAsCake2()
     */
    public function testFormatFirstResultAsCake2(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test formatListAsCake2 method
     *
     * @return void
     * @uses \ChangecalendarModule\Model\Table\ChangecalendarsTable::formatListAsCake2()
     */
    public function testFormatListAsCake2(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test emptyArrayIfNull method
     *
     * @return void
     * @uses \ChangecalendarModule\Model\Table\ChangecalendarsTable::emptyArrayIfNull()
     */
    public function testEmptyArrayIfNull(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test paginate method
     *
     * @return void
     * @uses \ChangecalendarModule\Model\Table\ChangecalendarsTable::paginate()
     */
    public function testPaginate(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test paginateCake4 method
     *
     * @return void
     * @uses \ChangecalendarModule\Model\Table\ChangecalendarsTable::paginateCake4()
     */
    public function testPaginateCake4(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test scroll method
     *
     * @return void
     * @uses \ChangecalendarModule\Model\Table\ChangecalendarsTable::scroll()
     */
    public function testScroll(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test scrollCake4 method
     *
     * @return void
     * @uses \ChangecalendarModule\Model\Table\ChangecalendarsTable::scrollCake4()
     */
    public function testScrollCake4(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
