<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MessagesOtdTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MessagesOtdTable Test Case
 */
class MessagesOtdTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MessagesOtdTable
     */
    protected $MessagesOtd;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.MessagesOtd',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('MessagesOtd') ? [] : ['className' => MessagesOtdTable::class];
        $this->MessagesOtd = $this->getTableLocator()->get('MessagesOtd', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->MessagesOtd);

        parent::tearDown();
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
