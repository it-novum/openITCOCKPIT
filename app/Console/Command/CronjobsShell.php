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

class CronjobsShell extends AppShell {
    public $uses = [
        'Cronjob',
        'Cronschedule',
    ];

    public function main() {
        $fp = fopen('/var/run/oitc_cronjob.lock', 'wb');
        if (!$fp || !flock($fp, LOCK_EX | LOCK_NB)) {
            $this->out('openITCOCKPIT cronjob already running!');
            fclose($fp);
            exit(1);
        }
        //Configure::load('nagios');
        $this->parser = $this->getOptionParser();
        $this->force = false;

        try {
            $this->cronjobs = $this->Cronjob->find('all', [
                'conditions' => [
                    'Cronjob.enabled' => 1
                ]
            ]);
        } catch (Exception $e) {
            debug($e->getMessage());
            exit(0);
        }

        if (array_key_exists('force', $this->params)) {
            $this->force = true;
        }

        $this->quiet = false;
        if (isset($this->params['quiet']) && $this->params['quiet'] == true) {
            $this->quiet = true;
        }

        $this->cronjobsToExecute = [];
        foreach ($this->cronjobs as $cronjob) {
            if (
                $cronjob['Cronschedule']['start_time'] == null ||
                (time() >= (strtotime($cronjob['Cronschedule']['start_time']) + $this->m2s($cronjob['Cronjob']['interval'])) && $cronjob['Cronschedule']['is_running'] == 0) ||
                $this->force === true
            ) {
                $this->scheduleCronjob($cronjob);
            }

        }
        fclose($fp);
    }

    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'force' => ['short' => 'f', 'help' => __d('oitc_console', 'All cronjobs will be forced to execute!')],
        ]);

        return $parser;
    }

    public function scheduleCronjob($cronjob) {

        //Flag the cronjob as is_running in DB and set start_time
        $cronjob['Cronschedule']['start_time'] = date('Y-m-d H:i:s');
        $cronjob['Cronschedule']['is_running'] = 1;
        if ($cronjob['Cronschedule']['id'] == null || $cronjob['Cronschedule']['id'] == '') {
            //The cron was never scheduled or the databases was truncated
            $cronjob['Cronschedule']['end_time'] = date('Y-m-d H:i:s');
            if (!$this->Cronjob->saveAll($cronjob)) {
                //Error in save
                return false;
            }

            // We saved new data and need to select this now again (because of DB truncate or cron never runs or what ever)
            $cronjob = $this->Cronjob->find('first', [
                'conditions' => [
                    'Cronjob.id' => $cronjob['Cronjob']['id'],
                ],
            ]);
        }

        //Executing the cron
        if ($cronjob['Cronjob']['plugin'] == 'Core') {
            //This is Core cronjob, so we load the Task and lets rock
            $_task = new TaskCollection($this);
            $extTask = $_task->load($cronjob['Cronjob']['task']);
            $extTask->execute($this->quiet);
        } else {
            try {
                $_task = new TaskCollection($this);
                $extTask = $_task->load($cronjob['Cronjob']['plugin'] . '.' . $cronjob['Cronjob']['task']);
                $extTask->execute($this->quiet);
            } catch (Exception $e) {
                debug($e);
            }
        }

        //Cronjob is done, set is_running back to 0 and the end_time
        $cronjob['Cronschedule']['end_time'] = date('Y-m-d H:i:s');
        $cronjob['Cronschedule']['is_running'] = 0;
        if ($this->Cronjob->saveAll($cronjob)) {
            //Cronjob done
            return true;
        }

        //Error on execution of the cron
        return false;

    }

    public function m2s($minutes) {
        return $minutes * 60;
    }
}
