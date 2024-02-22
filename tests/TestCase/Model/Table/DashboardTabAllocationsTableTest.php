<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DashboardTabAllocationsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DashboardTabAllocationsTable Test Case
 */
class DashboardTabAllocationsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DashboardTabAllocationsTable
     */
    protected $DashboardTabAllocations;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.DashboardTabAllocations',
        'app.DashboardTabs',
        'app.Containers',
        'app.Users',
        'app.UsergroupsToDashboardTabAllocations',
        'app.UsersToDashboardTabAllocations',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('DashboardTabAllocations') ? [] : ['className' => DashboardTabAllocationsTable::class];
        $this->DashboardTabAllocations = $this->getTableLocator()->get('DashboardTabAllocations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->DashboardTabAllocations);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\DashboardTabAllocationsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\DashboardTabAllocationsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
