<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HosttemplatesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HosttemplatesTable Test Case
 */
class HosttemplatesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HosttemplatesTable
     */
    public $Hosttemplates;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Hosttemplates',
        'app.Hosttemplatetypes',
        'app.Commands',
        'app.EventhandlerCommands',
        'app.Timeperiods',
        'app.CheckPeriods',
        'app.NotifyPeriods',
        'app.Containers',
        'app.ContactgroupsToHosttemplates',
        'app.ContactsToHosttemplates',
        'app.DeletedHosts',
        'app.Hosts',
        'app.Hosttemplatecommandargumentvalues',
        'app.HosttemplatesToHostgroups',
        'app.IdoitObjects',
        'app.IdoitObjecttypes',
        'app.NmapConfigurations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Hosttemplates') ? [] : ['className' => HosttemplatesTable::class];
        $this->Hosttemplates = TableRegistry::getTableLocator()->get('Hosttemplates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Hosttemplates);

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
