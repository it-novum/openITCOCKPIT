<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AgentchecksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AgentchecksTable Test Case
 */
class AgentchecksTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AgentchecksTable
     */
    public $Agentchecks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Agentchecks',
        'app.Servicetemplates'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Agentchecks') ? [] : ['className' => AgentchecksTable::class];
        $this->Agentchecks = TableRegistry::getTableLocator()->get('Agentchecks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Agentchecks);

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
