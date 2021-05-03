<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class UserDefinedTable extends AbstractMigration {

    public $autoId = false;
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */

    public function change() {

        $this->table('table_configs')
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
            ->addColumn('custom_last_change', 'integer', [
                'default' => '0',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_last_check', 'integer', [
                'default' => '0',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_host_output', 'integer', [
                'default' => '0',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_instance', 'integer', [
                'default' => '0',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_service_summary', 'integer', [
                'default' => '0',
                'limit'   => 1,
                'null'    => false,
            ])
            ->create();
    }

}
