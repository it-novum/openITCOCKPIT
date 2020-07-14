<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServicesToServicegroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServicesToServicegroupsTable Test Case
 */
class ServicesToServicegroupsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServicesToServicegroupsTable
     */
    protected $ServicesToServicegroups;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.ServicesToServicegroups',
        'app.Services',
        'app.Servicegroups',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ServicesToServicegroups') ? [] : ['className' => ServicesToServicegroupsTable::class];
        $this->ServicesToServicegroups = TableRegistry::getTableLocator()->get('ServicesToServicegroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ServicesToServicegroups);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
