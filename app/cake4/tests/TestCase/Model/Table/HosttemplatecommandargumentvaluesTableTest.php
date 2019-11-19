<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HosttemplatecommandargumentvaluesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HosttemplatecommandargumentvaluesTable Test Case
 */
class HosttemplatecommandargumentvaluesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HosttemplatecommandargumentvaluesTable
     */
    public $Hosttemplatecommandargumentvalues;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Hosttemplatecommandargumentvalues',
        'app.Commandarguments',
        'app.Hosttemplates'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Hosttemplatecommandargumentvalues') ? [] : ['className' => HosttemplatecommandargumentvaluesTable::class];
        $this->Hosttemplatecommandargumentvalues = TableRegistry::getTableLocator()->get('Hosttemplatecommandargumentvalues', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Hosttemplatecommandargumentvalues);

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
