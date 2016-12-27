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

App::uses('UUID', 'Lib');
App::uses('Graphgenerator', 'Model');

/**
 * Class GraphgeneratorsController
 * @property Rrd              Rrd RRD
 * @property Host             Host
 * @property Service          Service
 * @property GraphgenTmpl     GraphgenTmpl
 * @property GraphgenTmplConf GraphgenTmplConf
 * @property GraphCollection  $GraphCollection
 * @property Graphgenerator   $Graphgenerator
 */
class GraphgeneratorsController extends AppController
{
    const REDUCE_METHOD_STEPS = 1;
    const REDUCE_METHOD_AVERAGE = 2;
    const MAX_RESPONSE_GRAPH_POINTS = 1000;

    public $layout = 'Admin.default';
    public $uses = [
        'Rrd',
        'Host',
        'Service',
        'GraphgenTmpl',
        'GraphgenTmplConf',
        'GraphCollection',
        'GraphCollectionItem',
        'Graphgenerator',
    ];
    public $components = ['Paginator'];
    public $helpers = ['ListFilter.ListFilter'];

    /**
     * New/edit graph configuration.
     *
     * @param int $configuration_id
     */
    public function index($configuration_id = 0)
    {
        $this->__unbindAssociations('Host');

        $userContainerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $hostUuids = array_keys($this->Host->hostsByContainerId($userContainerIds, 'all', [], 'uuid'));
        $all_hosts = $this->Host->find('all', [
                'recursive'  => -1,
                'fields'     => ['id', 'name', 'uuid'],
                'conditions' => ['Host.uuid' => $hostUuids],
            ]
        );
        $host_uuids = [];
        $host_ids_for_select = [];
        foreach ($all_hosts as $host) {
            if ($this->Rrd->isValidHostUuid($host['Host']['uuid'])) {
                $host_ids_for_select[$host['Host']['id']] = $host['Host']['name'];
                $host_uuids[$host['Host']['id']] = $host['Host']['uuid'];
            }
        }

        $graph_configuration = [];
        if ($configuration_id > 0) {
            $graph_configuration = $this->GraphgenTmpl->loadGraphConfiguration($configuration_id);
        }
        $is_config_loaded = !empty($graph_configuration);

        // TODO replace this with smartadmin notifications
        $modals = [ // Defines the titles and bodies for the modal window of bootstrap. These are used by the BootstrapModalComponent extension.
            'request-took-to-long'            => [
                'title' => __('The request took too long.'),
                'body'  => [
                    __('The request took too long to respond and therefore was aborted.'),
                    __('The graph was not updated.'),
                ],
            ],
            'was-not-saved-no-service-chosen' => [
                'title' => __('The graph could not be saved.'),
                'body'  => [
                    __('The graph could not be saved, because there is no active service.'),
                    __('Please activate at least one service and then try to save the graph again.'),
                ],
            ],
            'not-saved'                       => [
                'title' => __('The graph could not be saved.'),
                'body'  => [
                    __('The graph could not be saved.'),
                ],
            ],
            'successfully-saved'              => [
                'title' => __('Saved'),
                'body'  => [
                    __('The graph has been saved successfully.'),
                ],
            ],
        ];

        $default_start_timestamp = date('d.m.Y H:i:s', (time() - 4 * 3600));
        $default_end_timestamp = date('d.m.Y H:i:s');

        $this->set([
            'graph_configuration'     => $graph_configuration,
            'is_config_loaded'        => $is_config_loaded,
            'host_ids_for_select'     => $host_ids_for_select,
            'modals'                  => $modals,
            'default_start_timestamp' => $default_start_timestamp,
            'default_end_timestamp'   => $default_end_timestamp,
            'host_uuids'              => $host_uuids,
        ]);
    }

    public function view($configuration_id = 0)
    {
        $this->layout = 'Admin.fullscreen';
        $this->index($configuration_id);
    }

