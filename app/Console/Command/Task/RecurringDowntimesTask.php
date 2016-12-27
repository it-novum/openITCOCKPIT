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

class RecurringDowntimesTask extends AppShell
{
    public $uses = [
        'Systemsetting',
        'Systemdowntimes',
        'Host',
        'Hostgroup',
        'Service',
        MONITORING_EXTERNALCOMMAND,
    ];

    public $_systemsettings = [];

    function execute($quiet = false)
    {
        $this->params['quiet'] = $quiet;
        $this->stdout->styles('green', ['text' => 'green']);

        $this->_systemsettings = $this->Systemsetting->findAsArraySection('MONITORING');

        $this->out('Create recurring downtimes...', false);

        if (!file_exists($this->_systemsettings['MONITORING']['MONITORING.STATUS_DAT'])) {
            $this->out('<error>Error: File '.$this->_systemsettings['MONITORING']['MONITORING.STATUS_DAT'].' does not exists!</error>');
            $this->hr();

            return false;
        }

        $this->recurringDowntimes();
        $this->out('<green>   Ok</green>');
        $this->hr();
    }

    public function recurringDowntimes()
    {
        $all_downtimes = $this->Systemdowntimes->find('all');
        $future =
        $statusdat = $this->parseStatusDat();
        foreach ($all_downtimes as $downtime) {
            $weekdays = [];
            $days_of_month = [];

            $current_weekday = date('N');
            $current_day_of_month = date('j');

            if ($downtime['Systemdowntimes']['weekdays'] !== '' && $downtime['Systemdowntimes']['weekdays'] !== null) {
                $weekdays = explode(',', $downtime['Systemdowntimes']['weekdays']);
            }

            if ($downtime['Systemdowntimes']['day_of_month'] !== '' && $downtime['Systemdowntimes']['day_of_month'] !== null) {
                $days_of_month = explode(',', $downtime['Systemdowntimes']['day_of_month']);
            }

            if (!empty($weekdays) && !empty($days_of_month)) {
                if (in_array($current_weekday, $weekdays) && in_array($current_day_of_month, $days_of_month)) {
                    //Example: Today is the 5 day of month and this is a monday

                    //Checking if the downtime is allready set in nagios
                    if (!$this->checkStatusDatForDowntime($statusdat, $downtime['Systemdowntimes']['id'], $downtime['Systemdowntimes']['comment'])) {
                        switch ($downtime['Systemdowntimes']['objecttype_id']) {
                            case OBJECT_HOST:
                                $host = $this->Host->findById($downtime['Systemdowntimes']['object_id']);
                                if (empty($host)) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $this->Systemdowntimes->delete($downtime['Systemdowntimes']['id']);
                                    break;
                                }
                                $this->Externalcommand->setHostDowntime([
                                    'hostUuid'     => $host['Host']['uuid'],
                                    'downtimetype' => $downtime['Systemdowntimes']['downtimetype_id'],
                                    'comment'      => 'AUTO['.$downtime['Systemdowntimes']['id'].']: '.$downtime['Systemdowntimes']['comment'],
                                    'author'       => $downtime['Systemdowntimes']['author'],
                                    'start'        => strtotime($downtime['Systemdowntimes']['from_time']),
                                    'end'          => strtotime($downtime['Systemdowntimes']['to_time']),
                                ]);
                                break;

                            case OBJECT_HOSTGROUP:
                                $hostgroup = $this->Hostgroup->findById($downtime['Systemdowntimes']['object_id']);
                                if (empty($hostgroup)) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $this->Systemdowntimes->delete($downtime['Systemdowntimes']['id']);
                                    break;
                                }
                                $this->Externalcommand->setHostgroupDowntime([
                                    'hostgroupUuid' => $hostgroup['Hostgroup']['uuid'],
                                    'downtimetype'  => $downtime['Systemdowntimes']['downtimetype_id'],
                                    'comment'       => 'AUTO['.$downtime['Systemdowntimes']['id'].']: '.$downtime['Systemdowntimes']['comment'],
                                    'author'        => $downtime['Systemdowntimes']['author'],
                                    'start'         => strtotime($downtime['Systemdowntimes']['from_time']),
                                    'end'           => strtotime($downtime['Systemdowntimes']['to_time']),
                                ]);
                                break;

                            case OBJECT_SERVICE:
                                $service = $this->Service->findById($downtime['Systemdowntimes']['object_id']);
                                if (empty($service)) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $this->Systemdowntimes->delete($downtime['Systemdowntimes']['id']);
                                    break;
                                }
                                $this->Externalcommand->setServiceDowntime([
                                    'hostUuid'    => $service['Host']['uuid'],
                                    'serviceUuid' => $service['Service']['uuid'],
                                    'start'       => strtotime($downtime['Systemdowntimes']['from_time']),
                                    'end'         => strtotime($downtime['Systemdowntimes']['to_time']),
                                    'author'      => $downtime['Systemdowntimes']['author'],
                                    'comment'     => 'AUTO['.$downtime['Systemdowntimes']['id'].']: '.$downtime['Systemdowntimes']['comment'],
                                ]);
                                break;
                        }
                    }
                    continue;
                }
            }

            if (!empty($weekdays)) {
                if (in_array($current_weekday, $weekdays)) {
                    //Example: today is monday
                    //Checking if the downtime is allready set in nagios
                    if (!$this->checkStatusDatForDowntime($statusdat, $downtime['Systemdowntimes']['id'], $downtime['Systemdowntimes']['comment'])) {
                        switch ($downtime['Systemdowntimes']['objecttype_id']) {
                            case OBJECT_HOST:
                                $host = $this->Host->findById($downtime['Systemdowntimes']['object_id']);
                                if (empty($host)) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $this->Systemdowntimes->delete($downtime['Systemdowntimes']['id']);
                                    break;
                                }
                                $this->Externalcommand->setHostDowntime([
                                    'hostUuid'     => $host['Host']['uuid'],
                                    'downtimetype' => $downtime['Systemdowntimes']['downtimetype_id'],
                                    'comment'      => 'AUTO['.$downtime['Systemdowntimes']['id'].']: '.$downtime['Systemdowntimes']['comment'],
                                    'author'       => $downtime['Systemdowntimes']['author'],
                                    'start'        => strtotime($downtime['Systemdowntimes']['from_time']),
                                    'end'          => strtotime($downtime['Systemdowntimes']['to_time']),
                                ]);
                                break;

                            case OBJECT_HOSTGROUP:
                                $hostgroup = $this->Hostgroup->findById($downtime['Systemdowntimes']['object_id']);
                                if (empty($hostgroup)) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $this->Systemdowntimes->delete($downtime['Systemdowntimes']['id']);
                                    break;
                                }
                                $this->Externalcommand->setHostgroupDowntime([
                                    'hostgroupUuid' => $hostgroup['Hostgroup']['uuid'],
                                    'downtimetype'  => $downtime['Systemdowntimes']['downtimetype_id'],
                                    'comment'       => 'AUTO['.$downtime['Systemdowntimes']['id'].']: '.$downtime['Systemdowntimes']['comment'],
                                    'author'        => $downtime['Systemdowntimes']['author'],
                                    'start'         => strtotime($downtime['Systemdowntimes']['from_time']),
                                    'end'           => strtotime($downtime['Systemdowntimes']['to_time']),
                                ]);
                                break;

                            case OBJECT_SERVICE:
                                $service = $this->Service->findById($downtime['Systemdowntimes']['object_id']);
                                if (empty($service)) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $this->Systemdowntimes->delete($downtime['Systemdowntimes']['id']);
                                    break;
                                }
                                $this->Externalcommand->setServiceDowntime([
                                    'hostUuid'    => $service['Host']['uuid'],
                                    'serviceUuid' => $service['Service']['uuid'],
                                    'start'       => strtotime($downtime['Systemdowntimes']['from_time']),
                                    'end'         => strtotime($downtime['Systemdowntimes']['to_time']),
                                    'author'      => $downtime['Systemdowntimes']['author'],
                                    'comment'     => 'AUTO['.$downtime['Systemdowntimes']['id'].']: '.$downtime['Systemdowntimes']['comment'],
                                ]);
                                break;
                        }
                    }
                    continue;
                }
            }

            if (!empty($days_of_month)) {
                if (in_array($current_day_of_month, $days_of_month)) {
                    //Example: today the 6 or 10 or 30 day of the current month
                    //Checking if the downtime is allready set in nagios
                    if (!$this->checkStatusDatForDowntime($statusdat, $downtime['Systemdowntimes']['id'], $downtime['Systemdowntimes']['comment'])) {
                        switch ($downtime['Systemdowntimes']['objecttype_id']) {
                            case OBJECT_HOST:
                                $host = $this->Host->findById($downtime['Systemdowntimes']['object_id']);
                                if (empty($host)) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $this->Systemdowntimes->delete($downtime['Systemdowntimes']['id']);
                                    break;
                                }
                                $this->Externalcommand->setHostDowntime([
                                    'hostUuid'     => $host['Host']['uuid'],
                                    'downtimetype' => $downtime['Systemdowntimes']['downtimetype_id'],
                                    'comment'      => 'AUTO['.$downtime['Systemdowntimes']['id'].']: '.$downtime['Systemdowntimes']['comment'],
                                    'author'       => $downtime['Systemdowntimes']['author'],
                                    'start'        => strtotime($downtime['Systemdowntimes']['from_time']),
                                    'end'          => strtotime($downtime['Systemdowntimes']['to_time']),
                                ]);
                                break;

                            case OBJECT_HOSTGROUP:
                                $hostgroup = $this->Hostgroup->findById($downtime['Systemdowntimes']['object_id']);
                                if (empty($hostgroup)) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $this->Systemdowntimes->delete($downtime['Systemdowntimes']['id']);
                                    break;
                                }
                                $this->Externalcommand->setHostgroupDowntime([
                                    'hostgroupUuid' => $hostgroup['Hostgroup']['uuid'],
                                    'downtimetype'  => $downtime['Systemdowntimes']['downtimetype_id'],
                                    'comment'       => 'AUTO['.$downtime['Systemdowntimes']['id'].']: '.$downtime['Systemdowntimes']['comment'],
                                    'author'        => $downtime['Systemdowntimes']['author'],
                                    'start'         => strtotime($downtime['Systemdowntimes']['from_time']),
                                    'end'           => strtotime($downtime['Systemdowntimes']['to_time']),
                                ]);
                                break;

                            case OBJECT_SERVICE:
                                $service = $this->Service->findById($downtime['Systemdowntimes']['object_id']);
                                if (empty($service)) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $this->Systemdowntimes->delete($downtime['Systemdowntimes']['id']);
                                    break;
                                }
                                $this->Externalcommand->setServiceDowntime([
                                    'hostUuid'    => $service['Host']['uuid'],
                                    'serviceUuid' => $service['Service']['uuid'],
                                    'start'       => strtotime($downtime['Systemdowntimes']['from_time']),
                                    'end'         => strtotime($downtime['Systemdowntimes']['to_time']),
                                    'author'      => $downtime['Systemdowntimes']['author'],
                                    'comment'     => 'AUTO['.$downtime['Systemdowntimes']['id'].']: '.$downtime['Systemdowntimes']['comment'],
                                ]);
                                break;
                        }
                    }
                    continue;
                }
            }

        }
    }

    public function checkStatusDatForDowntime($statusdat, $downtime_id, $comment)
    {
        foreach ($statusdat as $record) {
            if ($record['comment'] == 'AUTO['.$downtime_id.']: '.$comment) {
                return true;
            }
        }

        return false;
    }

    public function parseStatusDat()
    {
        $this->monitoringLog = $this->_systemsettings['MONITORING']['MONITORING.STATUS_DAT'];
        $return = [];
        $saveContent = false;
        $statusdat = fopen($this->monitoringLog, "r");
        while (!feof($statusdat)) {
            $line = trim(fgets($statusdat));
            if ($line == "hostdowntime {" || $line == "servicedowntime {") {
                $saveContent = true;
                $section = [];
                continue;
            }

            if ($line == "}" && $saveContent === true) {
                $saveContent = false;
                $return[] = $section;
            }

            if ($saveContent) {
                $tmp = explode('=', $line);
                $section[$tmp[0]] = $tmp[1];
                unset($tmp);
            }
        }

        return $return;

    }
}