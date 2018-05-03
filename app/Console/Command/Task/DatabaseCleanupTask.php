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

use itnovum\openITCOCKPIT\Core\DbBackend;
use \itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;

class DatabaseCleanupTask extends AppShell implements CronjobInterface {
    public $uses = [
        'Cronjob',
        'Systemsetting',
    ];

    public $modelsToCleanUp = ['Servicecheck', 'Hostcheck', 'Statehistory', 'Logentry', 'Notification', 'Contactnotification', 'Contactnotificationmethod'];
    //public $modelsToCleanUp = ['Servicecheck', 'Hostcheck'];
    public $_systemsettings = [];

    /**
     * @var DbBackend
     */
    private $DbBackend;

    public function execute($quiet = false) {
        $this->params['quiet'] = $quiet;
        $this->stdout->styles('green', ['text' => 'green']);
        $this->stdout->styles('blue', ['text' => 'blue']);
        $this->stdout->styles('red', ['text' => 'red']);

        $this->_systemsettings = $this->Systemsetting->findAsArray();

        Configure::load('dbbackend');
        $this->DbBackend = new DbBackend(Configure::read('dbbackend'));

        if ($this->DbBackend->isNdoUtils()) {
            $models = [
                MONITORING_SERVICECHECK,
                MONITORING_HOSTCHECK,
                MONITORING_STATEHISTORY,
                MONITORING_LOGENTRY,
                MONITORING_NOTIFICATION,
                MONITORING_CONTACTNOTIFICATION,
                MONITORING_CONTACTNOTIFICATIONMETHOD
            ];
            foreach ($models as $model) {
                $this->loadModel($model);
            }

            $this->checkAndCreatePartitionsNdoUitls();
        }

        if ($this->DbBackend->isCrateDb()) {
            $models = [
                MONITORING_SERVICECHECK,
                MONITORING_HOSTCHECK,
                MONITORING_STATEHISTORY_HOST,
                MONITORING_STATEHISTORY_SERVICE,
                MONITORING_LOGENTRY,
                MONITORING_NOTIFICATION_HOST,
                MONITORING_NOTIFICATION_SERVICE
            ];
            foreach ($models as $model) {
                $this->loadModel($model);
            }

            $this->cleanupCrateDb();
        }
    }

