<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddColumnToUserDefinedTable extends AbstractMigration {
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change() {
        $this->table('table_configs')
            ->addColumn('custom_hoststatus', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])->addColumn('custom_acknowledgement', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])->addColumn('custom_indowntime', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])->addColumn('custom_shared', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])->addColumn('custom_passive', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])
            ->addColumn('custom_priority', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])->addColumn('custom_hostname', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])->addColumn('custom_ip_address', 'integer', [
                'default' => '1',
                'limit'   => 1,
                'null'    => false,
            ])->addColumn('custom_description', 'integer', [
                'default' => '0',
                'limit'   => 1,
                'null'    => false,
            ])->addColumn('custom_container_name', 'integer', [
                'default' => '0',
                'limit'   => 1,
                'null'    => false,
            ])
            ->update();
    }
}
