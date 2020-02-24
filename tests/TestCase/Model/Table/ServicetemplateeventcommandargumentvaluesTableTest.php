<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServicetemplateeventcommandargumentvaluesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServicetemplateeventcommandargumentvaluesTable Test Case
 */
class ServicetemplateeventcommandargumentvaluesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServicetemplateeventcommandargumentvaluesTable
     */
    public $Servicetemplateeventcommandargumentvalues;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Servicetemplateeventcommandargumentvalues',
        'app.Commandarguments',
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
        $config = TableRegistry::getTableLocator()->exists('Servicetemplateeventcommandargumentvalues') ? [] : ['className' => ServicetemplateeventcommandargumentvaluesTable::class];
        $this->Servicetemplateeventcommandargumentvalues = TableRegistry::getTableLocator()->get('Servicetemplateeventcommandargumentvalues', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Servicetemplateeventcommandargumentvalues);

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
