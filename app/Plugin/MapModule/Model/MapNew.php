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

use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\ServicestatusConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use Statusengine\PerfdataParser;

class MapNew extends MapModuleAppModel {

    public $useTable = false;

    private $hostIcons = [
        0 => 'up.png',
        1 => 'down.png',
        2 => 'unreachable.png'
    ];
    private $serviceIcons = [
        0 => 'up.png',
        1 => 'warning.png',
        2 => 'critical.png',
        3 => 'unknown.png'
    ];
    private $ackIcon = 'ack.png';
    private $downtimeIcon = 'downtime.png';
    private $ackAndDowntimeIcon = 'downtime_ack.png';

    private $errorIcon = 'error.png';

    /**
     * @param Model $Service
     * @param Model $Hoststatus
     * @param Model $Servicestatus
     * @param $host
     * @return array
     */
    public function getHostItemImage(Model $Service, Model $Hoststatus, Model $Servicestatus, $host) {
        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
        $hoststatus = $Hoststatus->byUuid($host['Host']['uuid'], $HoststatusFields);
        if (empty($hoststatus)) {
            return [
                'icon' => $this->errorIcon,
                'color' => 'text-primary',
                'background' => 'bg-color-blueLight'
            ];
        }

        $hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($hoststatus['Hoststatus']);
        $icon = $this->hostIcons[$hoststatus->currentState()];
        $color = $hoststatus->HostStatusColor();
        $background = $hoststatus->HostStatusBackgroundColor();

        if ($hoststatus->isAcknowledged()) {
            $icon = $this->ackIcon;
        }

        if ($hoststatus->isInDowntime()) {
            $icon = $this->downtimeIcon;
        }

        if ($hoststatus->isAcknowledged() && $hoststatus->isInDowntime()) {
            $icon = $this->ackAndDowntimeIcon;
        }

        if ($hoststatus->currentState() > 0) {
            return [
                'icon' => $icon,
                'color' => $color,
                'background' => $background
            ];
        }

        //Check services for cumulated state (only if host is up)
        $services = $Service->find('list', [
            'recursive' => -1,
            'fields' => [
                'Service.uuid'
            ],
            'conditions' => [
                'Service.host_id' => $host['Host']['id'],
                'Service.disabled' => 0
            ]
        ]);

        $ServicestatusFieds = new ServicestatusFields($this->DbBackend);
        $ServicestatusFieds->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
        $ServicestatusConditions = new ServicestatusConditions($this->DbBackend);
        $ServicestatusConditions->servicesWarningCriticalAndUnknown();
        $servicestatus = $Servicestatus->byUuid($services, $ServicestatusFieds, $ServicestatusConditions);

        if (!empty($servicestatus)) {
            $worstServiceState = array_values(
                Hash::sort($servicestatus, '{s}.Servicestatus.current_state', 'desc')
            );

            $servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($worstServiceState[0]['Servicestatus']);
            $serviceIcon = $this->serviceIcons[$servicestatus->currentState()];

            if ($servicestatus->isAcknowledged()) {
                $serviceIcon = $this->ackIcon;
            }

            if ($servicestatus->isInDowntime()) {
                $serviceIcon = $this->downtimeIcon;
            }

            if ($servicestatus->isAcknowledged() && $servicestatus->isInDowntime()) {
                $serviceIcon = $this->ackAndDowntimeIcon;
            }
            return [
                'icon' => $serviceIcon,
                'color' => $servicestatus->ServiceStatusColor(),
                'background' => $servicestatus->ServiceStatusBackgroundColor()
            ];
        }


        return [
            'icon' => $icon,
            'color' => $color,
            'background' => $background
        ];
    }

