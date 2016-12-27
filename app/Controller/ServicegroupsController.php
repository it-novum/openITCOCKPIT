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


/**
 * @property Service       $Service
 * @property Servicegroup  $Servicegroup
 * @property Host          $Host
 * @property TreeComponent $Tree
 */
class ServicegroupsController extends AppController
{
    public $uses = [
        'Servicegroup',
        'Container',
        'Service',
        'User',
        MONITORING_OBJECTS,
        'Host',
    ];
    public $layout = 'Admin.default';
    public $components = [
        'Paginator',
        'ListFilter.ListFilter',
        'RequestHandler',
    ];
    public $helpers = ['ListFilter.ListFilter'];

    public $listFilters = [
        'index' => [
            'fields' => [
                'Container.name'           => ['label' => 'Name', 'searchType' => 'wildcard'],
                'Servicegroup.description' => ['label' => 'Alias', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function index()
    {
        $conditions = [
            'Container.parent_id' => $this->MY_RIGHTS,
        ];
        if (isset($this->Paginator->settings['conditions'])) {
            $conditions = Hash::merge($this->Paginator->settings['conditions'], $conditions);
        }
        $query = [
            'recursive' => -1,
            'joins'     => [
                [
                    'table'      => 'containers',
                    'alias'      => 'Container',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'Servicegroup.container_id = Container.id',
                    ],
                ],
                [
                    'table'      => 'services_to_servicegroups',
                    'alias'      => 'servicesToServicegroups',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'servicesToServicegroups.servicegroup_id = Servicegroup.id',
                    ],
                ],
                [
                    'table'      => 'services',
                    'alias'      => 'Service',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'Service.id = servicesToServicegroups.service_id',
                    ],
                ],
                [
                    'table'      => 'servicetemplates',
                    'alias'      => 'Servicetemplate',
                    'type'       => 'INNER',
                    'conditions' => [
                        'Servicetemplate.id = Service.servicetemplate_id',
                    ],
                ],
                [
                    'table'      => 'hosts',
                    'alias'      => 'Host',
                    'type'       => 'INNER',
                    'conditions' => [
                        'Host.id = Service.host_id',
                    ],
                ],
            ],

            'fields'     => [
                'Servicegroup.id',
                'Servicegroup.uuid',
                'Servicegroup.description',
                'Container.id',
                'Container.parent_id',
                'Container.name',
                'Service.id',
                'Service.name',
                'Host.id',
                'Host.name',
                'Servicetemplate.name',
            ],
            'conditions' => $conditions,
        ];

        $this->Paginator->settings['order'] = [
            'Container.name'       => 'asc',
            'Host.name'            => 'asc',
            'Service.name'         => 'asc',
            'Servicetemplate.name' => 'asc',
        ];
        if ($this->isApiRequest()) {
            unset($query['limit']);
            $all_servicegroups = $this->Servicegroup->find('all', $query);
        } else {
            $this->Paginator->settings = $query;
            $all_servicegroups = $this->Paginator->paginate();
            $all_servicegroups = Hash::merge([], Set::combine($all_servicegroups, '{n}.Servicegroup.id', '{n}.{(Servicegroup|Container)}'), Set::combine($all_servicegroups, '{n}.Service.id', '{n}.{(Service$|Servicetemplate|Host)}', '{n}.Servicegroup.id'));
        }

        $this->set('all_servicegroups', $all_servicegroups);

        //Aufruf für json oder xml view: /nagios_module/services.json oder /nagios_module/services.xml
        $this->set('_serialize', ['all_servicegroups']);
        $this->set('isFilter', false);
        if (isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null) {
            $this->set('isFilter', true);
        }
    }

    public function view($id = null)
    {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Servicegroup->exists($id)) {
            throw new NotFoundException(__('Invalid Servicegroup'));
        }

        $servicegroup = $this->Servicegroup->findById($id);
        if (!$this->allowedByContainerId(Hash::extract($servicegroup, 'Container.parent_id'))) {
            $this->render403();

            return;
        }

        $this->set('servicegroup', $servicegroup);
        $this->set('_serialize', ['servicegroup']);
    }

