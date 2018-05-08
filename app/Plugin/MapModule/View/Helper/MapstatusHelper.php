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

class MapstatusHelper extends AppHelper {

    public $hoststatus = [];
    public $servicestatus = [];
    public $servicegroupstatus = [];
    public $hostgroupstatus = [];

    public function beforeRender($viewFile) {
        //fill Hosts
        if (isset($this->_View->viewVars['hoststatus'])) {
            $hoststatus = $this->_View->viewVars['hoststatus'];
            foreach ($hoststatus as $uuid => $hs) {
                if(!isset($hs['Hoststatus'])){
                    $hs['Hoststatus'] = [];
                }
                $this->hoststatus[$uuid] = $hs['Hoststatus'];
                if (isset($hs['Host'])) {
                    array_push($this->hoststatus[$uuid], $hs['Host']);
                }
            }
        }
        
        //fill Services
        if (isset($this->_View->viewVars['servicestatus'])) {
            $servicestatus = $this->_View->viewVars['servicestatus'];
            if (!empty($servicestatus)) {
                foreach ($servicestatus as $uuid => $ss) {
                    if (!empty($ss['Servicestatus'])) {
                        $this->servicestatus[$uuid] = $ss['Servicestatus'];
                        array_push($this->servicestatus[$uuid], $ss['Service']);
                    }
                }
            }
        }

        //fill Hostgroups
        if (isset($this->_View->viewVars['hostgroups'])) {
            $hostgroupstatus = $this->_View->viewVars['hostgroups'];
            foreach ($hostgroupstatus as $hgs) {
                $this->hostgroupstatus[$hgs['Hostgroup']['uuid']] = $hgs['Host'];
            }
        }

        //fill Servicegroups
        if (isset($this->_View->viewVars['servicegroups'])) {
            $servicegroupstatus = $this->_View->viewVars['servicegroups'];
            foreach ($servicegroupstatus as $sgs) {
                if (!empty($sgs['Servicegroup']['uuid'])) {
                    $this->servicegroupstatus[$sgs['Servicegroup']['uuid']] = $sgs;
                }
            }
            if(!isset($servicegroupstatus['Servicestatus'])){
                $servicegroupstatus['Servicestatus'] = [];
            }
            $this->servicegroupstatus['Servicestatus'] = $servicegroupstatus['Servicestatus'];
        }
        if (isset($this->_View->viewVars['mapstatus'])) {
            $this->mapstatus = $this->_View->viewVars['mapstatus'];
        }
    }

    public function hoststatus($uuid) {
        $status = [];
        if (isset($this->hoststatus[$uuid]['current_state'])) {
            if ($this->hoststatus[$uuid]['problem_has_been_acknowledged'] == 1 && $this->hoststatus[$uuid]['scheduled_downtime_depth'] > 0) {
                $status = ['state' => $this->hoststatus[$uuid]['current_state'], 'is_flapping' => $this->hoststatus[$uuid]['is_flapping'], 'human_state' => __('Host state is acknowledged and the host is in scheduled downtime'), 'image' => 'downtime_ack.png'];
            } else if ($this->hoststatus[$uuid]['problem_has_been_acknowledged'] == 1) {
                $status = ['state' => $this->hoststatus[$uuid]['current_state'], 'is_flapping' => $this->hoststatus[$uuid]['is_flapping'], 'human_state' => __('Host state is acknowledged'), 'image' => 'ack.png'];
            } else if ($this->hoststatus[$uuid]['scheduled_downtime_depth'] > 0) {
                $status = ['state' => $this->hoststatus[$uuid]['current_state'], 'is_flapping' => $this->hoststatus[$uuid]['is_flapping'], 'human_state' => __('Host is in scheduled downtime'), 'image' => 'downtime.png'];
            } else {
                $state = [
                    0 => [
                        'human_state' => __('Host is up'),
                        'image'       => 'up.png',
                    ],
                    1 => [
                        'human_state' => __('Host is down'),
                        'image'       => 'down.png',
                    ],
                    2 => [
                        'human_state' => __('Host is unreachable'),
                        'image'       => 'unreachable.png',
                    ],
                ];
                $status = ['state' => $this->hoststatus[$uuid]['current_state'], 'is_flapping' => $this->hoststatus[$uuid]['is_flapping'], 'human_state' => $state[$this->hoststatus[$uuid]['current_state']]['human_state'], 'image' => $state[$this->hoststatus[$uuid]['current_state']]['image']];
            }
        } else {
            $status = ['state' => -1, 'human_state' => __('Not found in monitoring'), 'image' => 'error.png'];
        }

        if (!empty($this->hoststatus[$uuid]['Servicestatus']) && $status['state'] == 0) {
            //take the cumulative service state if the host has at least one service
            $hostServiceStatus = $this->hoststatus[$uuid]['Servicestatus'];
            if (isset($this->servicestatus)) {
                $servicestatusOriginal = $this->servicestatus;
            }
            foreach ($hostServiceStatus as $uuid => $hss) {
                if(!isset($hss['Servicestatus'])){
                    $hss['Servicestatus'] = [];
                }
                $this->servicestatus[$uuid] = $hss['Servicestatus'];
                array_push($this->servicestatus[$uuid], $hss['Service']);
            }
            foreach ($hostServiceStatus as $uuid => $hss) {
                $hostServiceStates[$uuid] = $this->servicestatus($uuid);
            }
            $cumulative_service_state['Service'] = Hash::apply($hostServiceStates, '{n}.state', 'max');
            $stateKey = null;

            $numberOfAck = 0;
            foreach ($hostServiceStates as $key => $value) {
                if(!isset($value['problem_has_been_acknowledged'])){
                    $value['problem_has_been_acknowledged'] = 0;
                }
                if ($value['problem_has_been_acknowledged'] == 1) {
                    $numberOfAck++;
                }
            }

            if (sizeof($hostServiceStates) == $numberOfAck) {
                //there are only ack services
                foreach ($hostServiceStates as $key => $value) {
                    if ($value['state'] == $cumulative_service_state['Service']) {
                        $stateKey = $key;
                    }
                }
            } else {
                foreach ($hostServiceStates as $key => $value) {
                    if ($value['state'] == $cumulative_service_state['Service'] && $value['problem_has_been_acknowledged'] == 0) {
                        $stateKey = $key;
                    }
                }
            }

            if (!empty($stateKey)) {
                $servicestate = $hostServiceStates[$stateKey];
            }
            $status = $servicestate;
        }
        return $status;
    }


