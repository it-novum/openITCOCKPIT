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
                'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'start' => '2023-08-09 07:09:57',
                'end' => '2023-08-09 07:09:57',
                'uid' => 'Lorem ipsum dolor sit amet',
                'context' => '',
                'created' => '2023-08-09 07:09:57',
                'modified' => '2023-08-09 07:09:57',
                'changecalendar_id' => 1,
                'user_id' => 1,
            ],
        ];
        parent::init();
    }
}