    /**
     * @param Model $Servicestatus
     * @param $service
     * @return array
     */
    public function getServiceItemImage(Model $Servicestatus, $service) {
        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged()->perfdata();
        $servicestatus = $Servicestatus->byUuid($service['Service']['uuid'], $ServicestatusFields);
        if (empty($servicestatus)) {
            return [
                'icon' => $this->errorIcon,
                'color' => 'text-primary',
                'background' => 'bg-color-blueLight',
                'perfdata' => null
            ];
        }

        $servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($servicestatus['Servicestatus']);

        $icon = $this->serviceIcons[$servicestatus->currentState()];

        if ($servicestatus->isAcknowledged()) {
            $icon = $this->ackIcon;
        }

        if ($servicestatus->isInDowntime()) {
            $icon = $this->downtimeIcon;
        }

        if ($servicestatus->isAcknowledged() && $servicestatus->isInDowntime()) {
            $icon = $this->ackAndDowntimeIcon;
        }

        $perfdata = new PerfdataParser($servicestatus->getPerfdata());


        return [
            'icon' => $icon,
            'color' => $servicestatus->ServiceStatusColor(),
            'background' => $servicestatus->ServiceStatusBackgroundColor(),
            'perfdata' => $perfdata->parse()
        ];
    }

    /**
     * @param Model $Service
     * @param Model $Hoststatus
     * @param Model $Servicestatus
     * @param $hostgroup
     * @return array
     */
    public function getHostgroupItemImage(Model $Service, Model $Hoststatus, Model $Servicestatus, $hostgroup) {
        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();

        $hostUuids = Hash::extract($hostgroup['Host'], '{n}.uuid');

        $hoststatusByUuids = $Hoststatus->byUuid($hostUuids, $HoststatusFields);
        if (empty($hoststatusByUuids)) {
            return [
                'icon' => $this->errorIcon,
                'color' => 'text-primary',
                'background' => 'bg-color-blueLight'
            ];
        }
        $worstHostState = array_values(
            Hash::sort($hoststatusByUuids, '{s}.Hoststatus.current_state', 'desc')
        );

        $hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($worstHostState[0]['Hoststatus']);

        $icon = $this->hostIcons[$hoststatus->currentState()];
        $color = $hoststatus->HostStatusColor();
        $background = $hoststatus->HostStatusBackgroundColor();


        if ($hoststatus->isAcknowledged()) {
            $icon = $this->ackIcon;
        }

        if ($hoststatus->isInDowntime()) {
            $icon = $this->downtimeIcon;
        }

        if ($hoststatus->isAcknowledged() && $hoststatus->isInDowntime()) {
            $icon = $this->ackAndDowntimeIcon;
        }

        if ($hoststatus->currentState() > 0) {
            return [
                'icon' => $icon,
                'color' => $color,
                'background' => $background
            ];
        }

        //Check services for cumulated state (only if host is up)
        $hostIds = Hash::extract($hostgroup['Host'], '{n}.id');

        //Check services for cumulated state (only if host is up)
        $services = $Service->find('list', [
            'recursive' => -1,
            'fields' => [
                'Service.uuid'
            ],
            'conditions' => [
                'Service.host_id' => $hostIds,
                'Service.disabled' => 0
            ]
        ]);

        $ServicestatusFieds = new ServicestatusFields($this->DbBackend);
        $ServicestatusFieds->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
        $ServicestatusConditions = new ServicestatusConditions($this->DbBackend);
        $ServicestatusConditions->servicesWarningCriticalAndUnknown();
        $servicestatus = $Servicestatus->byUuid($services, $ServicestatusFieds, $ServicestatusConditions);

        if (!empty($servicestatus)) {
            $worstServiceState = array_values(
                Hash::sort($servicestatus, '{s}.Servicestatus.current_state', 'desc')
            );

            $servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($worstServiceState[0]['Servicestatus']);
            $serviceIcon = $this->serviceIcons[$servicestatus->currentState()];

            if ($servicestatus->isAcknowledged()) {
                $serviceIcon = $this->ackIcon;
            }

            if ($servicestatus->isInDowntime()) {
                $serviceIcon = $this->downtimeIcon;
            }

            if ($servicestatus->isAcknowledged() && $servicestatus->isInDowntime()) {
                $serviceIcon = $this->ackAndDowntimeIcon;
            }
            return [
                'icon' => $serviceIcon,
                'color' => $servicestatus->ServiceStatusColor(),
                'background' => $servicestatus->ServiceStatusBackgroundColor()
            ];
        }

        return [
            'icon' => $icon,
            'color' => $color,
            'background' => $background
        ];
    }

