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
 * @property Servicedependency                       $Servicedependency
 * @property Timeperiod                              $Timeperiod
 * @property Service                                 $Service
 * @property Servicegroup                            $Servicegroup
 * @property ServicedependencyServiceMembership      $ServicedependencyServiceMembership
 * @property ServicedependencyServicegroupMembership $ServicedependencyServicegroupMembership
 * @property Host                                    $Host
 * @property Container                               $Container
 * @property PaginatorComponent                      $Paginator
 * @property ListFilterComponent                     $ListFilter
 * @property RequestHandlerComponent                 $RequestHandler
 * @property CustomValidationErrorsComponent         $CustomValidationErrors
 */
class ServicedependenciesController extends AppController
{

    public $uses = [
        'Servicedependency',
        'Timeperiod',
        'Service',
        'Servicegroup',
        'ServicedependencyServiceMembership',
        'ServicedependencyServicegroupMembership',
        'Host',
        'Container',
    ];

    public $layout = 'Admin.default';
    public $components = [
        'ListFilter.ListFilter',
        'RequestHandler',
        'CustomValidationErrors',
    ];
    public $helpers = [
        'ListFilter.ListFilter',
        'CustomValidationErrors',
    ];

    public function index() {
        $options = [
            'recursive'  => -1,
            'conditions' => [
                'Servicedependency.container_id' => $this->MY_RIGHTS,
            ],
            'contain'    => [
                'ServicedependencyServiceMembership'      => [
                    'Service' => [
                        'fields'          => [
                            'name',
                            'disabled'
                        ],
                        'Servicetemplate' => [
                            'fields' => ['name'],
                        ],
                        'Host'            => [
                            'fields' => [
                                'id',
                                'name',
                                'disabled'
                            ],
                        ],
                    ],
                ],
                'ServicedependencyServicegroupMembership' => [
                    'Servicegroup' => [
                        'Container' => [
                            'fields' => 'name',
                        ],
                    ],
                ],
                'Timeperiod' => [
                    'fields' => 'name',
                ],
            ],
        ];

        $query = Hash::merge($this->Paginator->settings, $options);

        if ($this->isApiRequest) {
            unset($query['limit']);
            $all_servicedependencies = $this->Servicedependency->find('all', $query);
        } else {
            $this->Paginator->settings = $query;
            $all_servicedependencies = $this->Paginator->paginate();
        }


        $this->set('all_servicedependencies', $all_servicedependencies);
        $this->set('_serialize', ['all_servicedependencies']);
    }

    public function view($id = null)
    {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();

        }
        if (!$this->Servicedependency->exists($id)) {
            throw new NotFoundException(__('Invalid servicedependency'));
        }
        $servicedependency = $this->Servicedependency->findById($id);
        $serviceDependencyContainerId = $servicedependency['Servicedependency']['container_id'];
        if (!$this->allowedByContainerId($serviceDependencyContainerId)) {
            $this->render403();

            return;
        }

