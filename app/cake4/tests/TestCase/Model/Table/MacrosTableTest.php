<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MacrosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MacrosTable Test Case
 */
class MacrosTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MacrosTable
     */
    public $Macros;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Macros'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Macros') ? [] : ['className' => MacrosTable::class];
        $this->Macros = TableRegistry::getTableLocator()->get('Macros', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Macros);

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
}
