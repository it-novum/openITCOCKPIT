<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsercontainerrolesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsercontainerrolesTable Test Case
 */
class UsercontainerrolesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsercontainerrolesTable
     */
    public $Usercontainerroles;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Usercontainerroles',
        'app.UsercontainerrolesToContainers',
        'app.UsersToUsercontainerroles'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Usercontainerroles') ? [] : ['className' => UsercontainerrolesTable::class];
        $this->Usercontainerroles = TableRegistry::getTableLocator()->get('Usercontainerroles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Usercontainerroles);

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
