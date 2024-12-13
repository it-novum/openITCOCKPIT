<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class StatuspageEnhancements extends AbstractMigration {
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void {
        if ($this->hasTable('statuspages')) {
            $this->table('statuspages')
                // If set to >0, the status page will refresh every X seconds
                ->addColumn('refresh', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => true,
                ])
                ->update();
        }
    }

    public function down(): void {
        if ($this->hasTable('statuspages')) {
            $this->table('statuspages')
                ->removeColumn('refresh')
                ->update();
        }
    }
}
