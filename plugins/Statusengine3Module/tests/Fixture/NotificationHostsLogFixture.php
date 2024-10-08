<?php
declare(strict_types=1);

namespace Statusengine3Module\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * NotificationHostsLogFixture
 */
class NotificationHostsLogFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'statusengine_host_notifications_log';
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'hostname' => 'bff92f86-c576-4c02-9525-94ef4cee65b5',
                'start_time' => 1,
                'start_time_usec' => 1,
                'end_time' => 1,
                'state' => 1,
                'reason_type' => 1,
                'is_escalated' => 1,
                'contacts_notified_count' => 1,
                'output' => 'Lorem ipsum dolor sit amet',
                'ack_author' => 'Lorem ipsum dolor sit amet',
                'ack_data' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
