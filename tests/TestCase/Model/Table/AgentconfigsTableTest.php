<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AgentconfigsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AgentconfigsTable Test Case
 */
class AgentconfigsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AgentconfigsTable
     */
    public $Agentconfigs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Agentconfigs',
        'app.Hosts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Agentconfigs') ? [] : ['className' => AgentconfigsTable::class];
        $this->Agentconfigs = TableRegistry::getTableLocator()->get('Agentconfigs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Agentconfigs);

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