    /**
     * retrieve single or all fields from a host
     * Get every field as array:
     * $this->Mapstatus->hoststatusField($item['Host'][0]['uuid'])
     * Get a single Value from the host
     * $this->Mapstatus->hoststatusField($item['Host'][0]['uuid'], 'output')
     * Get a single Value from the Host or return the default value
     * $this->Mapstatus->hoststatusField($item['Host'][0]['uuid'], 'output', 'defaultValue')
     *
     * @param  String $uuid the UUID of the host
     * @param  String $field the field you want (optional)
     * @param  null $default the default value which shall be returned when the value wasnt found
     *
     * @return array           Array with the Hostinformation
     */
    public function hoststatusField($uuid, $field = null, $default = null) {
        if ($field === null && isset($this->hoststatus[$uuid])) {
            return $this->hoststatus[$uuid];
        }
        if (isset($this->hoststatus[$uuid][$field])) {
            return $this->hoststatus[$uuid][$field];
        }

        return $default;
    }


    /**
     * Returns the current service state
     *
     * @param  String $uuid the service UUID
     *
     * @return array            Array with the current service state
     */
    public function servicestatus($uuid) {
        if (isset($this->servicestatus[$uuid]['current_state'])) {
            if ($this->servicestatus[$uuid]['problem_has_been_acknowledged'] == 1 && $this->servicestatus[$uuid]['scheduled_downtime_depth'] > 0) {
                return [
                    'state'                         => $this->servicestatus[$uuid]['current_state'],
                    'is_flapping'                   => $this->servicestatus[$uuid]['is_flapping'],
                    'human_state'                   => __('Service state is acknowledged and the service is in scheduled downtime'),
                    'image'                         => 'downtime_ack.png',
                    'problem_has_been_acknowledged' => $this->servicestatus[$uuid]['problem_has_been_acknowledged']
                ];
            }

            if ($this->servicestatus[$uuid]['problem_has_been_acknowledged'] == 1) {
                return [
                    'state'                         => $this->servicestatus[$uuid]['current_state'],
                    'is_flapping'                   => $this->servicestatus[$uuid]['is_flapping'],
                    'human_state'                   => __('Service state is acknowledged'),
                    'image'                         => 'ack.png',
                    'problem_has_been_acknowledged' => $this->servicestatus[$uuid]['problem_has_been_acknowledged']
                ];
            }

            if ($this->servicestatus[$uuid]['scheduled_downtime_depth'] > 0) {
                return [
                    'state'                         => $this->servicestatus[$uuid]['current_state'],
                    'is_flapping'                   => $this->servicestatus[$uuid]['is_flapping'],
                    'human_state'                   => __('Service is in scheduled downtime'),
                    'image'                         => 'downtime.png',
                    'problem_has_been_acknowledged' => $this->servicestatus[$uuid]['problem_has_been_acknowledged']
                ];
            }

            $state = [
                0 => [
                    'human_state' => __('Ok'),
                    'image'       => 'up.png',
                ],
                1 => [
                    'human_state' => __('Warning'),
                    'image'       => 'warning.png',
                ],
                2 => [
                    'human_state' => __('Critical'),
                    'image'       => 'critical.png',
                ],
                3 => [
                    'human_state' => __('Unknown'),
                    'image'       => 'unknown.png',
                ],
            ];

            return [
                'state'                         => $this->servicestatus[$uuid]['current_state'],
                'is_flapping'                   => $this->servicestatus[$uuid]['is_flapping'],
                'human_state'                   => $state[$this->servicestatus[$uuid]['current_state']]['human_state'],
                'image'                         => $state[$this->servicestatus[$uuid]['current_state']]['image'],
                'perfdata'                      => $this->servicestatus[$uuid]['perfdata'],
                'problem_has_been_acknowledged' => $this->servicestatus[$uuid]['problem_has_been_acknowledged']
            ];
        }

        return ['state' => -1, 'human_state' => __('Not found in monitoring'), 'image' => 'error.png'];
    }


