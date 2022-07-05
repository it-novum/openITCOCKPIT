<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StatuspageHostsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StatuspageHostsTable Test Case
 */
class StatuspageHostsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\StatuspageHostsTable
     */
    protected $StatuspageHosts;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.StatuspageHosts',
        'app.Statuspages',
        'app.Hosts',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('StatuspageHosts') ? [] : ['className' => StatuspageHostsTable::class];
        $this->StatuspageHosts = $this->getTableLocator()->get('StatuspageHosts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->StatuspageHosts);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\StatuspageHostsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\StatuspageHostsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
