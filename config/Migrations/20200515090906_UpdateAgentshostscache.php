<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class UpdateAgentshostscache extends AbstractMigration {

    public function up() {
        $this->table('agenthostscache')
            ->changeColumn('checkdata', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_MEDIUM])
            ->update();
    }

    public function down() {
        $this->table('agenthostscache')
            ->changeColumn('checkdata', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_REGULAR])
            ->update();
    }
}
