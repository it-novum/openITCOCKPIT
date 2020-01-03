<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HostsToHostgroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HostsToHostgroupsTable Test Case
 */
class HostsToHostgroupsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HostsToHostgroupsTable
     */
    protected $HostsToHostgroups;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.HostsToHostgroups',
        'app.Hosts',
        'app.Hostgroups',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('HostsToHostgroups') ? [] : ['className' => HostsToHostgroupsTable::class];
        $this->HostsToHostgroups = TableRegistry::getTableLocator()->get('HostsToHostgroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->HostsToHostgroups);

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
