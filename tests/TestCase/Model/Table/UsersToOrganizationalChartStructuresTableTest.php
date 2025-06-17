<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersToOrganizationalChartStructuresTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersToOrganizationalChartStructuresTable Test Case
 */
class UsersToOrganizationalChartStructuresTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersToOrganizationalChartStructuresTable
     */
    protected $UsersToOrganizationalChartStructures;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.UsersToOrganizationalChartStructures',
        'app.Users',
        'app.OrganizationalChartStructures',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('UsersToOrganizationalChartStructures') ? [] : ['className' => UsersToOrganizationalChartStructuresTable::class];
        $this->UsersToOrganizationalChartStructures = $this->getTableLocator()->get('UsersToOrganizationalChartStructures', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->UsersToOrganizationalChartStructures);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\UsersToOrganizationalChartStructuresTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\UsersToOrganizationalChartStructuresTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
