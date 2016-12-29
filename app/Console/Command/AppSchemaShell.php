<?php
// Copyright (C) <2015>  <it-novum GmbH>
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

App::uses('SchemaShell', 'Console/Command');

class AppSchemaShell extends SchemaShell
{
    /**
     * Overriding update to have non-interactive schema updates
     *
     * @param CakeSchema $Schema
     * @param string     $table
     *
     * @return void
     */
    protected function _update(&$Schema, $table = null)
    {
        $db = ConnectionManager::getDataSource($this->Schema->connection);

        $this->out(__d('cake_console', 'Comparing Database to Schema...'));
        $options = [];
        if (isset($this->params['force'])) {
            $options['models'] = false;
        }
        $Old = $this->Schema->read($options);
        $compare = $this->Schema->compare($Old, $Schema);

        $contents = [];

        if (empty($table)) {
            foreach ($compare as $table => $changes) {
                if (isset($compare[$table]['create'])) {
                    $contents[$table] = $db->createSchema($Schema, $table);
                } else {
                    $contents[$table] = $db->alterSchema([$table => $compare[$table]], $table);
                }
            }
        } elseif (isset($compare[$table])) {
            if (isset($compare[$table]['create'])) {
                $contents[$table] = $db->createSchema($Schema, $table);
            } else {
                $contents[$table] = $db->alterSchema([$table => $compare[$table]], $table);
            }
        }

        if (empty($contents)) {
            $this->out(__d('cake_console', 'Schema is up to date.'));

            return $this->_stop();
        }

        $this->out("\n".__d('cake_console', 'The following statements will run.'));
        $this->out(array_map('trim', $contents));

        $this->out(__d('cake_console', 'Updating Database...'));
        $this->_run($contents, 'update', $Schema);
        $this->out(__d('cake_console', 'End update.'));
    }
}
