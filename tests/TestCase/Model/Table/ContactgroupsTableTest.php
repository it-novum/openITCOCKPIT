<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ContactgroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ContactgroupsTable Test Case
 */
class ContactgroupsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ContactgroupsTable
     */
    public $Contactgroups;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Contactgroups',
        'app.Containers',
        'app.ContactgroupsToHostescalations',
        'app.ContactgroupsToHosts',
        'app.ContactgroupsToHosttemplates',
        'app.ContactgroupsToServiceescalations',
        'app.ContactgroupsToServices',
        'app.ContactgroupsToServicetemplates',
        'app.ContactsToContactgroups',
        'app.NagiosContactgroupMembers',
        'app.NagiosContactgroups'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Contactgroups') ? [] : ['className' => ContactgroupsTable::class];
        $this->Contactgroups = TableRegistry::getTableLocator()->get('Contactgroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Contactgroups);

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
