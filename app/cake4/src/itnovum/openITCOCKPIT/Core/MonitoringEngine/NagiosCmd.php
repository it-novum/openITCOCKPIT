<?php
// Copyright (C) <2018>  <it-novum GmbH>
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

namespace itnovum\openITCOCKPIT\Core\MonitoringEngine;


use App\Model\Table\HostgroupsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\ModuleManager;

class NagiosCmd {

    /**
     * @var string
     */
    private $path = '/opt/openitc/nagios/var/rw/nagios.cmd';

    /**
     * NagiosCmd constructor.
     * @param string|null $path
     */
    public function __construct($path = null) {
        if ($path !== null) {
            $this->path = $path;
        }
    }

    /**
     * @return string
     */
    private function getCommandPrefix() {
        return '[' . time() . '] ';
    }

    /**
     * @param string $externalCommandStr
     * @param int $satelliteId
     * @return bool
     */
    private function toCmd($externalCommandStr, $satelliteId = 0) {
        if ($satelliteId > 0) {
            $ModuleManager = new ModuleManager('DistributeModule');
            if (!$ModuleManager->moduleExists()) {
                return false;
            }
            $Satellite = $ModuleManager->loadModel('Satellite');

            $result = $Satellite->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Satellite.id' => $satelliteId
                ],
                'fields'     => [
                    'Satellite.id',
                    'Satellite.name'
                ]
            ]);

            if (isset($result['Satellite']['name'])) {
                $file = fopen('/opt/openitc/nagios/var/rw/' . md5($result['Satellite']['name']) . '_nagios.cmd', 'a+');
                fwrite($file, $this->getCommandPrefix() . $externalCommandStr . PHP_EOL);
                fclose($file);
            }
            unset($result);
            return true;
        }

        //Host or service from master system or command for master nagios.cmd
        if (file_exists($this->path)) {
            //Do NOT create nagios.cmd as text file
            $fd = fopen($this->path, 'w+');
            fwrite($fd, $this->getCommandPrefix() . $externalCommandStr . PHP_EOL);
            fclose($fd);
            return true;
        }
        return false;
    }

    /**
     * @param string $hostUuid UUID of the host
     * @param int $starttime Start time as unix timestamp
     * @param int $endtime End time as unix timestamp
     * @param string $author Name of the downtime author
     * @param string $comment Comment data
     * @param int $downtimetype 0 => Host only 1 => Host inc. services
     * @return bool
     */
    public function scheduleHostDowntime($hostUuid, $starttime, $endtime, $author, $comment, $downtimetype = 0) {
        $duration = $endtime - $starttime;

        if ($downtimetype === 0) {
            //Host only
            $cmd = sprintf(
                'SCHEDULE_HOST_DOWNTIME;%s;%s;%s;1;0;%s;%s;%s',
                $hostUuid,
                $starttime,
                $endtime,
                $duration,
                $author,
                $comment
            );
            return $this->toCmd($cmd);
        }

        //Host inc services
        $cmd = sprintf(
            'SCHEDULE_HOST_DOWNTIME;%s;%s;%s;1;0;%s;%s;%s',
            $hostUuid,
            $starttime,
            $endtime,
            $duration,
            $author,
            $comment
        );
        $this->toCmd($cmd);

        $cmd = sprintf(
            'SCHEDULE_HOST_SVC_DOWNTIME;%s;%s;%s;1;0;%s;%s;%s',
            $hostUuid,
            $starttime,
            $endtime,
            $duration,
            $author,
            $comment
        );
        return $this->toCmd($cmd);
    }

    /**
     * @param string $hostgroupUuid UUID of the host group
     * @param int $starttime Start time as unix timestamp
     * @param int $endtime End time as unix timestamp
     * @param string $author Name of the downtime author
     * @param string $comment Comment data
     * @param int $downtimetype 0 => Host only, 1 => Host inc. services
     */
    public function scheduleHostgroupDowntime($hostgroupUuid, $starttime, $endtime, $author, $comment, $downtimetype = 0) {
        //External commands SCHEDULE_HOSTGROUP_HOST_DOWNTIME and SCHEDULE_HOSTGROUP_SVC_DOWNTIME are broke in Nagios since 2007...

        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        $hostgroup = $HostgroupsTable->getHostsByHostgroupUuidForExternalcommandsIncludeingHosttemplateHosts($hostgroupUuid);

        if (isset($hostgroup['hosts'])) {
            foreach ($hostgroup['hosts'] as $host) {
                $this->scheduleHostDowntime(
                    $host['uuid'],
                    $starttime,
                    $endtime,
                    $author,
                    $comment,
                    $downtimetype
                );
            }
        }
    }

    /**
     * @param string $hostUuid UUID of the host
     * @param string $serviceUuid UUID of the service
     * @param int $starttime Start time as unix timestamp
     * @param int $endtime End time as unix timestamp
     * @param string $author Name of the downtime author
     * @param string $comment Comment data
     * @return bool
     */
    public function scheduleServiceDowntime($hostUuid, $serviceUuid, $starttime, $endtime, $author, $comment) {
        $duration = $endtime - $starttime;

        $cmd = sprintf(
            'SCHEDULE_SVC_DOWNTIME;%s;%s;%s;%s;1;0;%s;%s;%s',
            $hostUuid,
            $serviceUuid,
            $starttime,
            $endtime,
            $duration,
            $author,
            $comment
        );
        return $this->toCmd($cmd);
    }

    /**
     * @param array $hostUuids UUIDs of the hosts
     * @param int $starttime Start time as unix timestamp
     * @param int $endtime End time as unix timestamp
     * @param string $author Name of the downtime author
     * @param string $comment Comment data
     * @param int $downtimetype 0 => Host only, 1 => Host inc. services
     * @return bool
     */
    public function scheduleContainerDowntime($hostUuids, $starttime, $endtime, $author, $comment, $downtimetype = 0) {
        if (!is_array($hostUuids)) {
            $hostUuids = [$hostUuids];
        }

        foreach ($hostUuids as $hostUuid) {
            $this->scheduleHostDowntime(
                $hostUuid,
                $starttime,
                $endtime,
                $author,
                $comment,
                $downtimetype
            );
        }
    }


}