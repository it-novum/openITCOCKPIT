<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChangelogsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChangelogsTable Test Case
 */
class ChangelogsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ChangelogsTable
     */
    public $Changelogs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Changelogs',
        'app.Objects',
        'app.Objecttypes',
        'app.Users',
        'app.ChangelogsToContainers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Changelogs') ? [] : ['className' => ChangelogsTable::class];
        $this->Changelogs = TableRegistry::getTableLocator()->get('Changelogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Changelogs);

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
