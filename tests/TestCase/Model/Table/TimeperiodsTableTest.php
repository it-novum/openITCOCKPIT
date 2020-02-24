<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TimeperiodsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TimeperiodsTable Test Case
 */
class TimeperiodsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TimeperiodsTable
     */
    public $Timeperiods;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Timeperiods',
        'app.Containers',
        'app.Calendars',
        'app.Autoreports',
        'app.Hostdependencies',
        'app.Hostescalations',
        'app.Hosts',
        'app.Hosttemplates',
        'app.Instantreports',
        'app.NagiosTimeperiodTimeranges',
        'app.NagiosTimeperiods',
        'app.Servicedependencies',
        'app.Serviceescalations',
        'app.Servicetemplates',
        'app.TimeperiodTimeranges'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Timeperiods') ? [] : ['className' => TimeperiodsTable::class];
        $this->Timeperiods = TableRegistry::getTableLocator()->get('Timeperiods', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Timeperiods);

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
