<?php
declare(strict_types=1);

namespace ChangecalendarModule\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ChangecalendarsFixture
 */
class ChangecalendarsFixture extends TestFixture
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
                'description' => 'Lorem ipsum dolor sit amet',
                'colour' => 'Lorem',
                'container_id' => 1,
                'user_id' => 1,
                'created' => '2023-07-26 06:41:01',
                'modified' => '2023-07-26 06:41:01',
            ],
        ];
        parent::init();
    }
}
