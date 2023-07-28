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
}
