<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StatuspagesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StatuspagesTable Test Case
 */
class StatuspagesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\StatuspagesTable
     */
    protected $Statuspages;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Statuspages',
        'app.StatuspagesToContainers',
        'app.StatuspagesToHostgroups',
        'app.StatuspagesToHosts',
        'app.StatuspagesToServicegroups',
        'app.StatuspagesToServices',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Statuspages') ? [] : ['className' => StatuspagesTable::class];
        $this->Statuspages = $this->getTableLocator()->get('Statuspages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Statuspages);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\StatuspagesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
