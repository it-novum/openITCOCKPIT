<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class UserDefinedServiceTable extends AbstractMigration {
    public $autoId = false;
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change() {
        $this->table('service_table_configs')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default'       => null,
                'limit'         => 10,
                'null'          => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit'   => 11,
                'null'    => false,
            ])
            ->addColumn('custom_state', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_acknowledgement', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_indowntime', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_grapher', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_passive', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_priority', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_servicename', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_last_change', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_last_check', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_next_check', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_service_output', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_instance', 'integer', [
                'default' => '0',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_description', 'integer', [
                'default' => '0',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_container_name', 'integer', [
                'default' => '0',
                'limit'   => 1,
                'null'    => false,
            ])
            ->create();
    }
}
