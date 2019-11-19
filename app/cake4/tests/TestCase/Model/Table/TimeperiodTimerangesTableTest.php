<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TimeperiodTimerangesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TimeperiodTimerangesTable Test Case
 */
class TimeperiodTimerangesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TimeperiodTimerangesTable
     */
    public $TimeperiodTimeranges;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.TimeperiodTimeranges',
        'app.Timeperiods',
        'app.NagiosTimeperiodTimeranges'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('TimeperiodTimeranges') ? [] : ['className' => TimeperiodTimerangesTable::class];
        $this->TimeperiodTimeranges = TableRegistry::getTableLocator()->get('TimeperiodTimeranges', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TimeperiodTimeranges);

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
