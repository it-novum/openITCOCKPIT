<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DeletedServicesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DeletedServicesTable Test Case
 */
class DeletedServicesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DeletedServicesTable
     */
    public $DeletedServices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.DeletedServices',
        'app.Servicetemplates',
        'app.Hosts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('DeletedServices') ? [] : ['className' => DeletedServicesTable::class];
        $this->DeletedServices = TableRegistry::getTableLocator()->get('DeletedServices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DeletedServices);

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
