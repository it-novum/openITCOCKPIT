<?php
// Copyright (C) <2023>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

declare(strict_types=1);

use Migrations\AbstractMigration;

class Initial extends AbstractMigration {
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
        if (!$this->hasTable('changecalendars')) {
            $this->table('changecalendars')
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
                ->addColumn('description', 'text', [
                    'default' => null,
                    'null'    => true,
                ])
                ->addColumn('colour', 'string', [
                    'default' => null,
                    'limit'   => '7',
                    'null'    => true,
                ])
                ->addColumn('container_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('user_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('created', 'datetime', [
                    'default' => null,
                    'limit'   => null,
                    'null'    => false,
                ])
                ->addColumn('modified', 'datetime', [
                    'default' => null,
                    'limit'   => null,
                    'null'    => false,
                ])
                ->create();
        }

        if (!$this->hasTable('changecalendar_events')) {
            $this->table('changecalendar_events')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default'       => null,
                    'limit'         => 11,
                    'null'          => false,
                ])
                ->addPrimaryKey(['id'])
                ->addColumn('title', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])
                ->addColumn('description', 'text', [
                    'default' => null,
                    'null'    => true,
                ])
                ->addColumn('start', 'datetime', [
                    'default' => null,
                    'null'    => false
                ])
                ->addColumn('end', 'datetime', [
                    'default' => null,
                    'null'    => false
                ])
                ->addColumn('uid', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => true,
                ])
                ->addColumn('context', 'json', [
                    'default' => null,
                    'null'    => true,
                ])
                ->addColumn('created', 'datetime', [
                    'default' => null,
                    'limit'   => null,
                    'null'    => false,
                ])
                ->addColumn('modified', 'datetime', [
                    'default' => null,
                    'limit'   => null,
                    'null'    => false,
                ])
                ->addColumn('changecalendar_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('user_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addIndex(
                    [
                        'changecalendar_id',
                    ]
                )
                ->create();
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
        die('\n DOWN \n');
        $this->table('changecalendar_events')->drop()->save();
        $this->table('changecalendars')->drop()->save();
    }
}
