<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DeletedHostsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DeletedHostsTable Test Case
 */
class DeletedHostsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DeletedHostsTable
     */
    public $DeletedHosts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.DeletedHosts',
        'app.Hosttemplates',
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
        $config = TableRegistry::getTableLocator()->exists('DeletedHosts') ? [] : ['className' => DeletedHostsTable::class];
        $this->DeletedHosts = TableRegistry::getTableLocator()->get('DeletedHosts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DeletedHosts);

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