    /**
     * @param Model $Service
     * @param Model $Hoststatus
     * @param Model $Servicestatus
     * @param $host
     * @param UserTime $UserTime
     * @return array
     */
    public function getHostSummary(Model $Service, Model $Hoststatus, Model $Servicestatus, $host, UserTime $UserTime) {
        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields
            ->currentState()
            ->isHardstate()
            ->output()
            ->perfdata()
            ->currentCheckAttempt()
            ->maxCheckAttempts()
            ->lastCheck()
            ->nextCheck()
            ->lastStateChange()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged();
        $hoststatus = $Hoststatus->byUuid($host['Host']['uuid'], $HoststatusFields);
        if (empty($hoststatus)) {
            $hoststatus['Hoststatus'] = [];
        }

        $hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($hoststatus['Hoststatus'], $UserTime);

        $services = $Service->find('all', [
            'recursive' => -1,
            'contain' => [
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.name'
                    ]
                ]
            ],
            'fields' => [
                'Service.name',
                'Service.uuid'
            ],
            'conditions' => [
                'Service.host_id' => $host['Host']['id'],
                'Service.disabled' => 0
            ]
        ]);

        $ServicestatusFieds = new ServicestatusFields($this->DbBackend);
        $ServicestatusFieds
            ->currentState()
            ->isHardstate()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged()
            ->output();
        $ServicestatusConditions = new ServicestatusConditions($this->DbBackend);

        $servicesUuids = Hash::extract($services, '{n}.Service.uuid');
        $servicestatusResults = $Servicestatus->byUuid($servicesUuids, $ServicestatusFieds, $ServicestatusConditions);
        $servicesResult = [];
        foreach ($services as $service) {
            $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($service);
            if (isset($servicestatusResults[$Service->getUuid()])) {
                $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus(
                    $servicestatusResults[$Service->getUuid()]['Servicestatus']
                );
            } else {
                $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus(
                    ['Servicestatus' => []]
                );
            }

            $servicesResult[] = [
                'Service' => $Service->toArray(),
                'Servicestatus' => $Servicestatus->toArray()
            ];
        }
        $servicesResult = Hash::sort($servicesResult, '{s}.Servicestatus.currentState', 'desc');

        $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($host);

        return [
            'Host' => $Host->toArray(),
            'Hoststatus' => $hoststatus->toArray(),
            'Services' => $servicesResult
        ];
    }

    /**
     * @param Model $Service
     * @param Model $Hoststatus
     * @param Model $Servicestatus
     * @param $service
     * @param UserTime $UserTime
     * @return array
     */
    public function getServiceSummary(Model $Service, Model $Hoststatus, Model $Servicestatus, $service, UserTime $UserTime) {
        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields
            ->currentState()
            ->isHardstate()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged();
        $hoststatus = $Hoststatus->byUuid($service['Host']['uuid'], $HoststatusFields);
        if (empty($hoststatus)) {
            $hoststatus['Hoststatus'] = [];
        }

        $hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus(
            $hoststatus['Hoststatus'],
            $UserTime
        );

        $ServicestatusFieds = new ServicestatusFields($this->DbBackend);
        $ServicestatusFieds
            ->currentState()
            ->isHardstate()
            ->output()
            ->perfdata()
            ->currentCheckAttempt()
            ->maxCheckAttempts()
            ->lastCheck()
            ->nextCheck()
            ->lastStateChange()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged();

        $ServicestatusConditions = new ServicestatusConditions($this->DbBackend);

        $Servicestatus = $Servicestatus->byUuid($service['Service']['uuid'], $ServicestatusFieds, $ServicestatusConditions);
        $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($service);
        if (!empty($Servicestatus)) {
            $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus(
                $Servicestatus['Servicestatus'],
                $UserTime
            );
        } else {
            $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus(
                ['Servicestatus' => []]
            );
        }
        $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service);

        return [
            'Host' => $Host->toArray(),
            'Hoststatus' => $hoststatus->toArray(),
            'Service' => $Service->toArray(),
            'Servicestatus' => $Servicestatus->toArray()
        ];
    }

    /**
     * @param Model $Service
     * @param Model $Hoststatus
     * @param Model $Servicestatus
     * @param $hostgroup
     * @param UserTime $UserTime
     * @return array
     */
    public function getHostgroupSummary(Model $Service, Model $Hoststatus, Model $Servicestatus, $hostgroup) {
        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields
            ->currentState()
            ->isHardstate()
            ->output()
            ->perfdata()
            ->currentCheckAttempt()
            ->maxCheckAttempts()
            ->lastCheck()
            ->nextCheck()
            ->lastStateChange()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged();

        $hostUuids = Hash::extract($hostgroup['Host'], '{n}.uuid');

        $hoststatusByUuids = $Hoststatus->byUuid($hostUuids, $HoststatusFields);

        $ServicestatusFieds = new ServicestatusFields($this->DbBackend);
        $ServicestatusFieds
            ->currentState()
            ->isHardstate()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged()
            ->output();
        $ServicestatusConditions = new ServicestatusConditions($this->DbBackend);


        if (empty($hoststatusByUuids)) {
            $hoststatusByUuids['Hoststatus'] = [];
        }
        $hoststatusResult = [];
        $cumulatedHostState = -1;
        $cumulatedState = -1;
        $allServiceStatus = [];
        foreach ($hostgroup['Host'] as $host) {
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host(['Host' => $host]);
            if (isset($hoststatusByUuids[$Host->getUuid()])) {
                $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus(
                    $hoststatusByUuids[$Host->getUuid()]['Hoststatus']
                );
                if ($Hoststatus->currentState() > $cumulatedHostState) {
                    $cumulatedHostState = $Hoststatus->currentState();
                }
            } else {
                $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus(
                    ['Hoststatus' => []]
                );
            }
            $services = $Service->find('all', [
                'recursive' => -1,
                'contain' => [
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.name'
                        ]
                    ]
                ],
                'fields' => [
                    'Service.name',
                    'Service.uuid'
                ],
                'conditions' => [
                    'Service.host_id' => $Host->getId(),
                    'Service.disabled' => 0
                ]
            ]);

            $servicesUuids = Hash::extract($services, '{n}.Service.uuid');
            $servicestatusResults = $Servicestatus->byUuid($servicesUuids, $ServicestatusFieds, $ServicestatusConditions);
            foreach ($servicestatusResults as $servicestatusResult) {
                $allServiceStatus[] = $servicestatusResult['Servicestatus']['current_state'];
            }


            $hoststatusResult[] = [
                'Host' => $Host->toArray(),
                'Hoststatus' => $Hoststatus->toArray(),
                'ServiceSummary' => $Service->getServiceStateSummary($servicestatusResults, false)
            ];
        }

        $hoststatusResult = Hash::sort($hoststatusResult, '{s}.Hoststatus.currentState', 'desc');

        $hostgroup = [
            'name' => $hostgroup['Container']['name'],
            'description' => $hostgroup['Hostgroup']['description']
        ];

        return [
            'Hostgroup' => $hostgroup,
            'Hosts' =>$hoststatusResult,
            'CumulatedHostState' => $cumulatedHostState,
            'CumulatedServiceState' => (!empty($allServiceStatus))?max($allServiceStatus):$cumulatedState
        ];
    }
}

