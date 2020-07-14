<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class UpdateAgentshostscache extends AbstractMigration {

    public function up() {
        $this->table('agenthostscache')
            ->changeColumn('checkdata', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_MEDIUM])
            ->update();
        $this->table('agentconfigs')
            ->addColumn('push_noticed', 'boolean', [
                'default' => false,
                'null'    => false,
                'after'   => 'password'
            ])
            ->update();
    }

    public function down() {
        $this->table('agenthostscache')
            ->changeColumn('checkdata', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_REGULAR])
            ->update();
        $this->table('agentconfigs')
            ->removeColumn('push_noticed')
            ->update();
    }
}
