<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CronschedulesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CronschedulesTable Test Case
 */
class CronschedulesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CronschedulesTable
     */
    public $Cronschedules;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Cronschedules',
        'app.Cronjobs'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Cronschedules') ? [] : ['className' => CronschedulesTable::class];
        $this->Cronschedules = TableRegistry::getTableLocator()->get('Cronschedules', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Cronschedules);

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
