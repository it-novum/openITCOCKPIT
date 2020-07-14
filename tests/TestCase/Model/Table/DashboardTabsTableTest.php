<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DashboardTabsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DashboardTabsTable Test Case
 */
class DashboardTabsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DashboardTabsTable
     */
    protected $DashboardTabs;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.DashboardTabs',
        'app.Users',
        'app.SourceTabs',
        'app.Widgets',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('DashboardTabs') ? [] : ['className' => DashboardTabsTable::class];
        $this->DashboardTabs = TableRegistry::getTableLocator()->get('DashboardTabs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->DashboardTabs);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
