<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ApikeysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ApikeysTable Test Case
 */
class ApikeysTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ApikeysTable
     */
    public $Apikeys;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Apikeys',
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
        $config = TableRegistry::getTableLocator()->exists('Apikeys') ? [] : ['className' => ApikeysTable::class];
        $this->Apikeys = TableRegistry::getTableLocator()->get('Apikeys', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Apikeys);

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
