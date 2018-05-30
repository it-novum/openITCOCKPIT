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

/**
 * Class GraphCollectionsController
 * @property GraphgenTmpl     GraphgenTmpl
 * @property GraphgenTmplConf GraphgenTmplConf
 * @property GraphCollection  GraphCollection
 * @property Rrd              Rrd
 */
class GraphCollectionsController extends AppController
{
    const REDUCE_METHOD_STEPS = 1;
    const REDUCE_METHOD_AVERAGE = 2;
    const MAX_RESPONSE_GRAPH_POINTS = 1000;

    public $layout = 'Admin.default';
    public $uses = ['GraphgenTmpl', 'GraphCollection', 'GraphgenTmplConf', 'Rrd'];
    public $helpers = ['ListFilter.ListFilter'];

    public function index()
    {
        // Static conditions
        $conditions = [
//			'Host.disabled' => 0,
//			'HostsToContainers.container_id' => $this->MY_RIGHTS,
        ];
        $query = [
            'conditions' => $conditions,
            'order'      => ['GraphCollection.name' => 'asc'],
        ];

        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
        $all_collections = $this->Paginator->paginate('GraphCollection');

        $this->set(['all_collections' => $all_collections,]);
        $this->set('_serialize', ['all_collections']);
    }

    public function add()
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            //Fix HABTM validation
            $this->request->data['GraphCollection']['GraphgenTmpl'] = $this->request->data('GraphgenTmpl.GraphgenTmpl');

            if ($this->GraphCollection->saveAll($this->request->data)) {
                $this->setFlash(__('Successfully saved.'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Could not save data.'), false);
            }
        }
        $templates = $this->GraphgenTmpl->find('list');
        $this->set(compact(['saved_templates', 'templates']));
    }


    public function edit($id = null)
    {
        if (!$this->GraphCollection->exists($id)) {
            throw new NotFoundException(__('Invalid Graph Collection'));
        }

        $collection = $this->GraphCollection->findById($id);
        // Save a new or edited item.
        if ($this->request->is('post') || $this->request->is('put')) {
            //Fix HABTM validation
            $this->request->data['GraphCollection']['GraphgenTmpl'] = $this->request->data('GraphgenTmpl.GraphgenTmpl');
            if ($this->GraphCollection->saveAll($this->request->data)) {
                $this->setFlash(__('Successfully saved.'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Could not save data.'), false);
            }
        }
        $templates = $this->GraphgenTmpl->find('list');
        $this->set(compact(['collection', 'templates']));
        $this->request->data = Hash::merge($collection, $this->request->data);
    }

    public function display($id = null)
    {
        if (!$this->GraphCollection->exists($id)) {
            $this->Frontend->setJson('graphCollectionId', null);
            $id = null;
        } else {
            $id = (int)$id;
            $this->Frontend->setJson('graphCollectionId', $id);
        }
        $collections = $this->GraphCollection->find('list');
        $this->set(compact(['collections', 'id']));
    }

    public function mass_delete()
    {
        $args_are_valid = true;
        $args = func_get_args();
        foreach ($args as $i => $arg) {
            if (!is_numeric($arg)) {
                $args_are_valid = false;
            }
            $args[$i] = (int)$arg;
        }

        if ($args_are_valid) {
            $this->GraphCollection->deleteAll('GraphCollection.id IN ('.implode(',', $args).')');
            $this->setFlash(__('The Graph Collections have been deleted successfully.'));
        } else {
            $this->setFlash(__('Could not delete the graph configurations. The given arguments are invalid.'), false);
        }

        $this->redirect(['action' => 'index']);
    }

    public function loadCollectionGraphData($collection_id = 0)
    {
        $this->set('_serialize', ['collection']);
        $collection = $this->GraphCollection->loadCollection($collection_id);
        if (empty($collection)) {
            $this->set('collection', []);

            return;
        }

        $collection['GraphgenTmpl'] = $this->GraphgenTmpl->addHostsAndServices($collection['GraphgenTmpl']);
        $collection['GraphgenTmpl'] = $this->addHostAndServiceUuidsList($collection['GraphgenTmpl']);
        $collection['GraphgenTmpl'] = $this->addServiceRules($collection['GraphgenTmpl']);

        $this->set('collection', $collection);
    }

    private function addServiceRules($collection)
    {
        // Get an array of Hosts and Services
        foreach ($collection as $i => $template) {
            foreach ($template['HostAndServices'] as $host_id => $host_data) {
                $host_uuid = $host_data['host_uuid'];
                $services = $host_data['services'];
                foreach ($services as $service_id => $service_data) {
                    $service_uuid = $service_data['service_uuid'];

                    if (!isset($collection[$i]['ServiceRules'])) {
                        $collection[$i]['ServiceRules'] = [];
                    }
                    if (!isset($collection[$i]['ServiceRules'][$host_uuid])) {
                        $collection[$i]['ServiceRules'][$host_uuid] = [];
                    }

                    $collection[$i]['ServiceRules'][$host_uuid][] = $service_uuid;
                }
            }
        }

        return $collection;
    }

    private function addHostAndServiceUuidsList($collection)
    {
        // Get an array of Hosts and Services
        foreach ($collection as $i => $template) {
            foreach ($template['HostAndServices'] as $host_id => $host_data) {
                $host_uuid = $host_data['host_uuid'];
                $host_name = $host_data['host_name'];
                $services = $host_data['services'];
                foreach ($services as $service_id => $service_data) {
                    $service_uuid = $service_data['service_uuid'];
                    $service_name = $service_data['service_name'];

                    if (!isset($collection[$i]['HostAndServiceUuids'])) {
                        $collection[$i]['HostAndServiceUuids'] = [];
                    }
                    if (!isset($collection[$i]['HostAndServiceUuids'][$host_uuid])) {
                        $collection[$i]['HostAndServiceUuids'][$host_uuid] = [];
                    }

                    $enabled_perf_data = $this->Rrd->getPerfDataStructureByHostAndServiceUuid($host_uuid, $service_uuid);
                    foreach ($enabled_perf_data as $perf_data) {
                        $values = [
                            'service_id'        => $service_id,
                            'service_name'      => $service_name,
                            'service_rule_name' => $perf_data['name'],
                            'host_name'         => $host_name,
                        ];
                        $collection[$i]['HostAndServiceUuids'][$host_uuid][$service_uuid][$perf_data['ds']] = $values;
                    }
//					$collection[$i]['HostAndServiceUuids'][$host_uuid][$service_uuid] = $enabled_perf_data;
//					$collection[$i]['HostAndServiceUuids'][$host_uuid][] = $service_uuid;
                }
            }
        }

        return $collection;
    }
}
