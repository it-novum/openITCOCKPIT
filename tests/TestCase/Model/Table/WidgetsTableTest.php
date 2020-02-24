<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WidgetsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WidgetsTable Test Case
 */
class WidgetsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\WidgetsTable
     */
    protected $Widgets;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Widgets',
        'app.DashboardTabs',
        'app.Types',
        'app.Hosts',
        'app.Services',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Widgets') ? [] : ['className' => WidgetsTable::class];
        $this->Widgets = TableRegistry::getTableLocator()->get('Widgets', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Widgets);

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
