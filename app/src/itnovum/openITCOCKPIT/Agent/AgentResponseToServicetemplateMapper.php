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

namespace itnovum\openITCOCKPIT\Agent;


use App\Model\Entity\Agentcheck;

class AgentResponseToServicetemplateMapper {

    private $agentResponse = [];

    private $agentchecks = [];

    /**
     * AgentResponseToServicetemplateMapper constructor.
     * @param array $agentResponse
     * @param array $agentchecks []
     */
    public function __construct($agentResponse, $agentchecks) {
        $this->agentResponse = $agentResponse;

        foreach ($agentchecks as $agentcheck) {
            /** @var $agentcheck Agentcheck */
            $key = $agentcheck->get('name');
            $this->agentchecks[$key] = $agentcheck;
        }
    }

    /**
     * @return array
     */
    public function getMapping() {
        $mapping = [
            'health'    => [],
            'processes' => [],
            'services'  => []
        ];

        foreach ($this->agentResponse as $groupKey => $items) {
            if (isset($this->agentchecks[$groupKey])) {
                //Servicetemplate mapping found
                /** @var Agentcheck $agentcheck */
                $agentcheck = $this->agentchecks[$groupKey];

                switch ($groupKey) {
                    case 'memory':
                        $mapping['health'][] = [
                            'name'       => __('Memory usage percentage'),
                            'agentcheck' => [
                                'name'               => $agentcheck->get('name'),
                                'servicetemplate_id' => $agentcheck->get('servicetemplate_id'),
                                'args' => []
                            ]
                        ];
                        continue;

                    case 'swap':
                        $mapping['health'][] = [
                            'name'       => __('Swap usage percentage'),
                            'agentcheck' => [
                                'name'               => $agentcheck->get('name'),
                                'servicetemplate_id' => $agentcheck->get('servicetemplate_id'),
                                                                'args' => []

                            ]
                        ];
                        continue;

                    case 'cpu_percentage':
                        $mapping['health'][] = [
                            'name'       => __('CPU usage percentage'),
                            'agentcheck' => [
                                'name'               => $agentcheck->get('name'),
                                'servicetemplate_id' => $agentcheck->get('servicetemplate_id'),
                                'args' => []
                            ]
                        ];
                        continue;

                }

                if (is_array($items)) {
                    foreach ($items as $itemKey => $item) {
                        switch ($groupKey) {
                            case 'disks':
                                $mapping['health'][] = [
                                    'name'       => sprintf(
                                        '%s %s',
                                        __('Disk usage of:'),
                                        $item['disk']['mountpoint']
                                    ),
                                    'agentcheck' => [
                                        'name'               => $agentcheck->get('name'),
                                        'servicetemplate_id' => $agentcheck->get('servicetemplate_id'),
                                        'args' => []
                                    ]
                                ];
                                break;

                            case 'disk_io':
                                if ($itemKey === 'timestamp') {
                                    continue;
                                }
                                $mapping['health'][] = [
                                    'name'       => sprintf(
                                        '%s %s',
                                        __('Disk stats of:'),
                                        $itemKey
                                    ),
                                    'agentcheck' => [
                                        'name'               => $agentcheck->get('name'),
                                        'servicetemplate_id' => $agentcheck->get('servicetemplate_id')
                                    ]
                                ];
                                break;

                            case 'processes':
                                $processName = $item['name'];
                                if (!empty($item['exec'])) {
                                    $processName = $item['exec'];
                                }
                                if (!empty($item['cmdline'])) {
                                    $processName = implode(' ', $item['cmdline']);
                                }

                                $mapping['processes'][] = [
                                    'name'       => $processName,
                                    'agentcheck' => [
                                        'name'               => $agentcheck->get('name'),
                                        'servicetemplate_id' => $agentcheck->get('servicetemplate_id')
                                    ]
                                ];
                                break;

                            case 'net_stats':
                                $mapping['health'][] = [
                                    'name'       => sprintf(
                                        '%s %s',
                                        __('Network state of:'),
                                        $itemKey
                                    ),
                                    'agentcheck' => [
                                        'name'               => $agentcheck->get('name'),
                                        'servicetemplate_id' => $agentcheck->get('servicetemplate_id')
                                    ]
                                ];
                                break;

                            case 'net_io':
                                if ($itemKey === 'timestamp') {
                                    continue;
                                }
                                $mapping['health'][] = [
                                    'name'       => sprintf(
                                        '%s %s',
                                        __('Network stats of:'),
                                        $itemKey
                                    ),
                                    'agentcheck' => [
                                        'name'               => $agentcheck->get('name'),
                                        'servicetemplate_id' => $agentcheck->get('servicetemplate_id')
                                    ]
                                ];
                                break;
                        }
                    }
                }
            }
        }

        return $mapping;
    }

}