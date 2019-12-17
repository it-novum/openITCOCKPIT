<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HosttemplatesToHostgroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HosttemplatesToHostgroupsTable Test Case
 */
class HosttemplatesToHostgroupsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HosttemplatesToHostgroupsTable
     */
    protected $HosttemplatesToHostgroups;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.HosttemplatesToHostgroups',
        'app.Hosttemplates',
        'app.Hostgroups',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('HosttemplatesToHostgroups') ? [] : ['className' => HosttemplatesToHostgroupsTable::class];
        $this->HosttemplatesToHostgroups = TableRegistry::getTableLocator()->get('HosttemplatesToHostgroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->HosttemplatesToHostgroups);

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
