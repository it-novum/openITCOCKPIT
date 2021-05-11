<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class ChangeMaxLimitForJson extends AbstractMigration {
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change() {
        $this->table('widgets')
            ->changeColumn('json_data', 'text', [
                'default' => null,
                'limit'   => \Phinx\Db\Adapter\MysqlAdapter::TEXT_REGULAR,
                'null'    => true,
            ])
            ->save();

    }
}
