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
                'name' => 'Lorem ipsum dolor sit amet',
                'begin' => '2023-07-19 14:00:37',
                'end' => '2023-07-19 14:00:37',
                'created' => '2023-07-19 14:00:37',
                'modified' => '2023-07-19 14:00:37',
                'changecalendar_id' => 1,
                'user_id' => 1,
                'uid' => 'Lorem ipsum dolor sit amet',
                'conotext' => '',
            ],
        ];
        parent::init();
    }
}
