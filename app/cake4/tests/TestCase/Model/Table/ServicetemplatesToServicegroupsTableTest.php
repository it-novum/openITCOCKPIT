<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServicetemplatesToServicegroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServicetemplatesToServicegroupsTable Test Case
 */
class ServicetemplatesToServicegroupsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServicetemplatesToServicegroupsTable
     */
    protected $ServicetemplatesToServicegroups;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.ServicetemplatesToServicegroups',
        'app.Servicetemplates',
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
        $config = TableRegistry::getTableLocator()->exists('ServicetemplatesToServicegroups') ? [] : ['className' => ServicetemplatesToServicegroupsTable::class];
        $this->ServicetemplatesToServicegroups = TableRegistry::getTableLocator()->get('ServicetemplatesToServicegroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ServicetemplatesToServicegroups);

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
