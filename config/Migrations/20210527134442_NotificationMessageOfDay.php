<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class NotificationMessageOfDay extends AbstractMigration {
    public $autoId = false;
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change() {
        $table = $this->table('notification_messages');
        $table->addColumn('id', 'integer', [
            'autoIncrement' => true,
            'default'       => null,
            'limit'         => 10,
            'null'          => false,
        ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit'   => 255,
                'null'    => true,
            ])
            ->addColumn('message', 'string', [
                'default' => null,
                'limit'   => 255,
                'null'    => true,
            ])
            ->addColumn('date', 'string', [
                'default' => null,
                'limit'   => 255,
                'null'    => true,
            ])
            ->addColumn('time', 'string', [
                'default' => null,
                'limit'   => 255,
                'null'    => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit'   => null,
                'null'    => false,
            ])
            ->create();
    }
}
