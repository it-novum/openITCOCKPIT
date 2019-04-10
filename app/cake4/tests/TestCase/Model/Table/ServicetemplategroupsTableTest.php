<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServicetemplategroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServicetemplategroupsTable Test Case
 */
class ServicetemplategroupsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServicetemplategroupsTable
     */
    public $Servicetemplategroups;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Servicetemplategroups',
        'app.Containers',
        'app.IdoitLinklists',
        'app.ServicetemplatesToServicetemplategroups'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Servicetemplategroups') ? [] : ['className' => ServicetemplategroupsTable::class];
        $this->Servicetemplategroups = TableRegistry::getTableLocator()->get('Servicetemplategroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Servicetemplategroups);

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
