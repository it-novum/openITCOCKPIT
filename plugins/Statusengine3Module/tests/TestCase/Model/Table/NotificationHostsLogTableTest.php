<?php
declare(strict_types=1);

namespace Statusengine3Module\Test\TestCase\Model\Table;

use Cake\TestSuite\TestCase;
use Statusengine3Module\Model\Table\NotificationHostsLogTable;

/**
 * Statusengine3Module\Model\Table\NotificationHostsLogTable Test Case
 */
class NotificationHostsLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Statusengine3Module\Model\Table\NotificationHostsLogTable
     */
    protected $NotificationHostsLog;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'plugin.Statusengine3Module.NotificationHostsLog',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('NotificationHostsLog') ? [] : ['className' => NotificationHostsLogTable::class];
        $this->NotificationHostsLog = $this->getTableLocator()->get('NotificationHostsLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->NotificationHostsLog);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \Statusengine3Module\Model\Table\NotificationHostsLogTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
