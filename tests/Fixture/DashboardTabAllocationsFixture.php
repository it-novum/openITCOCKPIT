<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DashboardTabAllocationsFixture
 */
class DashboardTabAllocationsFixture extends TestFixture
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
                'dashboard_tab_id' => 1,
                'container_id' => 1,
                'user_id' => 1,
                'pinned' => 1,
                'created' => '2024-02-12 12:58:42',
                'modified' => '2024-02-12 12:58:42',
            ],
        ];
        parent::init();
    }
}
