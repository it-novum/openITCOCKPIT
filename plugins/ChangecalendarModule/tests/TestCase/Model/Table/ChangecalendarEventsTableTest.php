<?php
declare(strict_types=1);

namespace ChangecalendarModule\Test\TestCase\Model\Table;

use Cake\TestSuite\TestCase;
use ChangecalendarModule\Model\Table\ChangecalendarEventsTable;

/**
 * ChangecalendarModule\Model\Table\ChangecalendarEventsTable Test Case
 */
class ChangecalendarEventsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \ChangecalendarModule\Model\Table\ChangecalendarEventsTable
     */
    protected $ChangecalendarEvents;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'plugin.ChangecalendarModule.ChangecalendarEvents',
        'plugin.ChangecalendarModule.Changecalendars',
        'plugin.ChangecalendarModule.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ChangecalendarEvents') ? [] : ['className' => ChangecalendarEventsTable::class];
        $this->ChangecalendarEvents = $this->getTableLocator()->get('ChangecalendarEvents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ChangecalendarEvents);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \ChangecalendarModule\Model\Table\ChangecalendarEventsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \ChangecalendarModule\Model\Table\ChangecalendarEventsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