    public function checkAndCreatePartitionsNdoUitls() {
        //return true;
        $db = $this->Cronjob->getDataSource();

        foreach ($this->modelsToCleanUp as $Model) {
            //Get existing partitions for this table out of MySQL's information_schema
            $result = $db->fetchAll("
			SELECT partition_name
			FROM information_schema.partitions
			WHERE TABLE_SCHEMA = '" . $db->config['database'] . "'
			AND TABLE_NAME = '" . $this->{$Model}->tablePrefix . $this->{$Model}->table . "';");
            $result = $this->__clearPartitionQueryResult($result);


            //Check if partition for current week exists
            $currentMysqlPartitionStartDate = date('Y-m-d H:i:s', strtotime('00:00:00 next monday'));
            $currentPartitionName = 'p_' . date('o_W');

            $this->out('Checking for partition ' . $currentPartitionName . ' in Model ' . $Model . '...', false);
            if (!in_array($currentPartitionName, $result)) {
                try {
                    $db->fetchAll("ALTER TABLE " . $db->config['database'] . "." . $this->{$Model}->tablePrefix . $this->{$Model}->table . " REORGANIZE PARTITION p_max INTO (PARTITION " . $currentPartitionName . " VALUES LESS THAN (TO_DAYS('" . $currentMysqlPartitionStartDate . "')), PARTITION p_max values LESS THAN (MAXVALUE));");
                } catch (Exception $e) {
                    $this->out('<red>   MySQL Error: ' . $e->getMessage() . '</red>');
                }
            } else {
                $this->out('<green>   Ok</green>');
            }


            //Attention: look close befor you change the date parameters!!!
            // Think that ther is comming a new year on 31.12 !!!
            //TO_DAYS('2014-12-29 00:00:00')
            $mysqlPartitionStartDate = date('Y-m-d H:i:s', strtotime('00:00:00 next monday +1 week'));
            //p_2015_01
            $newPartitionName = 'p_' . date('o_W', strtotime('next monday'));

            $this->out('Checking for partition ' . $newPartitionName . ' in Model ' . $Model . '...', false);

            //Checking if this partition already exists
            if (!in_array($newPartitionName, $result)) {
                //This partition does not exist and we need to create it
                $this->out('<blue> Partition does not exists, will create it.</blue>', false);
                $result = $db->fetchAll("ALTER TABLE " . $db->config['database'] . "." . $this->{$Model}->tablePrefix . $this->{$Model}->table . " REORGANIZE PARTITION p_max INTO (PARTITION " . $newPartitionName . " VALUES LESS THAN (TO_DAYS('" . $mysqlPartitionStartDate . "')), PARTITION p_max values LESS THAN (MAXVALUE));");
                $this->out('<green>   Ok</green>');
                //ALTER TABLE ptn_test DROP PARTITION p_2015_01
            } else {
                $this->out('<green>   Ok</green>');
            }
            $this->cleanupPartition($Model, $result);
            $this->hr();
        }
    }

    public function cleanupPartition($Model, $partitions = []) {
        $this->out('Checking age of partitions...');
        $maxAgeInWeeks = $this->_systemsettings['ARCHIVE']['ARCHIVE.AGE.' . strtoupper(Inflector::pluralize($Model))];
        $maxAgeTimestamp = strtotime('00:00:00 last monday -' . ($maxAgeInWeeks + 1) . ' week');

        //Valide date
        if ($maxAgeTimestamp) {
            $db = $this->Cronjob->getDataSource();
            //$partitions = ['p_2014_48', 'p_2014_49', 'p_2014_50', 'p_2014_51', 'p_2015_01', 'p_2015_02', 'p_2015_03'];
            $comparePartitionName = 'p_' . date('o_W', $maxAgeTimestamp);

            if (is_array($partitions)) {
                foreach ($partitions as $partition) {
                    //NEVER EVER DROP THIS PARTITION!!!
                    if ($partition == 'p_max') {
                        continue;
                    }
                    if (!version_compare($comparePartitionName, $partition, '<')) {
                        $this->out('<red>Drop partition ' . $partition . '</red>');
                        $result = $db->fetchAll("ALTER TABLE " . $db->config['database'] . "." . $this->{$Model}->tablePrefix . $this->{$Model}->table . " DROP PARTITION " . $partition . ";");
                    }
                }
            }

            $this->out('<green>Cleanup done</green>');
        }
    }

    protected function __clearPartitionQueryResult($result) {
        $r = [];
        foreach ($result as $row) {
            if (isset($row['partitions']['partition_name'])) {
                $r[] = $row['partitions']['partition_name'];
            }
        }

        return $r;
    }


    public function cleanupCrateDb() {
        $this->out('Checking age of partitions...');

        $this->deletePartitionsFromCrateDb($this->StatehistoryHost, 'STATEHISTORIES');
        $this->deletePartitionsFromCrateDb($this->StatehistoryService, 'STATEHISTORIES');
        $this->deletePartitionsFromCrateDb($this->Hostcheck, 'HOSTCHECKS');
        $this->deletePartitionsFromCrateDb($this->Servicecheck, 'SERVICECHECKS');
        $this->deletePartitionsFromCrateDb($this->Logentry, 'LOGENTRIES');
        $this->deletePartitionsFromCrateDb($this->NotificationHost, 'NOTIFICATIONS');
        $this->deletePartitionsFromCrateDb($this->NotificationService, 'NOTIFICATIONS');
        $this->deletePartitionsFromCrateDb($this->NotificationService, 'NOTIFICATIONS');

        $this->out('<green>Cleanup done</green>');
        $this->hr();
    }

    /**
     * @param Model $Model
     * @param string $systemsettingsKey
     */
    public function deletePartitionsFromCrateDb(Model $Model, $systemsettingsKey) {
        $maxAgeInWeeks = $this->_systemsettings['ARCHIVE']['ARCHIVE.AGE.' . $systemsettingsKey];

        $maxAgeTimestamp = strtotime('00:00:00 last monday -' . ($maxAgeInWeeks + 1) . ' week');
        $maxAgeTimestamp = $maxAgeTimestamp * 1000; //Java timestamp CrateDB


        $partitionsToDelete = [];
        foreach ($Model->getPartitions() as $record) {
            if (isset($record['values']['day']) && $record['values']['day'] < $maxAgeTimestamp) {
                $partitionsToDelete[] = $record['values']['day'];
            }
        }

        foreach ($partitionsToDelete as $partitionConditionTimestamp) {
            $this->out(sprintf(
                '<red>Drop partition %s from table %s%s</red>',
                $partitionConditionTimestamp,
                $Model->tablePrefix,
                $Model->useTable
            ));
            if (!$Model->dropPartition($partitionConditionTimestamp)) {
                $this->out('<error>Error while dropping partition</error>');
            }
        }
    }
}