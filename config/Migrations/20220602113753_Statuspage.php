<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Statuspage extends AbstractMigration {

    public $autoId = false;

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change() {
        if (!$this->hasTable('statuspages')) {
            $this->table('statuspages')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default'       => null,
                    'limit'         => 11,
                    'null'          => false,
                ])
                ->addPrimaryKey(['id'])
                ->addColumn('name', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])
                ->addColumn('description', 'string', [
                    'default' => null,
                    'limit'   => 1000,
                    'null'    => true,
                ])
                ->addColumn('public', 'boolean', [
                    'default' => '0',
                    'limit'   => null,
                    'null'    => false,
                ])
                ->addColumn('show_comments', 'boolean', [
                    'default' => '0',
                    'limit'   => null,
                    'null'    => false,
                ])
                ->create();
        }

        if (!$this->hasTable('statuspages_to_containers')) {
            $this->table('statuspages_to_containers')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default'       => null,
                    'limit'         => 11,
                    'null'          => false,
                ])
                ->addPrimaryKey(['id'])
                ->addColumn('container_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('statuspage_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addIndex(
                    [
                        'container_id',
                    ]
                )
                ->addIndex(
                    [
                        'statuspage_id',
                    ]
                )
                ->create();
        }

        if (!$this->hasTable('statuspages_to_hosts')) {
            $this->table('statuspages_to_hosts')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default'       => null,
                    'limit'         => 11,
                    'null'          => false,
                ])
                ->addPrimaryKey(['id'])
                ->addColumn('statuspage_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('host_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('display_name', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => true,
                ])
                ->create();
        }

        if (!$this->hasTable('statuspages_to_services')) {
            $this->table('statuspages_to_services')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default'       => null,
                    'limit'         => 11,
                    'null'          => false,
                ])
                ->addPrimaryKey(['id'])
                ->addColumn('statuspage_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('service_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('display_name', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => true,
                ])
                ->create();
        }

        if (!$this->hasTable('statuspages_to_hostgroups')) {
            $this->table('statuspages_to_hostgroups')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default'       => null,
                    'limit'         => 11,
                    'null'          => false,
                ])
                ->addPrimaryKey(['id'])
                ->addColumn('statuspage_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('hostgroup_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('display_name', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => true,
                ])
                ->create();
        }

        if (!$this->hasTable('statuspages_to_servicegroups')) {
            $this->table('statuspages_to_servicegroups')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default'       => null,
                    'limit'         => 11,
                    'null'          => false,
                ])
                ->addPrimaryKey(['id'])
                ->addColumn('statuspage_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('servicegroup_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('display_name', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => true,
                ])
                ->create();
        }
    }
}