        $this->set('servicedependency', $servicedependency);
        $this->set('_serialize', ['servicedependency']);
    }

    public function edit($id = null)
    {
        if (!$this->Servicedependency->exists($id)) {
            throw new NotFoundException(__('Invalid servicedependency'));
        }
        $servicedependency = $this->Servicedependency->findById($id);
        if (!$servicedependency) {
            throw new NotFoundException(__('Invalid servicedependency'));
        }

        $serviceDependencyContainerId = $servicedependency['Servicedependency']['container_id'];
        if (!$this->allowedByContainerId($serviceDependencyContainerId)) {
            $this->render403();

            return;
        }
        $serviceDependencyContainerIds = $this->Tree->resolveChildrenOfContainerIds($serviceDependencyContainerId);

        $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_SERVICEDEPENDENCY, [], $this->hasRootPrivileges);
        $servicegroups = $this->Servicegroup->servicegroupsByContainerId($serviceDependencyContainerIds, 'list', 'id');

        $services = $this->Host->servicesByContainerIds($serviceDependencyContainerIds, 'list', [
            'prefixHostname' => true,
            'delimiter'      => '/',
            'forOptiongroup' => true,
        ]);

        $timeperiods = $this->Timeperiod->timeperiodsByContainerId($serviceDependencyContainerIds, 'list');

        if ($this->request->is('post') || $this->request->is('put')) {
            $containerId = $this->request->data('Servicedependency.container_id');
            if ($containerId > 0 && $containerId != $serviceDependencyContainerId) {
                // If the container ID has been changed, fill the variables

                $containerIds = $this->Tree->resolveChildrenOfContainerIds($containerId);
                $services = $this->Host->servicesByContainerIds($containerIds, 'list', [
                    'prefixHostname' => true,
                    'delimiter'      => '/',
                    'forOptiongroup' => true,
                ]);

                $servicegroups = $this->Servicegroup->servicegroupsByContainerId($containerIds, 'list', 'id');
                $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
            }

            $_services = (is_array($this->request->data('Servicedependency.Service'))) ? $this->request->data['Servicedependency']['Service'] : [];
            $dependent_services = (is_array($this->request->data('Servicedependency.ServiceDependent'))) ? $this->request->data['Servicedependency']['ServiceDependent'] : [];
            $this->request->data['ServicedependencyServiceMembership'] = $this->Servicedependency->parseServiceMembershipData($_services, $dependent_services);

            $_servicegroups = (is_array($this->request->data('Servicedependency.Servicegroup'))) ? $this->request->data['Servicedependency']['Servicegroup'] : [];
            $dependent_servicegroups = (is_array($this->request->data('Servicedependency.ServicegroupDependent'))) ? $this->request->data['Servicedependency']['ServicegroupDependent'] : [];
            $this->request->data['ServicedependencyServicegroupMembership'] = $this->Servicedependency->parseServicegroupMembershipData($_servicegroups, $dependent_servicegroups);


            $this->Servicedependency->set($this->request->data);
            $this->Servicedependency->id = $id;

            if ($this->Servicedependency->validates()) {
                $old_membership_services = $this->ServicedependencyServiceMembership->find('all', [
                    'conditions' => [
                        'ServicedependencyServiceMembership.servicedependency_id' => $id],
                ]);
                /* Delete old service associations */
                foreach ($old_membership_services as $old_membership_service) {
                    $this->ServicedependencyServiceMembership->delete($old_membership_service['ServicedependencyServiceMembership']['id']);
                }
                $old_membership_servicegroups = $this->ServicedependencyServicegroupMembership->find('all', [
                    'conditions' => [
                        'ServicedependencyServicegroupMembership.servicedependency_id' => $id],
                ]);
                /* Delete old servicegroup associations */
                foreach ($old_membership_servicegroups as $old_membership_servicegroup) {
                    $this->ServicedependencyServicegroupMembership->delete($old_membership_servicegroup['ServicedependencyServicegroupMembership']['id']);
                }
            }

            if ($this->Servicedependency->saveAll($this->request->data)) {
                $this->setFlash(__('Servicedependency successfully saved'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Servicedependency could not be saved'), false);
            }
        } else {
            $servicedependency['Servicedependency']['Service'] = Hash::combine($servicedependency['ServicedependencyServiceMembership'], '{n}[dependent=0].service_id', '{n}[dependent=0].service_id');
            $servicedependency['Servicedependency']['ServiceDependent'] = Hash::combine($servicedependency['ServicedependencyServiceMembership'], '{n}[dependent=1].service_id', '{n}[dependent=1].service_id');
            $servicedependency['Servicedependency']['Servicegroup'] = Hash::combine($servicedependency['ServicedependencyServicegroupMembership'], '{n}[dependent=0].servicegroup_id', '{n}[dependent=0].servicegroup_id');
            $servicedependency['Servicedependency']['ServicegroupDependent'] = Hash::combine($servicedependency['ServicedependencyServicegroupMembership'], '{n}[dependent=1].servicegroup_id', '{n}[dependent=1].servicegroup_id');
        }

        $this->request->data = Hash::merge($servicedependency, $this->request->data);
        $this->set(compact(['servicedependency', 'services', 'servicegroups', 'timeperiods', 'containers']));
    }

    public function add()
    {
        $customFieldsToRefill = [
            'Servicedependency' => [
                'execution_fail_on_ok',
                'execution_fail_on_warning',
                'execution_fail_on_critical',
                'execution_fail_on_unknown',
                'execution_fail_on_pending',
                'execution_none',
                'notification_fail_on_ok',
                'notification_fail_on_warning',
                'notification_fail_on_critical',
                'notification_fail_on_unknown',
                'notification_fail_on_pending',
                'notification_none',
                'inherits_parent',
            ],
        ];
        $this->CustomValidationErrors->checkForRefill($customFieldsToRefill);
        $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_SERVICEDEPENDENCY, [], $this->hasRootPrivileges);
        $services = [];
        $servicegroups = [];
        $timeperiods = [];
        $this->Frontend->set('data_placeholder', __('Please, start typing...'));
        $this->Frontend->set('data_placeholder_empty', __('No entries found'));

        if ($this->request->is('post') || $this->request->is('put')) {
            App::uses('UUID', 'Lib');
            $this->request->data['Servicedependency']['uuid'] = UUID::v4();
            $_services = (is_array($this->request->data('Servicedependency.Service'))) ? $this->request->data['Servicedependency']['Service'] : [];
            $dependent_services = (is_array($this->request->data('Servicedependency.ServiceDependent'))) ? $this->request->data['Servicedependency']['ServiceDependent'] : [];
            $this->request->data['ServicedependencyServiceMembership'] = $this->Servicedependency->parseServiceMembershipData($_services, $dependent_services);

            $_servicegroups = (is_array($this->request->data('Servicedependency.Servicegroup'))) ? $this->request->data['Servicedependency']['Servicegroup'] : [];
            $dependent_servicegroups = (is_array($this->request->data('Servicedependency.ServicegroupDependent'))) ? $this->request->data['Servicedependency']['ServicegroupDependent'] : [];
            $this->request->data['ServicedependencyServicegroupMembership'] = $this->Servicedependency->parseServicegroupMembershipData($_servicegroups, $dependent_servicegroups);

            $isJsonRequest = $this->request->ext === 'json';
            if ($this->Servicedependency->saveAll($this->request->data)) {
                if ($isJsonRequest) {
                    $this->serializeId();
                    return;
                } else {
                    $this->setFlash(__('Servicedependency successfully saved'));
                    $this->redirect(['action' => 'index']);
                }
            } else {
                if ($isJsonRequest) {
                    $this->serializeErrorMessage();
                    return;
                } else {
                    $this->setFlash(__('Servicedependency could not be saved'), false);

                    $containerId = $this->request->data('Servicedependency.container_id');
                    if ($containerId > 0) {
                        $containerIds = $this->Tree->resolveChildrenOfContainerIds($containerId);
                        $services = $this->Host->servicesByContainerIds($containerIds, 'list', [
                            'forOptiongroup' => true,
                        ]);
                        $servicegroups = $this->Servicegroup->servicegroupsByContainerId($containerIds, 'list', 'id');
                        $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
                    }
                }
            }
        }

        $this->set([
            'services'      => $services,
            'servicegroups' => $servicegroups,
            'timeperiods'   => $timeperiods,
            'containers'    => $containers,
        ]);
    }

    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Servicedependency->exists($id)) {
            throw new NotFoundException(__('Invalid servicedependency'));
        }
        $servicedependency = $this->Servicedependency->findById($id);

        if (!$this->allowedByContainerId($servicedependency['Servicedependency']['container_id'])) {
            $this->render403();

            return;
        }

        if ($this->Servicedependency->delete($id)) {
            $this->setFlash(__('Servicedependency deleted'));
            $this->redirect(['action' => 'index']);
        }
        $this->setFlash(__('Could not delete servicedependency'), false);
        $this->redirect(['action' => 'index']);
    }

    public function loadElementsByContainerId($containerId = null)
    {
        $this->allowOnlyAjaxRequests();

        if (!$this->Container->exists($containerId)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($containerId,
            false,
            $this->Constants->containerProperties(OBJECT_HOST, CT_HOSTGROUP)
        );
        $servicegroups = $this->Servicegroup->servicegroupsByContainerId($containerIds, 'list', 'id');
        $services = $this->Host->servicesByContainerIds($containerIds, 'list', [
            'forOptiongroup' => true
        ]);
        $timeperiodContainerIds = $this->Tree->resolveChildrenOfContainerIds($containerId,
            false,
            $this->Constants->containerProperties(OBJECT_TIMEPERIOD)
        );
        $timeperiods = $this->Timeperiod->timeperiodsByContainerId($timeperiodContainerIds, 'list');

        $servicegroups = $this->Servicegroup->makeItJavaScriptAble($servicegroups);
        $servicegroupsDependent = $servicegroups;
        $services = $this->Service->makeItJavaScriptAble($services);
        $servicesDependent = $services;
        $timeperiods = $this->Timeperiod->makeItJavaScriptAble($timeperiods);

        $data = [
            'services'               => $services,
            'servicesDependent'      => $servicesDependent,
            'servicegroups'          => $servicegroups,
            'servicegroupsDependent' => $servicegroupsDependent,
            'timeperiods'            => $timeperiods,
        ];
        $this->set($data);
        $this->set('_serialize', array_keys($data));
    }
}
