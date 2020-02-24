<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SystemfailuresTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SystemfailuresTable Test Case
 */
class SystemfailuresTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SystemfailuresTable
     */
    public $Systemfailures;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Systemfailures',
        'app.Users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Systemfailures') ? [] : ['className' => SystemfailuresTable::class];
        $this->Systemfailures = TableRegistry::getTableLocator()->get('Systemfailures', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Systemfailures);

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