    /**
     * Listing the existing configurations to load and edit them.
     */
    public function listing()
    {
//		$graphgen_tmpls = $this->GraphgenTmpl->find('all');
//		$this->set('graphgen_tmpls', $graphgen_tmpls);

//		$this->__unbindAssociations('Service');

        // Static conditions
        $conditions = [
//			'Host.disabled' => 0,
//			'HostsToContainers.container_id' => $this->MY_RIGHTS,
        ];

        $searchArray = $this->request->data('Filter.Host');
        $_conditions = [];
        if (!empty($searchArray)) {
            foreach ($searchArray as $field => $value) {
                $_conditions['Host.'.$field.' LIKE'] = '%'.$value.'%';
            }
            $conditions = Hash::merge($conditions, $_conditions);
        }

        $query = [
            'conditions' => $conditions,
            'limit'      => 25,
            'order'      => ['GraphgenTmpl.id' => 'asc'],
            'contain'    => [
                'GraphgenTmplConf' => [
                    'Service' => [
                        'fields'          => [
                            'Service.name',
                            'Service.uuid',
                            'Service.id',
                        ],
                        'Host'            => [
                            'fields' => [
                                'Host.name',
                                'Host.uuid',
                                'Host.id',
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
        ];

        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);

        $all_templates = $this->Paginator->paginate('GraphgenTmpl');
        $all_templates = $this->GraphgenTmpl->addHostsAndServices($all_templates);

        $this->set([
            'all_templates' => $all_templates,
        ]);
        $this->set('_serialize', ['all_templates']);

//		if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
//			if(isset($this->request->data['Filter']['HostStatus']['current_state'])){
//				//$this->set('HostStatus.current_state', $this->request->data['Filter']['HostStatus']['current_state']);
//			}else{
//				$this->set('HostStatus.current_state', []);
//			}
//			$this->set('isFilter', true);
//		}else{
//			$this->set('isFilter', false);
//		}
    }

    /**
     * This is the only public available delete function for the graph configurations yet.
     */
    public function mass_delete()
    {
        $args_are_valid = true;
        $args = func_get_args();
        foreach ($args as $arg) {
            if (!is_numeric($arg)) {
                $args_are_valid = false;
            }
        }

        if ($args_are_valid) {
            $this->GraphgenTmpl->deleteAll('GraphgenTmpl.id IN ('.implode(',', $args).')');
            $this->GraphgenTmplConf->deleteAll('GraphgenTmplConf.graphgen_tmpl_id IN ('.implode(',', $args).')');
            $this->setFlash(__('The Graph configurations have been deleted successfully.'));
        } else {
            $this->setFlash(__('Could not delete the graph configurations. The given arguments are invalid.'), false);
        }

        $this->redirect(['action' => 'listing']);
    }

    public function saveGraphTemplate()
    {
        $this->allowOnlyAjaxRequests();
        $this->allowOnlyPostRequests();
        //debug($this->request->data['GraphgenTmpl']['id']);
        //die();
        if ($this->GraphgenTmpl->validates($this->request->data)) {
            if (isset($this->request->data['GraphgenTmpl']['id'])) {
                $this->GraphgenTmplConf->deleteAll([
                    'GraphgenTmplConf.graphgen_tmpl_id' => $this->request->data['GraphgenTmpl']['id'],
                ]);
            }
            if ($this->GraphgenTmpl->saveAll($this->request->data)) {
                $this->set('success', true);
            }
        } else {
            $this->set('success', false);
        }
        $this->set('_serialize', ['success']);
    }


    public function loadGraphTemplate($id)
    {
        $this->allowOnlyAjaxRequests();

        $data = $this->GraphgenTmpl->find('all', [
            'conditions' => ['id' => $id],
        ]);
        $this->set('data', $data);
        $this->set('_serialize', ['data']); // Needs the header 'application/json' or `.json` suffix.
    }

    /**
     * Loads the services of a specific Host by its UUID.
     *
     * @param string $hostId
     */
    public function loadServicesByHostId($hostId)
    {
        $this->allowOnlyAjaxRequests();

        $userContainerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $host = $this->Host->hostsByContainerId($userContainerIds, 'first', ['Host.id' => $hostId]);

        $_services = [];
        if (!empty($host)) {
            $_services = $this->Service->find('all', [
                'conditions' => [
                    'Service.host_id' => $host['Host']['id'],
                ],
            ]);
        }

        $services = [];
        foreach ($_services as $service) {
            if ($this->Rrd->isValidHostAndServiceUuid($host['Host']['uuid'], $service['Service']['uuid'])) {
                if ($service['Service']['name'] === null || $service['Service']['name'] === '') {
                    $service['Service']['name'] = $service['Servicetemplate']['name'];
                }

                $services[] = [
                    'uuid'       => $service['Service']['uuid'],
                    'host_id'    => $service['Service']['host_id'],
                    'name'       => $service['Service']['name'],
                    'service_id' => $service['Service']['id'],
                ];
            }
        }
        $this->set('sizeof', sizeof($services));
        $this->set('Services', $services);
        $this->set('_serialize', ['Services', 'sizeof']);
    }

    /*
     * XHR
     */
    public function loadPerfDataStructures()
    {
        $this->set('_serialize', ['perf_data']);
        if (!isset($this->request->data['host_and_services_uuids']) ||
            empty($this->request->data['host_and_services_uuids'])
        ) {
            $this->set('perf_data', []);

            return;
        }
        $host_and_service_uuids = $this->request->data['host_and_services_uuids'];
        $perf_data = $this->Rrd->getPerfDataStructures($host_and_service_uuids);
        $this->set('perfperf_dataData', $perf_data);
    }

    /**
     * Loads the Services Rules of a given Host and Service by host and service UUID.
     *
     * @param string $host_uuid
     * @param string $service_uuid
     */
    public function loadServiceruleFromService($host_uuid, $service_uuid)
    {
        $this->allowOnlyAjaxRequests();

        $userContainerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        if ($this->Host->hostsByContainerId($userContainerIds, 'first', ['Host.uuid' => $host_uuid])) {
            $perfdataStructure = $this->Rrd->getPerfDataStructureByHostAndServiceUuid($host_uuid, $service_uuid);
        }

        $this->set('sizeof', sizeof($perfdataStructure));
        $this->set('perfdataStructure', $perfdataStructure);
        $this->set('_serialize', ['perfdataStructure', 'sizeof']);
    }

    /*
     * This method is used for the GraphgeneratorController as well as the ServicesController in the 'browser' action.
     *
     * @param array $options An array with the following keys:
     * 						 - 'host_and_service_uuids' as array. Each value is a key-value-pair.
     * 						 This parameter is mandatory.
     * 						 - 'start' and 'end' as timestamp. Either both values are set or both
     * 						   values will be set to a default, which will return the last two
     * 						   hours till now.
     *
     * @return array
     */
    public function fetchGraphData()
    {
        $this->allowOnlyAjaxRequests();

        $result = [];

        $this->set('rrd_data', $result);
        $this->set('_serialize', ['rrd_data']); // There is no extra view necessary when the data is also serialized.

        // Validate input.
        $host_and_service_uuids = $this->request->data('host_and_service_uuids');
        if (!$host_and_service_uuids || !is_array($host_and_service_uuids) || count($host_and_service_uuids) == 0) {
            return;
        }
        foreach ($host_and_service_uuids as $host_uuid => $service_uuids) {
            if (!UUID::is_valid($host_uuid)) {
                return;
            }

            foreach ($service_uuids as $service_uuid) {
                if (!UUID::is_valid($service_uuid)) {
                    return;
                }
            }
        }

        $service_uuid_amount = 0;
        foreach ($host_and_service_uuids as $service_uuids) {
            $service_uuid_amount += count($service_uuids);
        }
        $limit = (int)(self::MAX_RESPONSE_GRAPH_POINTS / $service_uuid_amount);

        $options = [
            'start' => time() - 2 * 3600,
            'end'   => time(),
        ];
        if (is_numeric($this->request->data('start')) && is_numeric($this->request->data('end'))) {
            // A unix timestamp is expected here for 'start' and 'end'.
            $options = [
                'start' => $this->request->data('start'),
                'end'   => $this->request->data('end'),
            ];
        }


        /*try{
            $timeZone = new DateTimeZone($this->Auth->user('timezone'));
        }catch(Exception $e){
            $timeZone = new DateTimeZone('UTC');
        }
        $clientTime = new DateTime('now', $timeZone);
        $timeZoneOffset = $clientTime->getOffset();
        print_r($timeZoneOffset);*/

        /*App::uses('Timezone', 'Lib');
        $timeZoneOffset = Timezone::getUserSystemOffset($this->Auth->user('timezone'));

        if(!$this->request->data('isUpdate')){
            if(trim(date('e')) != 'UTC'){
                $timeZoneOffset += 3600;
            }
        }*/


        foreach ($host_and_service_uuids as $host_uuid => $service_uuids) {
            foreach ($service_uuids as $service_uuid) {
                $rrd_data = $this->Rrd->getPerfDataFiles($host_uuid, $service_uuid, $options);
                $data_sources_count = count($rrd_data['data']);
                $tmp_limit = $limit / $data_sources_count;
                foreach ($rrd_data['data'] as $key => $value_array) {
                    // Limit the returned data to prevent client performance issues.
                    $rrd_data['data'][$key] = $this->reduceData($rrd_data['data'][$key], $tmp_limit, self::REDUCE_METHOD_AVERAGE);

                    /*$correctedTime = [];
                    foreach($value_array as $timestamp => $value){
                        $corrected_timestamp = $timestamp + $timeZoneOffset;
                        $correctedTime[$corrected_timestamp] = $value;
                    }
                    $rrd_data['data'][$key] = $correctedTime;*/
                }

                // Add hostname
                $additional_information['hostname'] = $this->Host->findByUuid($host_uuid)['Host']['name'];

                // Add servicename
                $service = $this->Service->findByUuid($service_uuid);
                $service_name = $service['Service']['name'] != '' ?
                    $service['Service']['name'] : $service['Servicetemplate']['name'];
                $additional_information['servicename'] = $service_name;

                $result[$host_uuid][$service_uuid] = array_merge($rrd_data, $additional_information);
            }
        }

        $this->set('rrd_data', $result);
    }

    private function reduceData($data, $limit = 500, $technique = self::REDUCE_METHOD_AVERAGE)
    {
        switch ($technique) {
            case self::REDUCE_METHOD_STEPS:
                return $this->reduceDataBySteps($data, $limit);
            case self::REDUCE_METHOD_AVERAGE:
                return $this->reduceDataByAverage($data, $limit);
            default:
                return $data;
        }
    }

    private function reduceDataByAverage($data, $limit = 500)
    {
        $data_count = count($data);
        if ($data_count <= $limit) {
            return $data;
        }

        $percent = $data_count / $limit;
        $step_size = ceil($percent);

        $i = 1;
        $result = [];
        $average_value_of_last_step = 0;
        $average_time_of_last_step = 0;
        foreach ($data as $timestamp => $value) {
            $average_value_of_last_step += $value;
            $average_time_of_last_step += $timestamp;
            if ($i % $step_size == 0) {
                $result[(int)($average_time_of_last_step / $step_size)] = $average_value_of_last_step / $step_size;

                $average_value_of_last_step = 0;
                $average_time_of_last_step = 0;
            }
            $i++;
        }

        return $result;
    }

    private function reduceDataBySteps($data, $limit = 500)
    {
        $data_count = count($data);
        if ($data_count <= $limit) {
            return $data;
        }

        $percent = $data_count / $limit;
        $steps = ceil($percent);

        $i = 0;
        $result = [];
        foreach ($data as $key => $value) {
            if ($i % $steps == 0) {
                $result[$key] = $value;
            }
            $i++;
        }

        return $result;
    }
}
