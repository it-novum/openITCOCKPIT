<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StatuspageItemsFixture
 */
class StatuspageItemsFixture extends TestFixture
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
                'object_id' => 1,
                'display_text' => 'Lorem ipsum dolor sit amet',
                'created' => '2023-08-28 06:21:45',
                'modified' => '2023-08-28 06:21:45',
            ],
        ];
        parent::init();
    }
}
