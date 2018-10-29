<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\Core\System\Health;


class MySQLHealth {

    /**
     * @var \Model
     */
    private $Model;

    public function __construct(\Model $Model) {
        $this->Model = $Model;
    }

    /**
     * @return array
     */
    public function selectInnoDbMetrics() {
        $db = $this->getDb();
        $rawResult = $db->fetchAll('SHOW GLOBAL STATUS LIKE "innodb%";');
        return $this->parseGlobalStatusResult($rawResult);
    }

    /**
     * @return array
     */
    public function selectTmpMetrics() {
        $db = $this->getDb();
        $rawResult = $db->fetchAll('SHOW GLOBAL STATUS LIKE "Created_tmp_%";');
        return $this->parseGlobalStatusResult($rawResult);
    }

    /**
     * @return array
     */
    public function selectQueryCacheMetrics() {
        $db = $this->getDb();
        $rawResult = $db->fetchAll('SHOW GLOBAL STATUS LIKE "Qcache%";');
        return $this->parseGlobalStatusResult($rawResult);
    }

    public function selectComMetrics() {
        $db = $this->getDb();
        $fields = [
            'Com_alter_table',
            'Com_commit',
            'Com_create_table',
            'Com_delete',
            'Com_insert',
            'Com_select',
            'Com_set_option',
            'Com_show_create_table',
            'Com_truncate',
            'Com_update'
        ];
        $rawResult = $db->fetchAll(sprintf(
            "SHOW GLOBAL STATUS WHERE Variable_name IN ('%s');",
            implode("', '", $fields)
        ));
        return $this->parseGlobalStatusResult($rawResult);
    }

    /**
     * @return array
     */
    public function getAllMetrics() {
        $metrics = [];
        foreach ($this->selectInnoDbMetrics() as $key => $value) {
            $metrics[$key] = $value;
        }

        foreach ($this->selectComMetrics() as $key => $value) {
            $metrics[$key] = $value;
        }

        foreach ($this->selectQueryCacheMetrics() as $key => $value) {
            $metrics[$key] = $value;
        }

        foreach ($this->selectTmpMetrics() as $key => $value) {
            $metrics[$key] = $value;
        }
        return $metrics;
    }

    /**
     * @param $rawResult
     * @return array
     */
    private function parseGlobalStatusResult($rawResult) {
        $result = [];
        foreach ($rawResult as $record) {
            if (isset($record['STATUS'])) {
                $result[$record['STATUS']['Variable_name']] = $record['STATUS']['Value'];
            }
            if (isset($record['global_status'])) {
                $result[$record['global_status']['Variable_name']] = $record['global_status']['Value'];
            }
        }
        return $result;
    }

    /**
     * @return \DataSource
     */
    private function getDb() {
        return $this->Model->getDataSource();
    }

}