    public function edit($id = null)
    {
        $userId = $this->Auth->user('id');
        if (!$this->Servicegroup->exists($id)) {
            throw new NotFoundException(__('Invalid servicegroup'));
        }

        /*fixme for permissions*/
        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_SERVICEGROUP, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_SERVICEGROUP, [], $this->hasRootPrivileges);
        }

        $servicegroup = $this->Servicegroup->find('first', [
            'contain'    => [
                'Service' => [
                    'fields'          => [
                        'Service.id',
                        'Service.servicetemplate_id',
                        'Service.name',
                    ],
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.id',
                            'Servicetemplate.name',
                        ],
                    ],
                    'Host'            => [
                        'fields' => [
                            'Host.name',
                        ],
                    ],
                ],
                'Container',
            ],
            'conditions' => [
                'Servicegroup.id' => $id,
            ],
        ]);

        if (!$this->allowedByContainerId(Hash::extract($servicegroup, 'Container.parent_id'))) {
            $this->render403();

            return;
        }
        $services_for_changelog = [];
        foreach ($servicegroup['Service'] as $service) {
            $services_for_changelog[] = [
                'id'   => $service['id'],
                'name' => $service['Host']['name'].' | '.(($service['name']) ? $service['name'] : $service['Servicetemplate']['name']),
            ];
            //$services_for_changelog[$service['id']] = $service['Host']['name'].' | '.(($service['name'])?$service['name']:$service['Servicetemplate']['name']);
        }

        $serviceIds = [ROOT_CONTAINER];
        if ($this->request->is('post') == false && $this->request->is('put') == false) {
            $serviceIds[] = $servicegroup['Container']['parent_id'];
        } else {
            $serviceIds[] = $this->request->data['Container']['parent_id'];
        }
        $serviceIds = $this->Tree->resolveChildrenOfContainerIds($serviceIds);
        array_unshift($serviceIds, ROOT_CONTAINER);
        $_services = $this->Service->servicesByHostContainerIds($serviceIds);

        //Fix that duplicate hostnames dont overwrite the array key!!
        foreach ($_services as $service) {
            $hostId = $service['Host']['id'];
            $hostName = $service['Host']['name'];
            $serviceId = $service['Service']['id'];
            $serviceDescription = $service[0]['ServiceDescription'];

            $services[$hostId][$hostName][$serviceId] = $hostName.'/'.$serviceDescription;
        }

        $servicegroup['Service'] = $services_for_changelog; //Services for changelog
        if ($this->request->is('post') || $this->request->is('put')) {
            $ext_data_for_changelog = [];

            if (isset($this->request->data['Servicegroup']['Service'])) {
                $this->request->data['Service'] = $this->request->data['Servicegroup']['Service'];
            }
            if ($this->request->data('Servicegroup.Service')) {
                $serviceAsList = Hash::combine($_services, '{n}.Service.id', ['%s | %s', '{n}.Host.name', '{n}.0.ServiceDescription']);
                foreach ($this->request->data['Servicegroup']['Service'] as $service_id) {
                    $ext_data_for_changelog['Service'][] = [
                        'id'   => $service_id,
                        'name' => $serviceAsList[$service_id],
                    ];
                }
            }

            if ($this->Servicegroup->saveAll($this->request->data)) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $id,
                    OBJECT_SERVICEGROUP,
                    $this->request->data('Container.parent_id'),
                    $userId,
                    $this->request->data['Container']['name'],
                    array_merge($this->request->data, $ext_data_for_changelog),
                    $servicegroup
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }
                if ($this->request->ext == 'json') {
                    $this->serializeId();

                    return;
                }
                $this->setFlash(__('<a href="/servicegroups/edit/%s">Servicegroup</a> successfully saved', $this->Servicegroup->id));
                $this->redirect(['action' => 'index']);
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();

                    return;
                }
                $this->setFlash(__('Servicegroup could not be saved'), false);
            }
        }
        if ($this->request->is('post') == false && $this->request->is('put') == false) {
            $servicegroup['Servicegroup']['Service'] = Hash::extract($servicegroup['Service'], '{n}.id', '{n}.name');
        }

        $this->request->data = Hash::merge($servicegroup, $this->request->data);
        $this->set(compact(['servicegroup', 'containers', 'services']));
        $this->set('_serialize', ['servicegroup', 'containers', 'services']);
    }

    public function add()
    {
        $userId = $this->Auth->user('id');
        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_SERVICEGROUP, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_SERVICEGROUP, [], $this->hasRootPrivileges);
        }

        $services = [];
        $this->Frontend->set('data_placeholder', __('Please choose a service'));
        $this->Frontend->set('data_placeholder_empty', __('No entries found'));
        if ($this->request->is('post') || $this->request->is('put')) {
            $_services = [];
            if ($this->request->data['Container']['parent_id'] > 0) {
                $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->request->data['Container']['parent_id'], $this->hasRootPrivileges);
                $_services = $this->Service->servicesByHostContainerIds($containerIds);
            }

            //Fix that duplicate hostnames dont overwrite the array key!!
            foreach ($_services as $service) {
                $services[$service['Host']['id']][$service['Host']['name']][$service['Service']['id']] = $service['Host']['name'].'/'.$service[0]['ServiceDescription'];
            }
            $ext_data_for_changelog = [];
            App::uses('UUID', 'Lib');
            $this->request->data['Servicegroup']['uuid'] = UUID::v4();
            $this->request->data['Container']['containertype_id'] = CT_SERVICEGROUP;
            if (isset($this->request->data['Servicegroup']['Service'])) {
                $this->request->data['Service'] = $this->request->data['Servicegroup']['Service'];
            }
            if ($this->request->data('Servicegroup.Service')) {
                $serviceAsList = Hash::combine($_services, '{n}.Service.id', ['%s | %s', '{n}.Host.name', '{n}.0.ServiceDescription']);
                foreach ($this->request->data['Servicegroup']['Service'] as $service_id) {
                    $ext_data_for_changelog['Service'][] = [
                        'id'   => $service_id,
                        'name' => $serviceAsList[$service_id],
                    ];
                }
            }

            $isJsonRequest = $this->request->ext === 'json';
            if ($this->Servicegroup->saveAll($this->request->data)) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $this->Servicegroup->id,
                    OBJECT_SERVICEGROUP,
                    $this->request->data('Container.parent_id'),
                    $userId,
                    $this->request->data('Container.name'),
                    array_merge($this->request->data, $ext_data_for_changelog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($isJsonRequest) {
                    $this->serializeId();

                    return;
                } else {
                    $this->setFlash(__('<a href="/servicegroups/edit/%s">Servicegroup</a> successfully saved', $this->Servicegroup->id));
                    $this->redirect(['action' => 'index']);
                }
            } else {
                if ($isJsonRequest) {
                    $this->serializeErrorMessage();

                    return;
                } else {
                    $this->setFlash(__('could not save data'), false);
                }
            }
        }
        $this->set(compact(['containers', 'services']));
    }

    public function loadServices($containerId = null)
    {
        $this->allowOnlyAjaxRequests();

        $services = $this->Host->servicesByContainerIds([ROOT_CONTAINER, $containerId], 'list', [
            'forOptiongroup' => true,
        ]);
        $services = $this->Service->makeItJavaScriptAble($services);

        $data = ['services' => $services];
        $this->set($data);
        $this->set('_serialize', array_keys($data));
    }

    public function delete($id = null)
    {
        $userId = $this->Auth->user('id');
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Servicegroup->exists($id)) {
            throw new NotFoundException(__('invalid_servicegroup'));
        }
        $container = $this->Servicegroup->findById($id);

        if (!$this->allowedByContainerId(Hash::extract($container, 'Container.parent_id'))) {
            $this->render403();

            return;
        }

        if ($this->Container->delete($container['Servicegroup']['container_id'], true)) {
            $changelog_data = $this->Changelog->parseDataForChangelog(
                $this->params['action'],
                $this->params['controller'],
                $id,
                OBJECT_SERVICEGROUP,
                $container['Container']['parent_id'],
                $userId,
                $container['Container']['name'],
                $container
            );
            if ($changelog_data) {
                CakeLog::write('log', serialize($changelog_data));
            }
            $this->setFlash(__('Servicegroup deleted'));
            $this->redirect(['action' => 'index']);
        }

        $this->setFlash(__('could not delete servicegroup'), false);
        $this->redirect(['action' => 'index']);
    }

    public function mass_delete($id = null)
    {
        $userId = $this->Auth->user('id');
        foreach (func_get_args() as $servicegroupId) {
            if ($this->Servicegroup->exists($servicegroupId)) {
                $servicegroup = $this->Servicegroup->find('first', [
                    'contain'    => [
                        'Container',
                        'Service',
                    ],
                    'conditions' => [
                        'Servicegroup.id' => $servicegroupId,
                    ],
                ]);
                if ($this->allowedByContainerId(Hash::extract($servicegroup, 'Container.parent_id'))) {
                    if ($this->Container->delete($servicegroup['Servicegroup']['container_id'], true)) {
                        $changelog_data = $this->Changelog->parseDataForChangelog(
                            $this->params['action'],
                            $this->params['controller'],
                            $id,
                            OBJECT_SERVICEGROUP,
                            $servicegroup['Container']['parent_id'],
                            $userId,
                            $servicegroup['Container']['name'],
                            $servicegroup
                        );
                        if ($changelog_data) {
                            CakeLog::write('log', serialize($changelog_data));
                        }
                    }
                }
            }
        }
        $this->setFlash(__('Servicegroups deleted'));
        $this->redirect(['action' => 'index']);
    }

    public function mass_add($id = null)
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            $targetServicegroup = $this->request->data('Servicegroup.id');
            if ($this->Servicegroup->exists($targetServicegroup)) {
                $servicegroup = $this->Servicegroup->findById($targetServicegroup);
                //Save old hosts from this hostgroup
                $servicegroupMembers = [];
                foreach ($servicegroup['Service'] as $service) {
                    $servicegroupMembers[] = $service['id'];
                }
                foreach ($this->request->data('Service.id') as $service_id) {
                    $servicegroupMembers[] = $service_id;
                }
                $servicegroup['Service'] = $servicegroupMembers;
                $servicegroup['Servicegroup']['Service'] = $servicegroupMembers;
                if ($this->Servicegroup->saveAll($servicegroup)) {
                    $this->setFlash(_('Servicegroup appended successfully'));
                    $this->redirect(['action' => 'index']);
                } else {
                    $this->setFlash(_('Could not append Servicegroup'), false);
                }
            } else {
                $this->setFlash('Servicegroup not found', false);
            }
        }

        $servicesToAppend = [];
        foreach (func_get_args() as $service_id) {
            $service = $this->Service->findById($service_id);
            $servicesToAppend[] = $service;
        }
        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $servicegroups = $this->Servicegroup->servicegroupsByContainerId($containerIds, 'list');

        $this->set(compact(['servicesToAppend', 'servicegroups']));
        $this->set('back_url', $this->referer());
    }

    public function listToPdf()
    {
        $args = func_get_args();
        $conditions = [
            'Container.parent_id' => $this->MY_RIGHTS,
        ];

        if (is_array($args) && !empty($args)) {
            if (end($args) == '.pdf' && (sizeof($args) > 1)) {
                $servicegroup_ids = $args;
                end($servicegroup_ids);
                $last_key = key($servicegroup_ids);
                unset($servicegroup_ids[$last_key]);

                $_conditions = [
                    'Servicegroup.id' => $servicegroup_ids,
                ];
                $conditions = Hash::merge($conditions, $_conditions);
            } else {
                $servicegroup_ids = $args;

                $_conditions = [
                    'Servicegroup.id' => $servicegroup_ids,
                ];
                $conditions = Hash::merge($conditions, $_conditions);
            }
        }

        $servicegroups = $this->Servicegroup->find('all', [
            'recursive'  => -1,
            'order'      => [
                'Container.name' => 'ASC',
            ],
            'conditions' => $conditions,
            'joins'      => [
                [
                    'table'      => 'containers',
                    'alias'      => 'Container',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'Servicegroup.container_id = Container.id',
                    ],
                ],
                [
                    'table'      => 'services_to_servicegroups',
                    'alias'      => 'servicesToServicegroups',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'servicesToServicegroups.servicegroup_id = Servicegroup.id',
                    ],
                ],
                [
                    'table'      => 'services',
                    'alias'      => 'Service',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'Service.id = servicesToServicegroups.service_id',
                    ],
                ],
                [
                    'table'      => 'servicetemplates',
                    'alias'      => 'Servicetemplate',
                    'type'       => 'INNER',
                    'conditions' => [
                        'Servicetemplate.id = Service.servicetemplate_id',
                    ],
                ],
                [
                    'table'      => 'hosts',
                    'alias'      => 'Host',
                    'type'       => 'INNER',
                    'conditions' => [
                        'Host.id = Service.host_id',
                    ],
                ],
            ],
            'fields'     => [
                'Container.name',
                'Servicegroup.description',
                'Servicegroup.id',
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.host_id',
                'Servicetemplate.name',
                'Host.id',
                'Host.name',
            ],
        ]);

        $serviceUuids = Hash::extract($servicegroups, '{n}.Service.uuid');
        $servicestatus = $this->Objects->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'name2'         => $serviceUuids,
                'objecttype_id' => 2,
            ],
            'fields'     => [
                'Servicestatus.current_state',
                'Servicestatus.is_flapping',
                'Servicestatus.last_state_change',
                'Servicestatus.problem_has_been_acknowledged',
                'Servicestatus.scheduled_downtime_depth',
                'Servicestatus.last_check',
                'Servicestatus.next_check',
                'Servicestatus.output',
                'Service.id',
            ],
            'joins'      => [
                [
                    'table'      => 'services',
                    'alias'      => 'Service',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'Service.uuid = Objects.name2',
                    ],
                ],
                [
                    'table'      => 'nagios_servicestatus',
                    'type'       => 'LEFT',
                    'alias'      => 'Servicestatus',
                    'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                ],
            ],
        ]);
        $servicestatus = Hash::combine($servicestatus, '{n}.Service.id', '{n}.Servicestatus');
        $servicegroupsAndContainers = Set::combine($servicegroups, '{n}.Servicegroup.id', '{n}.{^(Servicegroup|Container)$}');
        $servicesByServicegroupId = Set::combine($servicegroups, '{n}.Service.id', '{n}.{^(Service|Servicetemplate|Host)$}', '{n}.Servicegroup.id');
        $hosts = Hash::combine($servicegroups, '{n}.Service.id', '{n}.Host');

        $servicegroupstatus = [];
        foreach ($servicegroupsAndContainers as $servicegroupId => $servicegroup) {

            $currentHosts = [];
            foreach ($servicesByServicegroupId[$servicegroupId] as $serviceId => $service) {
                //$service['Status'] = $servicestatus[$serviceId];
                $host = $hosts[$serviceId];
                $hostId = $host['id'];
                if (!array_key_exists($hostId, $currentHosts)) {
                    $currentHosts[$hostId] = $host;
                }
                $data = [
                    'Service'         => $service['Service'],
                    'Servicetemplate' => $service['Servicetemplate'],
                    'Servicename'     => (!isset($service['Service']['name']) ? $service['Servicetemplate']['name'] : $service['Service']['name']),
                    'Status'          => $servicestatus[$serviceId],
                ];
                $currentHosts[$hostId]['Services'][] = $data;
            }
            $servicegroup['elements'] = $currentHosts;
            $servicegroupstatus[] = $servicegroup;
        }

        //counter
        $servicegroupCount = count($servicegroupstatus);
        $hostCount = Hash::apply($servicegroupstatus, '{n}.elements.{n}', 'count');
        $serviceCount = Hash::apply($servicegroupstatus, '{n}.elements.{n}.Services.{n}', 'count');

        $this->set(compact('servicegroupstatus', 'servicegroupCount', 'hostCount', 'serviceCount'));

        $filename = 'Servicegroups_'.strtotime('now').'.pdf';
        $binary_path = '/usr/bin/wkhtmltopdf';
        if (file_exists('/usr/local/bin/wkhtmltopdf')) {
            $binary_path = '/usr/local/bin/wkhtmltopdf';
        }
        $this->pdfConfig = [
            'engine'             => 'CakePdf.WkHtmlToPdf',
            'margin'             => [
                'bottom' => 15,
                'left'   => 0,
                'right'  => 0,
                'top'    => 15,
            ],
            'encoding'           => 'UTF-8',
            'download'           => true,
            'binary'             => $binary_path,
            'orientation'        => 'portrait',
            'filename'           => $filename,
            'no-pdf-compression' => '*',
            'image-dpi'          => '900',
            'background'         => true,
            'no-background'      => false,
        ];
    }
}
