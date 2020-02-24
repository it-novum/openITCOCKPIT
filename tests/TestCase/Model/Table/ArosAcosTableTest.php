<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ArosAcosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ArosAcosTable Test Case
 */
class ArosAcosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ArosAcosTable
     */
    protected $ArosAcos;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.ArosAcos',
        'app.Aros',
        'app.Acos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ArosAcos') ? [] : ['className' => ArosAcosTable::class];
        $this->ArosAcos = TableRegistry::getTableLocator()->get('ArosAcos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ArosAcos);

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
