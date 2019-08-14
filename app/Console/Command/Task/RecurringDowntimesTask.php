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

use App\Model\Table\ContainersTable;
use App\Model\Table\HostgroupsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\SystemdowntimesTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;
use itnovum\openITCOCKPIT\Core\MonitoringEngine\NagiosCmd;
use itnovum\openITCOCKPIT\Core\MonitoringEngine\StatusDat;

/**
 * Class RecurringDowntimesTask
 */
class RecurringDowntimesTask extends AppShell implements CronjobInterface {

    /**
     * @var StatusDat
     */
    private $StatusDat;

    function execute($quiet = false) {
        $this->params['quiet'] = $quiet;
        $this->stdout->styles('green', ['text' => 'green']);

        /** @var $SystemsettingsTable App\Model\Table\SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');

        $record = $SystemsettingsTable->getSystemsettingByKey('MONITORING.STATUS_DAT');
        $statusdatPath = $record->get('value');

        try {
            $this->StatusDat = new StatusDat($statusdatPath);
            $this->StatusDat->parseDowntimes();
        } catch (\RuntimeException $e) {
            $this->out('<error>' . $e->getMessage() . '</error>');
            $this->hr();

            return false;
        }

        $this->out('Create recurring downtimes...', false);

        $this->createMissingRecurringDowntimes();
        $this->out('<green>   Ok</green>');
        $this->hr();
    }

    public function createMissingRecurringDowntimes() {
        /** @var $SystemdowntimesTable SystemdowntimesTable */
        $SystemdowntimesTable = TableRegistry::getTableLocator()->get('Systemdowntimes');
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');

        $systemdowntimes = $SystemdowntimesTable->getRecurringDowntimesForCronjob();

        $NagiosCmd = new NagiosCmd();

        foreach ($systemdowntimes as $systemdowntime) {
            /** @var \App\Model\Entity\Systemdowntime $systemdowntime */
            $currentWeekday = date('N');
            $currentdayOfMonth = date('j');

            if ($systemdowntime->hasWeekdays() === true && $systemdowntime->hasDayOfMonth() === true) {
                if (in_array($currentWeekday, $systemdowntime->getWeekdays(), true) && in_array($currentdayOfMonth, $systemdowntime->getDayOfMonth(), true)) {
                    //Example: Today is the 5 day of month and this is a monday
                    if ($systemdowntime->isTimestampInThePast($systemdowntime->getScheduledEndTime()) === true) {
                        //Skip downtime if scheduled end time is in the past
                        continue;
                    }
                    //Checking if the downtime is already set in Nagios/Naemon
                    if ($this->StatusDat->checkIfRecurringDowntimeWasScheduled($systemdowntime->get('id'), $systemdowntime->get('comment')) === false) {
                        switch ((int)$systemdowntime->get('objecttype_id')) {
                            case OBJECT_HOST:
                                try {
                                    $hostUuid = $HostsTable->getHostUuidById($systemdowntime->get('object_id'));
                                    $NagiosCmd->scheduleHostDowntime(
                                        $hostUuid,
                                        $systemdowntime->getScheduledStartTime(),
                                        $systemdowntime->getScheduledEndTime(),
                                        $systemdowntime->get('author'),
                                        $systemdowntime->getRecurringDowntimeComment(),
                                        $systemdowntime->getDowntimetypeId()
                                    );
                                } catch (RecordNotFoundException $e) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $SystemdowntimesTable->delete($systemdowntime);
                                }
                                break;

                            case OBJECT_HOSTGROUP:
                                try {
                                    $hostgroupUuid = $HostgroupsTable->getHostgroupUuidById($systemdowntime->get('object_id'));
                                    $NagiosCmd->scheduleHostgroupDowntime(
                                        $hostgroupUuid,
                                        $systemdowntime->getScheduledStartTime(),
                                        $systemdowntime->getScheduledEndTime(),
                                        $systemdowntime->get('author'),
                                        $systemdowntime->getRecurringDowntimeComment(),
                                        $systemdowntime->getDowntimetypeId()
                                    );
                                } catch (RecordNotFoundException $e) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $SystemdowntimesTable->delete($systemdowntime);
                                }

                                break;

                            case OBJECT_SERVICE:
                                try {
                                    $service = $ServicesTable->getServiceByIdForExternalCommand($systemdowntime->get('object_id'));
                                    $NagiosCmd->scheduleServiceDowntime(
                                        $service->get('host')->get('uuid'),
                                        $service->get('uuid'),
                                        $systemdowntime->getScheduledStartTime(),
                                        $systemdowntime->getScheduledEndTime(),
                                        $systemdowntime->get('author'),
                                        $systemdowntime->getRecurringDowntimeComment()
                                    );
                                } catch (RecordNotFoundException $e) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $SystemdowntimesTable->delete($systemdowntime);
                                }
                                break;

                            case OBJECT_NODE: //Node / Container downtimes
                                if (!$ContainersTable->existsById($systemdowntime->get('object_id'))) {
                                    $SystemdowntimesTable->delete($systemdowntime);
                                    break;
                                }

                                $childrenContainerId = [
                                    $systemdowntime->get('object_id')
                                ];
                                if ($systemdowntime->get('is_recursive') == 1) {
                                    //Recursive container lookup is enabled
                                    //Lookup all child containers the user has write permissions to to select the hosts and create
                                    //the downtimes

                                    if ($systemdowntime->get('object_id') == ROOT_CONTAINER) {
                                        $childrenContainers = $ContainersTable->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
                                    } else {
                                        $childrenContainers = $ContainersTable->resolveChildrenOfContainerIds($systemdowntime->get('object_id'));
                                        $childrenContainers = $ContainersTable->removeRootContainer($childrenContainers);
                                    }
                                }


                                $hosts = $HostsTable->getHostsByContainerId(array_values($childrenContainerId), 'list', 'uuid');
                                $hostUuids = array_keys($hosts);

                                $NagiosCmd->scheduleContainerDowntime(
                                    $hostUuids,
                                    $systemdowntime->getScheduledStartTime(),
                                    $systemdowntime->getScheduledEndTime(),
                                    $systemdowntime->get('author'),
                                    $systemdowntime->getRecurringDowntimeComment(),
                                    $systemdowntime->getDowntimetypeId()
                                );
                                break;
                        }
                    }
                }
            }

            if ($systemdowntime->hasWeekdays() === true && $systemdowntime->hasDayOfMonth() === false) {
                if (in_array($currentWeekday, $systemdowntime->getWeekdays(), true)) {
                    //Example: today is monday
                    //Checking if the downtime is already set in Nagios/Naemon and make sure scheduled end time is not in the past
                    if ($systemdowntime->isTimestampInThePast($systemdowntime->getScheduledEndTime()) === true) {
                        //Skip downtime if scheduled end time is in the past
                        continue;
                    }

                    if ($this->StatusDat->checkIfRecurringDowntimeWasScheduled($systemdowntime->get('id'), $systemdowntime->get('comment')) === false) {
                        switch ((int)$systemdowntime->get('objecttype_id')) {
                            case OBJECT_HOST:
                                try {
                                    $hostUuid = $HostsTable->getHostUuidById($systemdowntime->get('object_id'));
                                    $NagiosCmd->scheduleHostDowntime(
                                        $hostUuid,
                                        $systemdowntime->getScheduledStartTime(),
                                        $systemdowntime->getScheduledEndTime(),
                                        $systemdowntime->get('author'),
                                        $systemdowntime->getRecurringDowntimeComment(),
                                        $systemdowntime->getDowntimetypeId()
                                    );
                                } catch (RecordNotFoundException $e) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $SystemdowntimesTable->delete($systemdowntime);
                                }
                                break;

                            case OBJECT_HOSTGROUP:
                                try {
                                    $hostgroupUuid = $HostgroupsTable->getHostgroupUuidById($systemdowntime->get('object_id'));
                                    $NagiosCmd->scheduleHostgroupDowntime(
                                        $hostgroupUuid,
                                        $systemdowntime->getScheduledStartTime(),
                                        $systemdowntime->getScheduledEndTime(),
                                        $systemdowntime->get('author'),
                                        $systemdowntime->getRecurringDowntimeComment(),
                                        $systemdowntime->getDowntimetypeId()
                                    );
                                } catch (RecordNotFoundException $e) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $SystemdowntimesTable->delete($systemdowntime);
                                }

                                break;

                            case OBJECT_SERVICE:
                                try {
                                    $service = $ServicesTable->getServiceByIdForExternalCommand($systemdowntime->get('object_id'));
                                    $NagiosCmd->scheduleServiceDowntime(
                                        $service->get('host')->get('uuid'),
                                        $service->get('uuid'),
                                        $systemdowntime->getScheduledStartTime(),
                                        $systemdowntime->getScheduledEndTime(),
                                        $systemdowntime->get('author'),
                                        $systemdowntime->getRecurringDowntimeComment()
                                    );
                                } catch (RecordNotFoundException $e) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $SystemdowntimesTable->delete($systemdowntime);
                                }
                                break;

                            case OBJECT_NODE: //Node / Container downtimes
                                if (!$ContainersTable->existsById($systemdowntime->get('object_id'))) {
                                    $SystemdowntimesTable->delete($systemdowntime);
                                    break;
                                }

                                $childrenContainerId = [
                                    $systemdowntime->get('object_id')
                                ];
                                if ($systemdowntime->get('is_recursive') == 1) {
                                    //Recursive container lookup is enabled
                                    //Lookup all child containers the user has write permissions to to select the hosts and create
                                    //the downtimes

                                    if ($systemdowntime->get('object_id') == ROOT_CONTAINER) {
                                        $childrenContainers = $ContainersTable->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
                                    } else {
                                        $childrenContainers = $ContainersTable->resolveChildrenOfContainerIds($systemdowntime->get('object_id'));
                                        $childrenContainers = $ContainersTable->removeRootContainer($childrenContainers);
                                    }
                                }


                                $hosts = $HostsTable->getHostsByContainerId(array_values($childrenContainerId), 'list', 'uuid');
                                $hostUuids = array_keys($hosts);

                                $NagiosCmd->scheduleContainerDowntime(
                                    $hostUuids,
                                    $systemdowntime->getScheduledStartTime(),
                                    $systemdowntime->getScheduledEndTime(),
                                    $systemdowntime->get('author'),
                                    $systemdowntime->getRecurringDowntimeComment(),
                                    $systemdowntime->getDowntimetypeId()
                                );
                                break;
                        }
                    }
                }
            }

            if ($systemdowntime->hasDayOfMonth() === true && $systemdowntime->hasWeekdays() === false) {
                if (in_array($currentdayOfMonth, $systemdowntime->getDayOfMonth(), true)) {
                    //Example: today the 6 or 10 or 30 day of the current month

                    //Checking if the downtime is already set in Nagios/Naemon and make sure scheduled end time is not in the past
                    if ($systemdowntime->isTimestampInThePast($systemdowntime->getScheduledEndTime()) === true) {
                        //Skip downtime if scheduled end time is in the past
                        continue;
                    }

                    //Checking if the downtime is allready set in nagios
                    if ($this->StatusDat->checkIfRecurringDowntimeWasScheduled($systemdowntime->get('id'), $systemdowntime->get('comment')) === false) {
                        switch ((int)$systemdowntime->get('objecttype_id')) {
                            case OBJECT_HOST:
                                try {
                                    $hostUuid = $HostsTable->getHostUuidById($systemdowntime->get('object_id'));
                                    $NagiosCmd->scheduleHostDowntime(
                                        $hostUuid,
                                        $systemdowntime->getScheduledStartTime(),
                                        $systemdowntime->getScheduledEndTime(),
                                        $systemdowntime->get('author'),
                                        $systemdowntime->getRecurringDowntimeComment(),
                                        $systemdowntime->getDowntimetypeId()
                                    );
                                } catch (RecordNotFoundException $e) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $SystemdowntimesTable->delete($systemdowntime);
                                }
                                break;

                            case OBJECT_HOSTGROUP:
                                try {
                                    $hostgroupUuid = $HostgroupsTable->getHostgroupUuidById($systemdowntime->get('object_id'));
                                    $NagiosCmd->scheduleHostgroupDowntime(
                                        $hostgroupUuid,
                                        $systemdowntime->getScheduledStartTime(),
                                        $systemdowntime->getScheduledEndTime(),
                                        $systemdowntime->get('author'),
                                        $systemdowntime->getRecurringDowntimeComment(),
                                        $systemdowntime->getDowntimetypeId()
                                    );
                                } catch (RecordNotFoundException $e) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $SystemdowntimesTable->delete($systemdowntime);
                                }

                                break;

                            case OBJECT_SERVICE:
                                try {
                                    $service = $ServicesTable->getServiceByIdForExternalCommand($systemdowntime->get('object_id'));
                                    $NagiosCmd->scheduleServiceDowntime(
                                        $service->get('host')->get('uuid'),
                                        $service->get('uuid'),
                                        $systemdowntime->getScheduledStartTime(),
                                        $systemdowntime->getScheduledEndTime(),
                                        $systemdowntime->get('author'),
                                        $systemdowntime->getRecurringDowntimeComment()
                                    );
                                } catch (RecordNotFoundException $e) {
                                    // The object for recurring downtime was deleted, so we delete the downtime
                                    $SystemdowntimesTable->delete($systemdowntime);
                                }
                                break;

                            case OBJECT_NODE: //Node / Container downtimes
                                if (!$ContainersTable->existsById($systemdowntime->get('object_id'))) {
                                    $SystemdowntimesTable->delete($systemdowntime);
                                    break;
                                }

                                $childrenContainerId = [
                                    $systemdowntime->get('object_id')
                                ];
                                if ($systemdowntime->get('is_recursive') == 1) {
                                    //Recursive container lookup is enabled
                                    //Lookup all child containers the user has write permissions to to select the hosts and create
                                    //the downtimes

                                    if ($systemdowntime->get('object_id') == ROOT_CONTAINER) {
                                        $childrenContainers = $ContainersTable->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
                                    } else {
                                        $childrenContainers = $ContainersTable->resolveChildrenOfContainerIds($systemdowntime->get('object_id'));
                                        $childrenContainers = $ContainersTable->removeRootContainer($childrenContainers);
                                    }
                                }


                                $hosts = $HostsTable->getHostsByContainerId(array_values($childrenContainerId), 'list', 'uuid');
                                $hostUuids = array_keys($hosts);

                                $NagiosCmd->scheduleContainerDowntime(
                                    $hostUuids,
                                    $systemdowntime->getScheduledStartTime(),
                                    $systemdowntime->getScheduledEndTime(),
                                    $systemdowntime->get('author'),
                                    $systemdowntime->getRecurringDowntimeComment(),
                                    $systemdowntime->getDowntimetypeId()
                                );
                                break;
                        }
                    }
                }
            }

        }
    }
}
