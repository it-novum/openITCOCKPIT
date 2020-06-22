<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddModuleToConfigurationQueue extends AbstractMigration {

    public function up() {
        $this->table('configuration_queue')
            ->addColumn('module', 'string', [
                'default' => null,
                'limit'   => 255,
                'null'    => true,
                'after'   => 'json_data'
            ])
            ->update();
    }

    public function down() {
        $this->table('configuration_queue')
            ->removeColumn('module')
            ->update();
    }
}
