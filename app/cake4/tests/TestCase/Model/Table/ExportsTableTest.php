<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExportsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExportsTable Test Case
 */
class ExportsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ExportsTable
     */
    protected $Exports;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Exports',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Exports') ? [] : ['className' => ExportsTable::class];
        $this->Exports = TableRegistry::getTableLocator()->get('Exports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Exports);

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
}
