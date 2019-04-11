<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServicegroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServicegroupsTable Test Case
 */
class ServicegroupsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServicegroupsTable
     */
    public $Servicegroups;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Servicegroups',
        'app.Containers',
        'app.InstantreportsToServicegroups',
        'app.NagiosServicegroupMembers',
        'app.NagiosServicegroups',
        'app.ServicegroupsToServicedependencies',
        'app.ServicegroupsToServiceescalations',
        'app.ServicesToServicegroups',
        'app.ServicetemplatesToServicegroups'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Servicegroups') ? [] : ['className' => ServicegroupsTable::class];
        $this->Servicegroups = TableRegistry::getTableLocator()->get('Servicegroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Servicegroups);

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
