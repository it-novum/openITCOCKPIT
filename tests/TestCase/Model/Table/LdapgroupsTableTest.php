<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LdapgroupsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LdapgroupsTable Test Case
 */
class LdapgroupsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LdapgroupsTable
     */
    protected $Ldapgroups;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Ldapgroups',
        'app.LdapgroupsToUsercontainerroles',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Ldapgroups') ? [] : ['className' => LdapgroupsTable::class];
        $this->Ldapgroups = $this->getTableLocator()->get('Ldapgroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Ldapgroups);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\LdapgroupsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
