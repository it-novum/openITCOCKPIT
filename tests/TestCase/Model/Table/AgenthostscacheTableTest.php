<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AgenthostscacheTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AgenthostscacheTable Test Case
 */
class AgenthostscacheTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AgenthostscacheTable
     */
    protected $Agenthostscache;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Agenthostscache',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Agenthostscache') ? [] : ['className' => AgenthostscacheTable::class];
        $this->Agenthostscache = TableRegistry::getTableLocator()->get('Agenthostscache', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Agenthostscache);

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
