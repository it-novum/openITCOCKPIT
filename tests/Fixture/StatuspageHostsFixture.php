<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StatuspageHostsFixture
 */
class StatuspageHostsFixture extends TestFixture
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
                'id' => 1,
                'statuspage_id' => 1,
                'host_id' => 1,
                'display_name' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
