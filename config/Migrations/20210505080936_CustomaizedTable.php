<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CustomaizedTable extends AbstractMigration {
    public $autoId = false;

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change() {
        $table = $this->table('dynamic_table_configs');
        $table->addColumn('id', 'integer', [
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
            ])->addColumn('json_data', 'string', [
                'default' => null,
                'limit'   => 2000,
                'null'    => true,
            ])
            ->addColumn('table_name', 'string', [
                'default' => null,
                'limit'   => 255,
                'null'    => true
            ])->create();
    }
}
