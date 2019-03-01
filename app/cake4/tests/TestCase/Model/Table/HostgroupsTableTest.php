<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HostgroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HostgroupsTable Test Case
 */
class HostgroupsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HostgroupsTable
     */
    public $Hostgroups;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Hostgroups',
        'app.Containers',
        'app.HostgroupsToGrafanaconfigurations',
        'app.HostgroupsToHostdependencies',
        'app.HostgroupsToHostescalations',
        'app.HostsToHostgroups',
        'app.HosttemplatesToHostgroups',
        'app.InstantreportsToHostgroups',
        'app.NagiosHostgroupMembers',
        'app.NagiosHostgroups'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Hostgroups') ? [] : ['className' => HostgroupsTable::class];
        $this->Hostgroups = TableRegistry::getTableLocator()->get('Hostgroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Hostgroups);

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
