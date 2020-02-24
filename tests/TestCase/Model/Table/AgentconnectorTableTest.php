<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AgentconnectorTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AgentconnectorTable Test Case
 */
class AgentconnectorTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AgentconnectorTable
     */
    protected $Agentconnector;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Agentconnector',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Agentconnector') ? [] : ['className' => AgentconnectorTable::class];
        $this->Agentconnector = TableRegistry::getTableLocator()->get('Agentconnector', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Agentconnector);

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
}