    public function servicestatusField($uuid, $field = null, $default = null) {
        if ($field === null && isset($this->servicestatus[$uuid])) {
            return $this->servicestatus[$uuid];
        }
        if (isset($this->servicestatus[$uuid][$field])) {
            return $this->servicestatus[$uuid][$field];
        }

        return $default;
    }


    public function servicegroupstatus($uuid) {

        $serviceUuids = Hash::extract($this->servicegroupstatus[$uuid], 'Service.{n}.uuid');
        $servicestates = [];
        foreach ($serviceUuids as $serviceUuid) {
            if(!isset($this->servicegroupstatus['Servicestatus'][$serviceUuid])){
                $this->servicegroupstatus['Servicestatus'][$serviceUuid] = [];
            }
            $servicestates[] = $this->servicegroupstatus['Servicestatus'][$serviceUuid];
        }
        $servicestate = Hash::extract($servicestates, '{n}.Servicestatus');

        if (!empty($servicestate)) {
            $cumulative_service_state = Hash::apply($servicestate, '{n}.current_state', 'max');

            return $this->ServicegroupstatusValues($cumulative_service_state);
        }

        return ['state' => -1, 'human_state' => __('Not found in monitoring'), 'image' => 'error.png'];
    }

    public function hostgroupstatus($uuid) {
        $cumulative_service_state = false;
        if (!empty($this->hostgroupstatus[$uuid])) {
            $cumulative_host_state = Hash::apply($this->hostgroupstatus[$uuid], '{n}.Hoststatus.current_state', 'max');
            if ($cumulative_host_state == 0) {
                foreach ($this->hostgroupstatus[$uuid] as $key => $hosts) {
                    $currentStates = Hash::extract($hosts, 'Servicestatus.{n}.current_state');
                    if (is_array($currentStates) && !empty($currentStates)) {
                        $current_cumulative_service_state = Hash::apply($currentStates, '{n}', 'max');
                        if (isset($current_cumulative_service_state)) {
                            $cumulative_service_states_data[] = $current_cumulative_service_state;
                        }
                    }
                }
                if (isset($cumulative_service_states_data)) {
                    $cumulative_service_state = max($cumulative_service_states_data);
                }
            }
            return (!$cumulative_service_state) ? $this->hostgroupstatusValuesHost($cumulative_host_state) : $this->hostgroupstatusValuesService($cumulative_service_state);
        }
        return ['state' => -1, 'human_state' => __('Not found in monitoring'), 'image' => 'error.png'];
    }

    public function hostgroupHoststatus($host) {
        if (!empty($host['Servicestatus'])) {
            $servicestatus = [];
            foreach ($host['Servicestatus'] as $servicestates) {
                $servicestatus[] = $servicestates['current_state'];
            }
            if (!empty($servicestatus)) {
                $cumulativeServiceState = max($servicestatus);

                return $this->hostgroupstatusValuesService($cumulativeServiceState);
            }

        } else {
            //host has no services -> return hoststatus
            return $this->hostgroupstatusValuesHost($host['Hoststatus']['current_state']);
        }

    }


    public function hostgroupstatusValuesHost($state) {
        if (!isset($state)) {
            $err = [
                'human_state' => __('Not found in monitoring'),
                'image'       => 'error.png',
                'state'       => -1,
                'class'       => 'btn-primary',
                'type'        => 'host'
            ];

            return $err;
        }
        $states = [
            0  => [
                'human_state' => __('Up'),
                'image'       => 'up.png',
                'state'       => 0,
                'class'       => 'btn-success',
                'type'        => 'host'
            ],
            1  => [
                'human_state' => __('Down'),
                'image'       => 'down.png',
                'state'       => 1,
                'class'       => 'btn-danger',
                'type'        => 'host'
            ],
            2  => [
                'human_state' => __('Unreachable'),
                'image'       => 'unreachable.png',
                'state'       => 2,
                'class'       => 'btn-unknown',
                'type'        => 'host'
            ],
            -1 => [
                'human_state' => __('Not found in monitoring'),
                'image'       => 'error.png',
                'state'       => -1,
                'class'       => 'btn-primary',
                'type'        => 'host'
            ],
        ];

        return $states[$state];
    }

