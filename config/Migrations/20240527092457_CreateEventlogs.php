<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//     This program is free software: you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation, version 3 of the License.
//
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//     If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//     under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//     License agreement and license key will be shipped with the order
//     confirmation.

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateEventlogs extends AbstractMigration {
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void {
        if (!$this->hasTable('eventlogs')) {
            $this->table('eventlogs')
                ->addPrimaryKey(['id'])
                ->addColumn('type', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])
                ->addColumn('model', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])
                ->addColumn('object_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('data', 'text', [
                    'default' => null,
                    'limit'   => null,
                    'null'    => false,
                ])
                ->addColumn('created', 'datetime', [
                    'default' => null,
                    'limit'   => null,
                    'null'    => false,
                ])
                ->addIndex(
                    [
                        'created',
                    ]
                )
                ->create();
        }

        if (!$this->hasTable('eventlogs_to_containers')) {
            $this->table('eventlogs_to_containers')
                ->addPrimaryKey(['id'])
                ->addColumn('eventlog_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('container_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addIndex(
                    [
                        'eventlog_id',
                    ]
                )
                ->addIndex(
                    [
                        'container_id',
                    ]
                )
                ->create();
        }
    }
}
