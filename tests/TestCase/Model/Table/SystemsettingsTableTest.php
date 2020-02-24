<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SystemsettingsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SystemsettingsTable Test Case
 */
class SystemsettingsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SystemsettingsTable
     */
    public $Systemsettings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Systemsettings'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Systemsettings') ? [] : ['className' => SystemsettingsTable::class];
        $this->Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Systemsettings);

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
