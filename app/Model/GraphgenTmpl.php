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

class GraphgenTmpl extends AppModel {
    public $hasMany = ['GraphgenTmplConf'];

    public $hasAndBelongsToMany = [
        'GraphCollection' => [
            'className'             => 'GraphCollection',
            'joinTable'             => 'graph_tmpl_to_graph_collection',
            'foreignKey'            => 'graphgen_tmpl_id',
            'associationForeignKey' => 'graph_collection_id',
        ],
    ];

    public function loadGraphConfiguration($id = 0) {
        if (!is_numeric($id) || $id < 1) {
            return [];
        }

        // Iterate and collect all hosts for one graph configuration.
        $templates = $this->find('all', [
            'conditions' => [
                'GraphgenTmpl.id' => $id,
            ],
            'contain'    => [
                'GraphgenTmplConf' => [
                    'Service' => [
                        'fields'          => [
                            'Service.name',
                            'Service.uuid',
                        ],
                        'Host'            => [
                            'fields' => [
                                'Host.name',
                                'Host.uuid',
                            ],
                        ],
                        'Servicetemplate' => [
                            'fields' => [
                                'Servicetemplate.name',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        // addHostsAndServices() requires an array of templates.
        $templates = $this->addHostsAndServices($templates);

        if (count($templates) == 1) {
            return $templates[0];
        }

        return $templates;
    }


    /**
     * Receives the Graphgenerator templates with their corresponding hosts and services.
     * This method adds a sorted list of hosts and their services for each template in the given array.
     * It can be used to render the hosts and services of the templates.
     *
     * @param array $templates
     *
     * @return array
     */
    public function addHostsAndServices(array $templates) {
        foreach ($templates as $i => $template) {
            // Iterate and collect all hosts for one graph configuration.
            $host_and_services = [];
            foreach ($template['GraphgenTmplConf'] as $graphgen_conf) {
                $host_id = $graphgen_conf['Service']['host_id'];
                $host_name = $graphgen_conf['Service']['Host']['name'];
                if (!isset($host_and_services[$host_id])) {
                    $host_and_services[$host_id] = [
                        'host_name' => $host_name,
                        'host_uuid' => $graphgen_conf['Service']['Host']['uuid'],
                        'services'  => [],
                    ];
                }
            }

            // Iterate all services and assign them to the right hosts.
            foreach ($template['GraphgenTmplConf'] as $graphgen_conf) {
                $service_id = $graphgen_conf['service_id'];
                $host_id = $graphgen_conf['Service']['host_id'];
                if (trim($graphgen_conf['Service']['name']) != '') {
                    $service_name = $graphgen_conf['Service']['name'];
                } else {
                    $service_name = $graphgen_conf['Service']['Servicetemplate']['name'];
                }

                $host_and_services[$host_id]['services'][$service_id] = [
                    'service_name' => $service_name,
                    'service_uuid' => $graphgen_conf['Service']['uuid'],
                    'data_sources' => json_decode($graphgen_conf['data_sources']),
                ];
            }

            $templates[$i]['HostAndServices'] = $host_and_services;
        }

        return $templates;
    }
}
