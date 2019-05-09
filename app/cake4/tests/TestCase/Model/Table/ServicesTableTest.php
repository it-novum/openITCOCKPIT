<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServicesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServicesTable Test Case
 */
class ServicesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServicesTable
     */
    public $Services;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Services',
        'app.Hosts',
        'app.Servicetemplates',
        'app.Mkservicedata',
        'app.Servicecommandargumentvalues'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Services') ? [] : ['className' => ServicesTable::class];
        $this->Services = TableRegistry::getTableLocator()->get('Services', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Services);

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

    /**
     * Test getHostPrimaryContainerIdsByServicetemplateId method
     *
     * @return void
     */
    public function testGetHostPrimaryContainerIdsByServicetemplateId()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getServicesWithHostForServicetemplateUsedBy method
     *
     * @return void
     */
    public function testGetServicesWithHostForServicetemplateUsedBy()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test initializePluginTables method
     *
     * @return void
     */
    public function testInitializePluginTables()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test tableLocator method
     *
     * @return void
     */
    public function testTableLocator()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test setTableLocator method
     *
     * @return void
     */
    public function testSetTableLocator()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getTableLocator method
     *
     * @return void
     */
    public function testGetTableLocator()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
