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

/**
 * Class AgentResponseToServicetemplateMapper
 * @package itnovum\openITCOCKPIT\Agent
 * @deprecated
 */
class AgentResponseToServicetemplateMapper {

    private $agentResponse = [];

    private $agentchecks = [];

    /**
     * AgentResponseToServicetemplateMapper constructor.
     * @param array $agentResponse
     * @param array $agentchecks
     */
    public function __construct($agentResponse, $agentchecks) {
        $this->agentResponse = $agentResponse;

        foreach ($agentchecks as $agentcheck) {
            $key = $agentcheck['name'];
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
                        $servicename = __('Memory usage percentage');

                        $check = [
                            'name'       => $servicename,
                            'agentcheck' => [
                                'name'            => $agentcheck['name'],
                                'plugin_name'     => $agentcheck['plugin_name'],
                                'service' => $agentcheck['service']
                            ]
                        ];
                        $check['agentcheck']['service']['name'] = $servicename;

                        $mapping['health'][] = $check;
                        continue;

                    case 'swap':
                        $servicename = __('Swap usage percentage');

                        $check = [
                            'name'       => $servicename,
                            'agentcheck' => [
                                'name'            => $agentcheck['name'],
                                'plugin_name'     => $agentcheck['plugin_name'],
                                'service' => $agentcheck['service']
                            ]
                        ];
                        $check['agentcheck']['service']['name'] = $servicename;

                        $mapping['health'][] = $check;
                        continue;

                    case 'cpu_percentage':
                        $servicename = __('CPU usage percentage');

                        $check = [
                            'name'       => $servicename,
                            'agentcheck' => [
                                'name'            => $agentcheck['name'],
                                'plugin_name'     => $agentcheck['plugin_name'],
                                'service' => $agentcheck['service']
                            ]
                        ];
                        $check['agentcheck']['service']['name'] = $servicename;

                        $mapping['health'][] = $check;
                        continue;

                }

                if (is_array($items)) {
                    foreach ($items as $itemKey => $item) {
                        switch ($groupKey) {
                            case 'disks':
                                $servicename = sprintf(
                                    '%s %s',
                                    __('Disk usage of:'),
                                    $item['disk']['device']
                                );

                                $check = [
                                    'name'       => $servicename,
                                    'agentcheck' => [
                                        'name'            => $agentcheck['name'],
                                        'plugin_name'     => $agentcheck['plugin_name'],
                                        'service' => $agentcheck['service']
                                    ]
                                ];

                                $check['agentcheck']['service']['name'] = $servicename;
                                foreach ($check['agentcheck']['service']['servicecommandargumentvalues'] as $index => $arg) {
                                    if ($arg['commandargument']['name'] === '$ARG3$') {
                                        $check['agentcheck']['service']['servicecommandargumentvalues'][$index]['value'] = $item['disk']['device'];
                                    }
                                }

                                $mapping['health'][] = $check;
                                break;

                            case 'disk_io':
                                if ($itemKey === 'timestamp') {
                                    continue;
                                }

                                $servicename = sprintf(
                                    '%s %s',
                                    __('Disk stats of:'),
                                    $itemKey
                                );

                                $check = [
                                    'name'       => $servicename,
                                    'agentcheck' => [
                                        'name'            => $agentcheck['name'],
                                        'plugin_name'     => $agentcheck['plugin_name'],
                                        'service' => $agentcheck['service']
                                    ]
                                ];

                                $check['agentcheck']['service']['name'] = $servicename;
                                foreach ($check['agentcheck']['service']['servicecommandargumentvalues'] as $index => $arg) {
                                    if ($arg['commandargument']['name'] === '$ARG3$') {
                                        $check['agentcheck']['service']['servicecommandargumentvalues'][$index]['value'] = $itemKey;
                                    }
                                }

                                $mapping['health'][] = $check;
                                break;

                            case 'processes':
                                $processName = $item['name'];
                                if (!empty($item['exec'])) {
                                    $processName = $item['exec'];
                                }
                                if (!empty($item['cmdline'])) {
                                    $processName = implode(' ', $item['cmdline']);
                                }

                                $check = [
                                    'name'       => $processName,
                                    'agentcheck' => [
                                        'name'            => $agentcheck['name'],
                                        'plugin_name'     => $agentcheck['plugin_name'],
                                        'service' => $agentcheck['service']
                                    ]
                                ];

                                $check['agentcheck']['service']['name'] = $processName;
                                foreach ($check['agentcheck']['service']['servicecommandargumentvalues'] as $index => $arg) {
                                    if ($arg['commandargument']['name'] === '$ARG7$') {
                                        $check['agentcheck']['service']['servicecommandargumentvalues'][$index]['value'] = $processName;
                                    }
                                }

                                $mapping['processes'][] = $check;
                                break;

                            case 'net_stats':
                                $servicename = sprintf(
                                    '%s %s',
                                    __('Network state of:'),
                                    $itemKey
                                );

                                $check = [
                                    'name'       => $servicename,
                                    'agentcheck' => [
                                        'name'            => $agentcheck['name'],
                                        'plugin_name'     => $agentcheck['plugin_name'],
                                        'service' => $agentcheck['service']
                                    ]
                                ];

                                $check['agentcheck']['service']['name'] = $servicename;
                                /*foreach ($check['agentcheck']['service']['serviceservicecommandargumentvalues'] as $index => $arg) {
                                    if ($arg['commandargument']['name'] === '$ARG3$') {
                                        $check['agentcheck']['service']['serviceservicecommandargumentvalues'][$index]['value'] = $itemKey;
                                    }
                                }*/

                                $mapping['health'][] = $check;
                                break;

                            case 'net_io':
                                if ($itemKey === 'timestamp') {
                                    continue;
                                }
                                $servicename = sprintf(
                                    '%s %s',
                                    __('Network stats of:'),
                                    $itemKey
                                );

                                $check = [
                                    'name'       => $servicename,
                                    'agentcheck' => [
                                        'name'            => $agentcheck['name'],
                                        'plugin_name'     => $agentcheck['plugin_name'],
                                        'service' => $agentcheck['service']
                                    ]
                                ];

                                $check['agentcheck']['service']['name'] = $servicename;
                                /*foreach ($check['agentcheck']['service']['servicecommandargumentvalues'] as $index => $arg) {
                                    if ($arg['commandargument']['name'] === '$ARG3$') {
                                        a$check['agentcheck']['service']['servicecommandargumentvalues'][$index]['value'] = $itemKey;
                                    }
                                }*/

                                $mapping['health'][] = $check;
                                break;
                        }
                    }
                }
            }
        }

        return $mapping;
    }

}
