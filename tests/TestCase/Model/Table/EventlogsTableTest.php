<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EventlogsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EventlogsTable Test Case
 */
class EventlogsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EventlogsTable
     */
    protected $Eventlogs;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Eventlogs',
        'app.EventlogsToContainers',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Eventlogs') ? [] : ['className' => EventlogsTable::class];
        $this->Eventlogs = $this->getTableLocator()->get('Eventlogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Eventlogs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\EventlogsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
