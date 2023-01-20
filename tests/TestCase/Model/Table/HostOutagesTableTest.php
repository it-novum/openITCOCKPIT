<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HostOutagesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HostOutagesTable Test Case
 */
class HostOutagesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HostOutagesTable
     */
    protected $HostOutages;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.HostOutages',
        'app.Hosts',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('HostOutages') ? [] : ['className' => HostOutagesTable::class];
        $this->HostOutages = $this->getTableLocator()->get('HostOutages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->HostOutages);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\HostOutagesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\HostOutagesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
