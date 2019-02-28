<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HostsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HostsTable Test Case
 */
class HostsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HostsTable
     */
    public $Hosts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Hosts',
        'app.Containers',
        'app.Hosttemplates',
        'app.Commands',
        'app.EventhandlerCommands',
        'app.Timeperiods',
        'app.CheckPeriods',
        'app.NotifyPeriods',
        'app.Satellites',
        'app.ContactgroupsToHosts',
        'app.ContactsToHosts',
        'app.DeletedHosts',
        'app.DeletedServices',
        'app.Eventcorrelations',
        'app.GrafanaDashboards',
        'app.GrafanaUserdashboardMetrics',
        'app.Hostcommandargumentvalues',
        'app.HostsToAutoreports',
        'app.HostsToContainers',
        'app.HostsToHostdependencies',
        'app.HostsToHostescalations',
        'app.HostsToHostgroups',
        'app.HostsToParenthosts',
        'app.IdoitHosts',
        'app.InstantreportsToHosts',
        'app.LastUsedMkagents',
        'app.Mkservicedata',
        'app.Mksnmp',
        'app.NagiosHostContactgroups',
        'app.NagiosHostContacts',
        'app.NagiosHostParenthosts',
        'app.NagiosHosts',
        'app.Services',
        'app.ServicesToAutoreports',
        'app.Widgets'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Hosts') ? [] : ['className' => HostsTable::class];
        $this->Hosts = TableRegistry::getTableLocator()->get('Hosts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Hosts);

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
