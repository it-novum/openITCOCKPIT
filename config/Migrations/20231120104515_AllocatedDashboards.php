<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.

declare(strict_types=1);

use Migrations\AbstractMigration;

/**
 * Class AllocatedDashboards
 *
 * Usage:
 * openitcockpit-update
 */
class AllocatedDashboards extends AbstractMigration {
    /** @inheritdoc */
    public $autoId = false;

    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up(): void {
        if (!$this->hasTable('usergroups_to_dashboard_tabs')) {
            $this->table('usergroups_to_dashboard_tabs')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default'       => null,
                    'limit'         => 11,
                    'null'          => false,
                ])
                ->addPrimaryKey(['id'])
                ->addColumn('usergroup_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('dashboard_tab_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->create();
        }
        if (!$this->hasTable('users_to_dashboard_tabs')) {
            $this->table('users_to_dashboard_tabs')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default'       => null,
                    'limit'         => 11,
                    'null'          => false,
                ])
                ->addPrimaryKey(['id'])
                ->addColumn('user_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('dashboard_tab_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->create();
        }
        if ($this->hasTable('dashboard_tabs')) {
            $this->table('dashboard_tabs')
                ->addColumn('container_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => true,
                ])
                ->addColumn('flags', 'integer', [
                    'default' => 0,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->save();
        }
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     * @return void
     */
    public function down(): void {
        $this->table('usergroups_to_dashboard_tabs')->drop()->save();
        $this->table('users_to_dashboard_tabs')->drop()->save();
    }
}
