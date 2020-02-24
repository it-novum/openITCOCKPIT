<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CommandargumentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CommandargumentsTable Test Case
 */
class CommandargumentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CommandargumentsTable
     */
    public $Commandarguments;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Commandarguments',
        'app.Commands',
        'app.Hostcommandargumentvalues',
        'app.Hosttemplatecommandargumentvalues',
        'app.Servicecommandargumentvalues',
        'app.Serviceeventcommandargumentvalues',
        'app.Servicetemplatecommandargumentvalues',
        'app.Servicetemplateeventcommandargumentvalues'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Commandarguments') ? [] : ['className' => CommandargumentsTable::class];
        $this->Commandarguments = TableRegistry::getTableLocator()->get('Commandarguments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Commandarguments);

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
