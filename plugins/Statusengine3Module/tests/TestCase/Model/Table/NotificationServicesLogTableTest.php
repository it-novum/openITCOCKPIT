<?php
declare(strict_types=1);

namespace Statusengine3Module\Test\TestCase\Model\Table;

use Cake\TestSuite\TestCase;
use Statusengine3Module\Model\Table\NotificationServicesLogTable;

/**
 * Statusengine3Module\Model\Table\NotificationServicesLogTable Test Case
 */
class NotificationServicesLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Statusengine3Module\Model\Table\NotificationServicesLogTable
     */
    protected $NotificationServicesLog;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'plugin.Statusengine3Module.NotificationServicesLog',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('NotificationServicesLog') ? [] : ['className' => NotificationServicesLogTable::class];
        $this->NotificationServicesLog = $this->getTableLocator()->get('NotificationServicesLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->NotificationServicesLog);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \Statusengine3Module\Model\Table\NotificationServicesLogTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
