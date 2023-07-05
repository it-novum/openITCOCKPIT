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
                'begin' => '2023-06-28 07:54:44',
                'end' => '2023-06-28 07:54:44',
                'created' => '2023-06-28 07:54:44',
                'modified' => '2023-06-28 07:54:44',
                'changecalendar_id' => 1,
                'user_id' => 1,
            ],
        ];
        parent::init();
    }
}
