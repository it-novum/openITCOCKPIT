<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AutomapsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AutomapsTable Test Case
 */
class AutomapsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AutomapsTable
     */
    public $Automaps;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Automaps',
        'app.Containers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Automaps') ? [] : ['className' => AutomapsTable::class];
        $this->Automaps = TableRegistry::getTableLocator()->get('Automaps', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Automaps);

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
