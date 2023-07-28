<?php
declare(strict_types=1);

namespace ChangecalendarModule\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ChangecalendarEventsFixture
 */
class ChangecalendarEventsFixture extends TestFixture
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
                'title' => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet',
                'start' => '2023-07-26 08:09:17',
                'end' => '2023-07-26 08:09:17',
                'uid' => 'Lorem ipsum dolor sit amet',
                'context' => '',
                'created' => '2023-07-26 08:09:17',
                'modified' => '2023-07-26 08:09:17',
                'changecalendar_id' => 1,
                'user_id' => 1,
            ],
        ];
        parent::init();
    }
}
