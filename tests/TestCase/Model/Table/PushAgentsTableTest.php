<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PushAgentsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PushAgentsTable Test Case
 */
class PushAgentsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PushAgentsTable
     */
    protected $PushAgents;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.PushAgents',
        'app.Agentconfigs',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('PushAgents') ? [] : ['className' => PushAgentsTable::class];
        $this->PushAgents = $this->getTableLocator()->get('PushAgents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->PushAgents);

        parent::tearDown();
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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
