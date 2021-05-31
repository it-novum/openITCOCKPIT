<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NotificationMessagesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NotificationMessagesTable Test Case
 */
class NotificationMessagesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\NotificationMessagesTable
     */
    protected $NotificationMessages;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.NotificationMessages',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('NotificationMessages') ? [] : ['className' => NotificationMessagesTable::class];
        $this->NotificationMessages = $this->getTableLocator()->get('NotificationMessages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->NotificationMessages);

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
}
