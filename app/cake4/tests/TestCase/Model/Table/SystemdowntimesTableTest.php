<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SystemdowntimesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SystemdowntimesTable Test Case
 */
class SystemdowntimesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SystemdowntimesTable
     */
    public $Systemdowntimes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Systemdowntimes',
        'app.Objecttypes',
        'app.Objects',
        'app.Downtimetypes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Systemdowntimes') ? [] : ['className' => SystemdowntimesTable::class];
        $this->Systemdowntimes = TableRegistry::getTableLocator()->get('Systemdowntimes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Systemdowntimes);

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
