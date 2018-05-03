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
 * @property Serviceescalation                       $Serviceescalation
 * @property Timeperiod                              $Timeperiod
 * @property Service                                 $Service
 * @property Servicegroup                            $Servicegroup
 * @property Contact                                 $Contact
 * @property Contactgroup                            $Contactgroup
 * @property ServiceescalationServiceMembership      $ServiceescalationServiceMembership
 * @property ServiceescalationServicegroupMembership $ServiceescalationServicegroupMembership
 * @property Container                               $Container
 * @property Host                                    $Host
 */
class ServiceescalationsController extends AppController
{
    public $uses = [
        'Serviceescalation',
        'Timeperiod',
        'Service',
        'Servicegroup',
        'Contact',
        'Contactgroup',
        'ServiceescalationServiceMembership',
        'ServiceescalationServicegroupMembership',
        'Container',
        'Host',
    ];
    public $layout = 'Admin.default';
    public $components = [
        'ListFilter.ListFilter',
        'RequestHandler',
        'CustomValidationErrors',
    ];
    public $helpers = ['ListFilter.ListFilter', 'CustomValidationErrors'];

    public function index()
    {

        $options = [
            'recursive'  => -1,
            'conditions' => [
                'Serviceescalation.container_id' => $this->MY_RIGHTS,
            ],
            'contain'    => [
                'ServiceescalationServiceMembership'      => [
                    'Service' => [
                        'fields'          => [
                            'id',
                            'name',
                            'disabled'
                        ],
                        'Servicetemplate' => [
                            'fields' => [
                                'name',
                            ],
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
                'Contact'                                 => 'name',
                'Contactgroup'                            => [
                    'Container' => [
                        'fields' => 'name',
                    ],
                ],
                'ServiceescalationServicegroupMembership' => [
                    'Servicegroup' => [
                        'Container' => [
                            'fields' => 'name',
                        ],
                    ],
                ],
                'Timeperiod'                              => [
                    'fields' => [
                        'id',
                        'name',
                    ],
                ],
            ],
        ];

        $query = Hash::merge($this->Paginator->settings, $options);

        if ($this->isApiRequest()) {
            unset($query['limit']);
            $all_serviceescalations = $this->Serviceescalation->find('all', $query);
        } else {
            $this->Paginator->settings = $query;
            $all_serviceescalations = $this->Paginator->paginate();
        }

        $this->set('all_serviceescalations', $all_serviceescalations);
        $this->set('_serialize', ['all_serviceescalations']);
    }

    public function view($id = null)
    {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();

        }
        if (!$this->Serviceescalation->exists($id)) {
            throw new NotFoundException(__('Invalid serviceescalation'));
        }
        $serviceescalation = $this->Serviceescalation->find('first', [
            'conditions' => [
                'Serviceescalation.id' => $id,
            ],
            'contain'    => [
                'ServiceescalationServiceMembership'      => [
                    'Service' => [
                        'Servicetemplate' => [
                            'fields' => [
                                'id',
                                'name',
                            ],
                        ],
                        'Host'            => [
                            'fields' => [
                                'id',
                                'name',
                            ],
                        ],
                    ],
                ],
                'Contact',
                'Contactgroup'                            => [
                    'Container',
                ],
                'ServiceescalationServicegroupMembership' => [
                    'Servicegroup' => [
                        'Container',
                    ],
                ],
                'Timeperiod',
            ],
        ]);
        if (!$this->allowedByContainerId($serviceescalation['Serviceescalation']['container_id'])) {
            $this->render403();

            return;
        }

        $this->set('serviceescalation', $serviceescalation);
        $this->set('_serialize', ['serviceescalation']);
    }

    public function edit($id = null)
    {
        if (!$this->Serviceescalation->exists($id)) {
            throw new NotFoundException(__('Invalid serviceescalation'));
        }
        $serviceescalation = $this->Serviceescalation->findById($id);
        if (!$serviceescalation) {
            throw new NotFoundException(__('Invalid serviceescalation'));
        }

        if (!$this->allowedByContainerId($serviceescalation['Serviceescalation']['container_id'])) {
            $this->render403();

            return;
        }

        $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_SERVICEESCALATION, [], $this->hasRootPrivileges);
        list($servicegroups, $services, $timeperiods, $contacts, $contactgroups) =$this->getAvailableDataByContainerId($serviceescalation['Serviceescalation']['container_id']);

        if ($this->request->is('post') || $this->request->is('put')) {
            $containerIds = $this->request->data('Serviceescalation.container_id');
            if ($containerIds > 0 && $containerIds != $serviceescalation['Serviceescalation']['container_id']) {
                // Container ID has been changed
                list($servicegroups, $services, $timeperiods, $contacts, $contactgroups) =
                    $this->getAvailableDataByContainerId($containerIds);
            }

            $this->request->data['Contact']['Contact'] = $this->request->data['Serviceescalation']['Contact'];
            $this->request->data['Contactgroup']['Contactgroup'] = $this->request->data['Serviceescalation']['Contactgroup'];
            $_services = (is_array($this->request->data('Serviceescalation.Service'))) ? $this->request->data['Serviceescalation']['Service'] : [];
            $services_excluded = (is_array($this->request->data('Serviceescalation.Service_excluded'))) ? $this->request->data['Serviceescalation']['Service_excluded'] : [];
            $this->request->data['ServiceescalationServiceMembership'] = $this->Serviceescalation->parseServiceMembershipData($_services, $services_excluded);

            $_servicegroups = (is_array($this->request->data('Serviceescalation.Servicegroup'))) ? $this->request->data['Serviceescalation']['Servicegroup'] : [];
            $servicegroups_excluded = (is_array($this->request->data('Serviceescalation.Servicegroup_excluded'))) ? $this->request->data['Serviceescalation']['Servicegroup_excluded'] : [];
            $this->request->data['ServiceescalationServicegroupMembership'] = [];
            $this->request->data['ServiceescalationServicegroupMembership'] = $this->Serviceescalation->parseServicegroupMembershipData($_servicegroups, $servicegroups_excluded);

            $this->Serviceescalation->set($this->request->data);
            $this->Serviceescalation->id = $id;

            if ($this->Serviceescalation->validates()) {
                $old_membership_services = $this->ServiceescalationServiceMembership->find('all', [
                    'conditions' => [
                        'ServiceescalationServiceMembership.serviceescalation_id' => $id,
                    ],
                    'recursive' => -1
                ]);
                /* Delete old service associations */
                foreach ($old_membership_services as $old_membership_service) {
                    $this->ServiceescalationServiceMembership->delete($old_membership_service['ServiceescalationServiceMembership']['id']);

                }
                $old_membership_servicegroups = $this->ServiceescalationServicegroupMembership->find('all', [
                    'conditions' => [
                        'ServiceescalationServicegroupMembership.serviceescalation_id' => $id],
                ]);
                /* Delete old servicegroup associations */
                foreach ($old_membership_servicegroups as $old_membership_servicegroup) {
                    $this->ServiceescalationServicegroupMembership->delete($old_membership_servicegroup['ServiceescalationServicegroupMembership']['id']);
                }
            }
            if ($this->Serviceescalation->saveAll($this->request->data)) {
                $this->setFlash(__('Serviceescalation successfully saved'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Serviceescalation could not be saved'), false);
            }
        } else {
            $serviceescalation['Serviceescalation']['Service'] = Hash::combine($serviceescalation['ServiceescalationServiceMembership'], '{n}[excluded=0].service_id', '{n}[excluded=0].service_id');
            $serviceescalation['Serviceescalation']['Service_excluded'] = Hash::combine($serviceescalation['ServiceescalationServiceMembership'], '{n}[excluded=1].service_id', '{n}[excluded=1].service_id');
            $serviceescalation['Serviceescalation']['Servicegroup'] = Hash::combine($serviceescalation['ServiceescalationServicegroupMembership'], '{n}[excluded=0].servicegroup_id', '{n}[excluded=0].servicegroup_id');
            $serviceescalation['Serviceescalation']['Servicegroup_excluded'] = Hash::combine($serviceescalation['ServiceescalationServicegroupMembership'], '{n}[excluded=1].servicegroup_id', '{n}[excluded=1].servicegroup_id');
            $serviceescalation['Serviceescalation']['Contact'] = Hash::extract($serviceescalation['Contact'], '{n}.id');
            $serviceescalation['Serviceescalation']['Contactgroup'] = Hash::extract($serviceescalation['Contactgroup'], '{n}.id');
        }

        $this->request->data = Hash::merge($serviceescalation, $this->request->data);

        $this->set([
            'serviceescalation' => $serviceescalation,
            'services'          => $services,
            'servicegroups'     => $servicegroups,
            'timeperiods'       => $timeperiods,
            'contactgroups'     => $contactgroups,
            'contacts'          => $contacts,
            'containers'        => $containers,
        ]);
    }

    public function add()
    {
        $services = [];
        $servicegroups = [];
        $contacts = [];
        $contactgroups = [];
        $timeperiods = [];

        $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_SERVICEESCALATION, [], $this->hasRootPrivileges);

        $customFieldsToRefill = [
            'Serviceescalation' => [
                'escalate_on_recovery',
                'escalate_on_warning',
                'escalate_on_critical',
                'escalate_on_unknown',
            ],
        ];
        $this->CustomValidationErrors->checkForRefill($customFieldsToRefill);
        $this->Frontend->set('data_placeholder', __('Please choose service'));
        $this->Frontend->set('data_placeholder_empty', __('No entries found'));

        if ($this->request->is('post') || $this->request->is('put')) {
            $necessaryKeys = [
                'Contact'      => [],
                'Contactgroup' => [],
            ];
            foreach ($necessaryKeys as $necessaryKey => $value) {
                if (!isset($this->request->data['Serviceescalation'][$necessaryKey])) {
                    $this->request->data['Serviceescalation'][$necessaryKey] = $value;
                }
            }
            App::uses('UUID', 'Lib');
            $this->request->data['Serviceescalation']['uuid'] = UUID::v4();
            $this->request->data['Contact']['Contact'] = $this->request->data['Serviceescalation']['Contact'];
            $this->request->data['Contactgroup']['Contactgroup'] = $this->request->data['Serviceescalation']['Contactgroup'];
            $_services = (is_array($this->request->data('Serviceescalation.Service'))) ? $this->request->data['Serviceescalation']['Service'] : [];
            $services_excluded = (is_array($this->request->data('Serviceescalation.Service_excluded'))) ? $this->request->data['Serviceescalation']['Service_excluded'] : [];
            $this->request->data['ServiceescalationServiceMembership'] = [];
            $this->request->data['ServiceescalationServiceMembership'] = $this->Serviceescalation->parseServiceMembershipData($_services, $services_excluded);

            $_servicegroups = (is_array($this->request->data('Serviceescalation.Servicegroup'))) ? $this->request->data['Serviceescalation']['Servicegroup'] : [];
            $servicegroups_excluded = (is_array($this->request->data('Serviceescalation.Servicegroup_excluded'))) ? $this->request->data['Serviceescalation']['Servicegroup_excluded'] : [];
            $this->request->data['ServiceescalationServicegroupMembership'] = [];
            $this->request->data['ServiceescalationServicegroupMembership'] = $this->Serviceescalation->parseServicegroupMembershipData($_servicegroups, $servicegroups_excluded);

            $this->Serviceescalation->set($this->request->data);
            $isJsonRequest = $this->request->ext === 'json';
            if ($this->Serviceescalation->saveAll($this->request->data)) {
                if ($isJsonRequest) {
                    $this->serializeId();

                    return;
                } else {
                    $this->setFlash(__('Serviceescalation successfully saved'));
                    $this->redirect(['action' => 'index']);
                }
            } else {
                if ($isJsonRequest) {
                    $this->serializeErrorMessage();

                    return;
                } else {
                    $this->setFlash(__('Serviceescalation could not be saved'), false);

                    $containerId = $this->request->data('Serviceescalation.container_id');
                    if ($containerId > 0) {
                        list($servicegroups, $services, $timeperiods, $contacts, $contactgroups) =
                            $this->getAvailableDataByContainerId($containerId);
                    }
                }
            }
        }
        $this->set([
            'services'      => $services,
            'servicegroups' => $servicegroups,
            'timeperiods'   => $timeperiods,
            'contactgroups' => $contactgroups,
            'contacts'      => $contacts,
            'containers'    => $containers,
        ]);
    }

    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Serviceescalation->exists($id)) {
            throw new NotFoundException(__('Invalid serviceescalation'));
        }
        $serviceescalation = $this->Serviceescalation->findById($id);

        if (!$this->allowedByContainerId($serviceescalation['Serviceescalation']['container_id'])) {
            $this->render403();

            return;
        }

        if ($this->Serviceescalation->delete($id)) {
            $this->setFlash(__('Serviceescalation deleted'));
            $this->redirect(['action' => 'index']);
        }
        $this->setFlash(__('Could not delete serviceescalation'), false);
        $this->redirect(['action' => 'index']);
    }

