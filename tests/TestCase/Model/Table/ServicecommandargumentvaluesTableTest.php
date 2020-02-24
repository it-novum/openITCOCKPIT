<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServicecommandargumentvaluesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServicecommandargumentvaluesTable Test Case
 */
class ServicecommandargumentvaluesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServicecommandargumentvaluesTable
     */
    public $Servicecommandargumentvalues;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Servicecommandargumentvalues',
        'app.Commandarguments',
        'app.Services'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Servicecommandargumentvalues') ? [] : ['className' => ServicecommandargumentvaluesTable::class];
        $this->Servicecommandargumentvalues = TableRegistry::getTableLocator()->get('Servicecommandargumentvalues', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Servicecommandargumentvalues);

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
