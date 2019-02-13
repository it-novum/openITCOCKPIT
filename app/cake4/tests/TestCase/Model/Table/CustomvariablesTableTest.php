<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomvariablesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomvariablesTable Test Case
 */
class CustomvariablesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomvariablesTable
     */
    public $Customvariables;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Customvariables',
        'app.Objects',
        'app.Objecttypes',
        'app.NagiosCustomvariables'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Customvariables') ? [] : ['className' => CustomvariablesTable::class];
        $this->Customvariables = TableRegistry::getTableLocator()->get('Customvariables', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Customvariables);

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
