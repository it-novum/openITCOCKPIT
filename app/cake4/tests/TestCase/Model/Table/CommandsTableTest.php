<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CommandsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CommandsTable Test Case
 */
class CommandsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CommandsTable
     */
    public $Commands;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Commands',
        'app.Commandarguments',
        'app.ContactsToHostcommands',
        'app.ContactsToServicecommands',
        'app.Hosts',
        'app.Hosttemplates',
        'app.NagiosCommands',
        'app.Services',
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
        $config = TableRegistry::getTableLocator()->exists('Commands') ? [] : ['className' => CommandsTable::class];
        $this->Commands = TableRegistry::getTableLocator()->get('Commands', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Commands);

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
