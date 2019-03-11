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
use App\Model\Table\ContactgroupsTable;
use App\Model\Table\ContactsTable;
use App\Model\Table\ContainersTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Database\ScrollIndex;

/**
 * @property Serviceescalation $Serviceescalation
 * @property Timeperiod $Timeperiod
 * @property Service $Service
 * @property Servicegroup $Servicegroup
 * @property Contact $Contact
 * @property Contactgroup $Contactgroup
 * @property ServiceescalationServiceMembership $ServiceescalationServiceMembership
 * @property ServiceescalationServicegroupMembership $ServiceescalationServicegroupMembership
 * @property Container $Container
 * @property Host $Host
 */
class ServiceescalationsController extends AppController {
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

    public function index() {
        $this->layout = 'blank';

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

        if (isset($this->request->query['page'])) {
            $this->Paginator->settings['page'] = $this->request->query['page'];
        }
        $query = Hash::merge($this->Paginator->settings, $options);

        if (!$this->isApiRequest()) {
            /*$this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $all_serviceescalations = $this->Paginator->paginate();
            $this->set('all_serviceescalations', $all_serviceescalations);
            $this->set('_serialize', ['all_serviceescalations']);*/
            return;
        }

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            if (isset($query['limit'])) {
                unset($query['limit']);
            }
            $all_serviceescalations = $this->Serviceescalation->find('all', $query);
            $this->set('all_serviceescalations', $all_serviceescalations);
            $this->set('_serialize', ['all_serviceescalations']);
            return;
        } else {
            if ($this->isScrollRequest()) {
                $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
                $ScrollIndex = new ScrollIndex($this->Paginator, $this);
                $all_serviceescalations = $this->Serviceescalation->find('all', array_merge($this->Paginator->settings, $query));
                $ScrollIndex->determineHasNextPage($all_serviceescalations);
                $ScrollIndex->scroll();
            } else {
                $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
                $all_serviceescalations = $this->Paginator->paginate("Serviceescalation", []);
            }
        }

        foreach ($all_serviceescalations as $key => $serviceescalation) {
            $all_serviceescalations[$key]['Serviceescalation']['allowEdit'] = $this->isWritableContainer($serviceescalation['Serviceescalation']['container_id']);
        }

        $this->set('all_serviceescalations', $all_serviceescalations);
        $toJson = ['all_serviceescalations', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_serviceescalations', 'scroll'];
        }