    public function loadElementsByContainerId($containerId = null)
    {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Container->exists($containerId)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        list($servicegroups, $services, $timeperiods, $contacts, $contactgroups) =
            $this->getAvailableDataByContainerId($containerId);

        $servicegroups = $this->Servicegroup->makeItJavaScriptAble($servicegroups);
        $services = $this->Service->makeItJavaScriptAble($services);
        $timeperiods = $this->Timeperiod->makeItJavaScriptAble($timeperiods);
        $contacts = $this->Contact->makeItJavaScriptAble($contacts);
        $contactgroups = $this->Contactgroup->makeItJavaScriptAble($contactgroups);

        $servicegroupsExcluded = $servicegroups;
        $servicesExcluded = $services;

        $data = [
            'services'              => $services,
            'servicesExcluded'      => $servicesExcluded,
            'servicegroups'         => $servicegroups,
            'servicegroupsExcluded' => $servicegroupsExcluded,
            'timeperiods'           => $timeperiods,
            'contacts'              => $contacts,
            'contactgroups'         => $contactgroups,
        ];
        $this->set($data);
        $this->set('_serialize', array_keys($data));
    }

    /**
     * @param int[] $containerIds
     *
     * @return array
     */
    protected function getAvailableDataByContainerId($containerIds)
    {
        $containerIds = $this->Tree->resolveChildrenOfContainerIds($containerIds);

        $servicegroups = $this->Servicegroup->servicegroupsByContainerId($containerIds, 'list', 'id');
        $services = $this->Host->servicesByContainerIds($containerIds, 'list', ['forOptiongroup' => true]);
        $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
        $contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
        $contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');

        return [$servicegroups, $services, $timeperiods, $contacts, $contactgroups];
    }
}
