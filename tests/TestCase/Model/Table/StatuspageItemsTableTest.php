<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StatuspageItemsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StatuspageItemsTable Test Case
 */
class StatuspageItemsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\StatuspageItemsTable
     */
    protected $StatuspageItems;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.StatuspageItems',
        'app.Statuspages',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('StatuspageItems') ? [] : ['className' => StatuspageItemsTable::class];
        $this->StatuspageItems = $this->getTableLocator()->get('StatuspageItems', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->StatuspageItems);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\StatuspageItemsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\StatuspageItemsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
