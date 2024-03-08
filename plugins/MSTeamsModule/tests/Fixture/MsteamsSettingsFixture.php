<?php
declare(strict_types=1);

namespace MSTeamsModule\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MsteamsSettingsFixture
 */
class MsteamsSettingsFixture extends TestFixture
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
                'webhook_url' => 'Lorem ipsum dolor sit amet',
                'two_way' => 1,
                'apikey' => 'Lorem ipsum dolor sit amet',
                'use_proxy' => 1,
                'created' => '2024-01-18 11:10:24',
                'modified' => '2024-01-18 11:10:24',
            ],
        ];
        parent::init();
    }
}
