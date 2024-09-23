<?php
declare(strict_types=1);

namespace Statusengine3Module\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * NotificationServicesLogFixture
 */
class NotificationServicesLogFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'statusengine_service_notifications_log';
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'hostname' => 'a8fc233b-d0ad-4dba-8cc6-e79343cd9c45',
                'service_description' => '39a9be72-c76f-4646-a992-cb55e028f17d',
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
