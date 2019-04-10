<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServicetemplatecommandargumentvaluesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServicetemplatecommandargumentvaluesTable Test Case
 */
class ServicetemplatecommandargumentvaluesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServicetemplatecommandargumentvaluesTable
     */
    public $Servicetemplatecommandargumentvalues;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Servicetemplatecommandargumentvalues',
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
        $config = TableRegistry::getTableLocator()->exists('Servicetemplatecommandargumentvalues') ? [] : ['className' => ServicetemplatecommandargumentvaluesTable::class];
        $this->Servicetemplatecommandargumentvalues = TableRegistry::getTableLocator()->get('Servicetemplatecommandargumentvalues', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Servicetemplatecommandargumentvalues);

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
