<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StatuspagesFixture
 */
class StatuspagesFixture extends TestFixture
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
                'public' => 1,
                'show_comments' => 1,
                'created' => '2023-08-28 05:19:13',
                'modified' => '2023-08-28 05:19:13',
            ],
        ];
        parent::init();
    }
}