    public function hostgroupstatusValuesService($state) {
        if (!isset($state)) {
            $err = [
                'human_state' => __('Not found in monitoring'),
                'image'       => 'error.png',
                'state'       => -1,
                'class'       => 'btn-primary',
                'type'        => 'service'
            ];

            return $err;
        }
        $states = [
            0  => [
                'human_state' => __('Ok'),
                'image'       => 'up.png',
                'state'       => 0,
                'class'       => 'btn-success',
                'type'        => 'service'
            ],
            1  => [
                'human_state' => __('Warning'),
                'image'       => 'warning.png',
                'state'       => 1,
                'class'       => 'btn-warning',
                'type'        => 'service'
            ],
            2  => [
                'human_state' => __('Critical'),
                'image'       => 'critical.png',
                'state'       => 2,
                'class'       => 'btn-danger',
                'type'        => 'service'
            ],
            3  => [
                'human_state' => __('Unreachable'),
                'image'       => 'unreachable.png',
                'state'       => 3,
                'class'       => 'btn-unknown',
                'type'        => 'service'
            ],
            -1 => [
                'human_state' => __('Not found in monitoring'),
                'image'       => 'error.png',
                'state'       => -1,
                'class'       => 'btn-primary',
                'type'        => 'service'
            ],
        ];

        return $states[$state];
    }

    public function ServicegroupstatusValues($state) {
        if (!isset($state)) {
            $err = [
                'human_state' => __('Not found in monitoring'),
                'image'       => 'error.png',
                'state'       => -1,
            ];

            return $err;
        }
        $states = [
            0  => [
                'human_state' => __('Ok'),
                'image'       => 'up.png',
                'state'       => 0,
            ],
            1  => [
                'human_state' => __('Warning'),
                'image'       => 'warning.png',
                'state'       => 1,
            ],
            2  => [
                'human_state' => __('Critical'),
                'image'       => 'critical.png',
                'state'       => 2,
            ],
            3  => [
                'human_state' => __('Unknown'),
                'image'       => 'unknown.png',
                'state'       => 3,
            ],
            -1 => [
                'human_state' => __('Not found in monitoring'),
                'image'       => 'error.png',
                'state'       => -1,
            ],
        ];

        return $states[$state];
    }

    public function mapstatus($id) {
        //returns the summary state for a Map
        $mapstructure = $this->mapstatus['structure'][$id];
        $mapstatus = $this->mapstatus['status'];
        $state = -1;
        if (!empty($mapstructure) && !empty($mapstatus)) {
            $hostUuidsByMap = Hash::extract($mapstructure, '{n}.{s}.{n}.host.{n}');
            $serviceUuidsByMap = Hash::extract($mapstructure, '{n}.{s}.{n}.service.{n}');

            $allHoststates = [];
            $allServicestates = [];
            foreach ($hostUuidsByMap as $hostUuid) {
                $hoststatus = $mapstatus['hoststatus'];
                if (isset($hoststatus[$hostUuid])) {
                    //ACK should be shown as OK
                    if ($hoststatus[$hostUuid]['Hoststatus']['problem_has_been_acknowledged']) {
                        $allHoststates[] = 0;
                    } else {
                        $allHoststates[] = $hoststatus[$hostUuid]['Hoststatus']['current_state'];
                    }
                }
            }

            foreach ($serviceUuidsByMap as $serviceUuid) {
                $servicestatus = $mapstatus['servicestatus'];
                if (isset($servicestatus[$serviceUuid])) {
                    //ACK should be shown as OK
                    if ($servicestatus[$serviceUuid]['Servicestatus']['problem_has_been_acknowledged']) {
                        $allServicestates[] = 0;
                    } else {
                        $allServicestates[] = $servicestatus[$serviceUuid]['Servicestatus']['current_state'];
                    }
                }
            }


            $cumulative_host_state = -1;
            if (!empty($allHoststates)) {
                $cumulative_host_state = Hash::apply($allHoststates, '{n}', 'max');
            }

            $cumulative_service_state = -1;
            if (!empty($allServicestates)) {
                $cumulative_service_state = Hash::apply($allServicestates, '{n}', 'max');
            }

            if ($cumulative_host_state > $cumulative_service_state) {
                $state = $this->hostgroupstatusValuesHost($cumulative_host_state);
            } else {
                $state = $this->hostgroupstatusValuesService($cumulative_service_state);
            }
        }

        return $state;
    }

}
