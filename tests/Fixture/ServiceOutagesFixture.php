<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ServiceOutagesFixture
 */
class ServiceOutagesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'host_id' => 1,
                'service_id' => 1,
                'start_time' => 1,
                'state_time_usec' => 1,
                'end_time' => 1,
                'output' => 'Lorem ipsum dolor sit amet',
                'is_hardstate' => 1,
                'in_downtime' => 1,
            ],
        ];
        parent::init();
    }
}