        $this->set('_serialize', $toJson);
    }

    public function view($id = null) {
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

    public function edit($id = null) {
        $this->layout = 'blank';
        if (!$this->isAngularJsRequest() && $id === null) {
            return;
        }

        if (!$this->Serviceescalation->exists($id) && $id !== null) {
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

        $serviceescalation['Serviceescalation']['id'] = intval($serviceescalation['Serviceescalation']['id']);
        $serviceescalation['Serviceescalation']['container_id'] = intval($serviceescalation['Serviceescalation']['container_id']);
        $serviceescalation['Serviceescalation']['timeperiod_id'] = intval($serviceescalation['Serviceescalation']['timeperiod_id']);
        $serviceescalation['Serviceescalation']['first_notification'] = intval($serviceescalation['Serviceescalation']['first_notification']);
        $serviceescalation['Serviceescalation']['last_notification'] = intval($serviceescalation['Serviceescalation']['last_notification']);
        $serviceescalation['Serviceescalation']['notification_interval'] = intval($serviceescalation['Serviceescalation']['notification_interval']);
        $serviceescalation['Serviceescalation']['escalate_on_recovery'] = intval($serviceescalation['Serviceescalation']['escalate_on_recovery']);
        $serviceescalation['Serviceescalation']['escalate_on_warning'] = intval($serviceescalation['Serviceescalation']['escalate_on_warning']);
        $serviceescalation['Serviceescalation']['escalate_on_critical'] = intval($serviceescalation['Serviceescalation']['escalate_on_critical']);
        $serviceescalation['Serviceescalation']['escalate_on_unknown'] = intval($serviceescalation['Serviceescalation']['escalate_on_unknown']);


        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');

        $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_SERVICEESCALATION, [], $this->hasRootPrivileges);
        $containers = Api::makeItJavaScriptAble($containers);

        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($serviceescalation['Serviceescalation']['container_id']);
        $servicegroups = Api::makeItJavaScriptAble($this->Servicegroup->servicegroupsByContainerId($containerIds, 'list', 'id'));
        $servicegroupsExcluded = $servicegroups;
        $services = Api::makeItJavaScriptAble($this->Service->servicesByHostContainerIds($containerIds, 'list'));
        $servicesExcluded = $services;
        $timeperiods = Api::makeItJavaScriptAble($TimeperiodsTable->timeperiodsByContainerId($containerIds, 'list'));

        $contacts = Api::makeItJavaScriptAble($ContactsTable->contactsByContainerId($containerIds, 'list'));
        $contactgroups = Api::makeItJavaScriptAble($ContactgroupsTable->getContactgroupsByContainerId($containerIds, 'list', 'id'));


        if ($this->request->is('post') || $this->request->is('put')) {
            $containerIds = $this->request->data('Serviceescalation.container_id');
            if ($containerIds > 0 && $containerIds != $serviceescalation['Serviceescalation']['container_id']) {
                // Container ID has been changed
                $containerIds = $ContainersTable->resolveChildrenOfContainerIds($this->request->data('Serviceescalation.container_id'));
                $servicegroups = Api::makeItJavaScriptAble($this->Servicegroup->servicegroupsByContainerId($containerIds, 'list', 'id'));
                $services = Api::makeItJavaScriptAble($this->Service->servicesByHostContainerIds($containerIds, 'list'));
                $timeperiods = Api::makeItJavaScriptAble($TimeperiodsTable->timeperiodsByContainerId($containerIds, 'list'));
                $contacts = Api::makeItJavaScriptAble($ContactsTable->contactsByContainerId($containerIds, 'list'));
                $contactgroups = Api::makeItJavaScriptAble($ContactgroupsTable->getContactgroupsByContainerId($containerIds, 'list', 'id'));
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
                    'recursive'  => -1
                ]);
                /* Delete old service associations */
                foreach ($old_membership_services as $old_membership_service) {
                    $this->ServiceescalationServiceMembership->delete($old_membership_service['ServiceescalationServiceMembership']['id']);

                }
                $old_membership_servicegroups = $this->ServiceescalationServicegroupMembership->find('all', [
                    'conditions' => [
                        'ServiceescalationServicegroupMembership.serviceescalation_id' => $id
                    ],
                ]);
                /* Delete old servicegroup associations */
                foreach ($old_membership_servicegroups as $old_membership_servicegroup) {
                    $this->ServiceescalationServicegroupMembership->delete($old_membership_servicegroup['ServiceescalationServicegroupMembership']['id']);
                }
            }
            if ($this->Serviceescalation->saveAll($this->request->data)) {
                $this->serializeId();
            } else {
                $this->serializeErrorMessage();
            }
        } else {
            $serviceescalation['Serviceescalation']['Service'] = array_map('intval', array_values(Hash::combine($serviceescalation['ServiceescalationServiceMembership'], '{n}[excluded=0].service_id', '{n}[excluded=0].service_id')));
            $serviceescalation['Serviceescalation']['Service_excluded'] = array_map('intval', array_values(Hash::combine($serviceescalation['ServiceescalationServiceMembership'], '{n}[excluded=1].service_id', '{n}[excluded=1].service_id')));
            $serviceescalation['Serviceescalation']['Servicegroup'] = array_map('intval', array_values(Hash::combine($serviceescalation['ServiceescalationServicegroupMembership'], '{n}[excluded=0].servicegroup_id', '{n}[excluded=0].servicegroup_id')));
            $serviceescalation['Serviceescalation']['Servicegroup_excluded'] = array_map('intval', array_values(Hash::combine($serviceescalation['ServiceescalationServicegroupMembership'], '{n}[excluded=1].servicegroup_id', '{n}[excluded=1].servicegroup_id')));
            $serviceescalation['Serviceescalation']['Contact'] = array_map('intval', array_values(Hash::extract($serviceescalation['Contact'], '{n}.id')));
            $serviceescalation['Serviceescalation']['Contactgroup'] = array_map('intval', array_values(Hash::extract($serviceescalation['Contactgroup'], '{n}.id')));
        }

        $this->request->data = Hash::merge($serviceescalation, $this->request->data);

        $this->set([
            'serviceescalation'     => $serviceescalation,
            'containers'            => $containers,
            'services'              => $services,
            'servicesExcluded'      => $servicesExcluded,
            'servicegroups'         => $servicegroups,
            'servicegroupsExcluded' => $servicegroupsExcluded,
            'timeperiods'           => $timeperiods,
            'contactgroups'         => $contactgroups,
            'contacts'              => $contacts,
        ]);
        $this->set('_serialize', ['serviceescalation', 'containers', 'services', 'servicesExcluded', 'servicegroups', 'servicegroupsExcluded', 'timeperiods', 'contactgroups', 'contacts']);
    }

    public function add() {
        $this->layout = 'blank';
        if (!$this->isAngularJsRequest()) {
            return;
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_SERVICEESCALATION, [], $this->hasRootPrivileges);
        $containers = Api::makeItJavaScriptAble($containers);

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
                $this->serializeId();
                return;
            } else {
                $this->serializeErrorMessage();
                return;
            }
        }

        $this->set(compact(['containers', 'services', 'servicegroups', 'timeperiods', 'contactgroups', 'contacts']));
        $this->set('_serialize', ['containers', 'services', 'servicegroups', 'timeperiods', 'contactgroups', 'contacts']);
    }

    public function delete($id = null) {
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
            $this->set('message', __('Serviceescalation deleted'));
            $this->set('_serialize', ['message']);
        }
        $this->set('message', __('Could not delete serviceescalation'));
        $this->set('_serialize', ['message']);
    }

    public function loadElementsByContainerId($containerId = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException(__('This is only allowed via API.'));
            return;
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if (!$ContainersTable->existsById($containerId)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        list($servicegroups, $services, $timeperiods, $contacts, $contactgroups) =
            $this->getAvailableDataByContainerId($containerId);


        $servicegroups = Api::makeItJavaScriptAble($servicegroups);
        $services = Api::makeItJavaScriptAble($services);
        $timeperiods = Api::makeItJavaScriptAble($timeperiods);
        $contacts = Api::makeItJavaScriptAble($contacts);
        $contactgroups = Api::makeItJavaScriptAble($contactgroups);

        foreach($services as $key => $service){
            $services[$key]['group'] = array_keys($service['value'])[0];
            $innerValueArray = $service['value'][array_keys($service['value'])[0]];
            $services[$key]['key'] = array_keys($innerValueArray)[0];
            $services[$key]['value'] = $innerValueArray[$services[$key]['key']];
        }

        $servicegroupsExcluded = $servicegroups;
        $servicesExcluded = $services;

        $this->set([
            'services'              => $services,
            'servicesExcluded'      => $servicesExcluded,
            'servicegroups'         => $servicegroups,
            'servicegroupsExcluded' => $servicegroupsExcluded,
            'timeperiods'           => $timeperiods,
            'contacts'              => $contacts,
            'contactgroups'         => $contactgroups,
        ]);
        $this->set('_serialize', ['services', 'servicesExcluded', 'servicegroups', 'servicegroupsExcluded', 'timeperiods', 'contacts', 'contactgroups']);
    }

    /**
     * @param int[] $containerIds
     *
     * @return array
     */
    protected function getAvailableDataByContainerId($containerIds) {
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');

        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerIds);

        $servicegroups = $this->Servicegroup->servicegroupsByContainerId($containerIds, 'list', 'id');
        $services = $this->Host->servicesByContainerIds($containerIds, 'list', ['forOptiongroup' => true]);
        $timeperiods = $TimeperiodsTable->find('list')
            ->where(['Timeperiods.container_id IN' => $containerIds])
            ->toArray();
        $contacts = $ContactsTable->contactsByContainerId($containerIds, 'list');
        $contactgroups = $ContactgroupsTable->getContactgroupsByContainerId($containerIds, 'list', 'id');

        return [$servicegroups, $services, $timeperiods, $contacts, $contactgroups];
    }
}
