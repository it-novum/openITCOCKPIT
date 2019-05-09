<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServiceeventcommandargumentvaluesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServiceeventcommandargumentvaluesTable Test Case
 */
class ServiceeventcommandargumentvaluesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServiceeventcommandargumentvaluesTable
     */
    public $Serviceeventcommandargumentvalues;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Serviceeventcommandargumentvalues',
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
        $config = TableRegistry::getTableLocator()->exists('Serviceeventcommandargumentvalues') ? [] : ['className' => ServiceeventcommandargumentvaluesTable::class];
        $this->Serviceeventcommandargumentvalues = TableRegistry::getTableLocator()->get('Serviceeventcommandargumentvalues', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Serviceeventcommandargumentvalues);

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
