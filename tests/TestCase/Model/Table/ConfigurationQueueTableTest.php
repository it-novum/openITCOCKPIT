<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConfigurationQueueTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConfigurationQueueTable Test Case
 */
class ConfigurationQueueTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ConfigurationQueueTable
     */
    public $ConfigurationQueue;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ConfigurationQueue'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ConfigurationQueue') ? [] : ['className' => ConfigurationQueueTable::class];
        $this->ConfigurationQueue = TableRegistry::getTableLocator()->get('ConfigurationQueue', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ConfigurationQueue);

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
}
