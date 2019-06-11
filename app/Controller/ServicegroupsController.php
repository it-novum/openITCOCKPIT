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

use App\Model\Table\ContainersTable;
use App\Model\Table\HostsTable;
use App\Model\Table\ServicegroupsTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplatesTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\KeyValueStore;
use itnovum\openITCOCKPIT\Core\ServicegroupConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Core\Views\PerfdataChecker;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\ServicegroupFilter;
use itnovum\openITCOCKPIT\Filter\ServicetemplateFilter;
use itnovum\openITCOCKPIT\Monitoring\QueryHandler;


/**
 * Class ServicegroupsController
 *
 * @property AppPaginatorComponent $Paginator
 */
class ServicegroupsController extends AppController {

    public $uses = [
        'Servicegroup',
        MONITORING_OBJECTS,
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS,
    ];

    public $layout = 'blank';


    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        /** @var $ServicegroupsTable ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

        $MY_RIGHTS = [];
        if (!$this->hasRootPrivileges) {
            $MY_RIGHTS = $this->MY_RIGHTS;
        }

        $ServicegroupFilter = new ServicegroupFilter($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $ServicegroupFilter->getPage());
        $servicegroups = $ServicegroupsTable->getServicegroupsIndex($ServicegroupFilter, $PaginateOMat, $MY_RIGHTS);


        foreach ($servicegroups as $index => $servicegroup) {
            if ($this->hasRootPrivileges) {
                $servicegroups[$index]['allow_edit'] = true;
            } else {
                $servicegroups[$index]['allow_edit'] = $this->allowedByContainerId(
                    $servicegroup['container']['parent_id']
                );
            }
        }

        $this->set('all_servicegroups', $servicegroups);
        $toJson = ['all_servicegroups', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_servicegroups', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    /**
     * @param int|null $id
     */
    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ServicegroupsTable ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

        if (!$ServicegroupsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Servicegroup'));
        }

        $servicegroup = $ServicegroupsTable->get($id, [
            'contain' => [
                'Containers'
            ]
        ]);

        if (!$this->allowedByContainerId($servicegroup->get('container')->get('parent_id'))) {
            $this->render403();
            return;
        }

        $this->set('servicegroup', $servicegroup);
        $this->set('_serialize', ['servicegroup']);
    }


    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }


        if ($this->request->is('post')) {
            $User = new User($this->Auth);

            /** @var $ServicegroupsTable ServicegroupsTable */
            $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');
            $this->request->data['Servicegroup']['uuid'] = UUID::v4();
            $this->request->data['Servicegroup']['container']['containertype_id'] = CT_SERVICEGROUP;
            $servicegroup = $ServicegroupsTable->newEntity();
            $servicegroup = $ServicegroupsTable->patchEntity($servicegroup, $this->request->data('Servicegroup'));

            $ServicegroupsTable->save($servicegroup);
            if ($servicegroup->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $servicegroup->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors

                $extDataForChangelog = $ServicegroupsTable->resolveDataForChangelog($this->request->data);
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'add',
                    'servicegroups',
                    $servicegroup->get('id'),
                    OBJECT_SERVICEGROUP,
                    $servicegroup->get('container')->get('parent_id'),
                    $User->getId(),
                    $servicegroup->get('container')->get('name'),
                    array_merge($this->request->data, $extDataForChangelog)
                );

                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                //@todo refactor with cake4
                Cache::clear(false, 'permissions');

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($servicegroup); // REST API ID serialization
                    return;
                }
            }
            $this->set('servicegroup', $servicegroup);
            $this->set('_serialize', ['servicegroup']);
        }
    }


    /**
     * @param int|null $id
     */
    public function edit($id = null) {
        if (!$this->isApiRequest() && $id === null) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $ServicegroupsTable ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

        if (!$ServicegroupsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Servicegroup'));
        }

        $servicegroup = $ServicegroupsTable->getServicegroupForEdit($id);
        $servicegroupForChangelog = $servicegroup;

        if (!$this->allowedByContainerId($servicegroup['Servicegroup']['container']['parent_id'])) {
            $this->render403();
            return;
        }

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return host group information
            $this->set('servicegroup', $servicegroup);
            $this->set('_serialize', ['servicegroup']);
            return;
        }

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            //Update contact data
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            $servicegroupEntity = $ServicegroupsTable->get($id);

            $servicegroupEntity->setAccess('uuid', false);
            $servicegroupEntity = $ServicegroupsTable->patchEntity($servicegroupEntity, $this->request->data('Servicegroup'));
            $servicegroupEntity->id = $id;
            $ServicegroupsTable->save($servicegroupEntity);
            if ($servicegroupEntity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $servicegroupEntity->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors

                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'edit',
                    'servicegroups',
                    $servicegroupEntity->id,
                    OBJECT_SERVICEGROUP,
                    $servicegroupEntity->get('container')->get('parent_id'),
                    $User->getId(),
                    $servicegroupEntity->get('container')->get('name'),
                    array_merge($ServicegroupsTable->resolveDataForChangelog($this->request->data), $this->request->data),
                    array_merge($ServicegroupsTable->resolveDataForChangelog($servicegroupForChangelog), $servicegroupForChangelog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($servicegroupEntity); // REST API ID serialization
                    return;
                }
            }
            $this->set('servicegroup', $servicegroupEntity);
            $this->set('_serialize', ['servicegroup']);
        }
    }

    /**
     * @param null $id
     * @deprecated
     */
    public function delete($id = null) {
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

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($ContainersTable->delete($ContainersTable->get($container['Servicegroup']['container_id']))) {
            Cache::clear(false, 'permissions');
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

    public function addServicesToServicegroup() {
        //Only ship template
        return;
    }

    public function append() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        if ($this->request->is('post')) {
            $id = $this->request->data('Servicegroup.id');
            $serviceIds = $this->request->data('Servicegroup.services._ids');
            if (!is_array($serviceIds)) {
                $serviceIds = [$serviceIds];
            }

            if (empty($serviceIds)) {
                //No services to add
                return;
            }

            /** @var $ServicegroupsTable ServicegroupsTable */
            $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');
            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
            /** @var $HostsTable HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
            /** @var $ServicesTable ServicesTable */
            $ServicesTable = TableRegistry::getTableLocator()->get('Services');

            if (!$ServicegroupsTable->existsById($id)) {
                throw new NotFoundException(__('Invalid Servicegroup'));
            }

            $servicegroup = $ServicegroupsTable->getServicegroupForEdit($id);
            $servicegroupForChangelog = $servicegroup;
            if (!$this->allowedByContainerId($servicegroup['Servicegroup']['container']['parent_id'])) {
                $this->render403();
                return;
            }

            //Merge new services with existing services from service group
            $serviceIds = array_unique(array_merge(
                $servicegroup['Servicegroup']['services']['_ids'],
                $serviceIds
            ));

            $containerId = $servicegroup['Servicegroup']['container']['parent_id'];

            if ($containerId == ROOT_CONTAINER) {
                //Don't panic! Only root users can edit /root objects ;)
                //So no loss of selected services/service templates
                $containerIds = $ContainersTable->resolveChildrenOfContainerIds(ROOT_CONTAINER, true, [
                    CT_GLOBAL,
                    CT_TENANT,
                    CT_NODE
                ]);
            } else {
                $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId, false, [
                    CT_GLOBAL,
                    CT_TENANT,
                    CT_NODE
                ]);
            }

            $serviceIdsToSave = [];
            $HostsCache = new KeyValueStore();
            foreach ($serviceIds as $serviceId) {
                $service = $ServicesTable->get($serviceId);
                $hostId = $service->get('host_id');

                if (!$HostsCache->has($hostId)) {
                    $HostsCache->set($hostId, $HostsTable->getHostSharing($hostId));
                }

                $host = $HostsCache->get($hostId);
                foreach ($host['Host']['hosts_to_containers_sharing']['_ids'] as $hostContainerId) {
                    if (in_array($hostContainerId, $containerIds, true)) {
                        $serviceIdsToSave[] = $serviceId;
                        continue 2;
                    }
                }
            }


            $User = new User($this->Auth);
            $servicegroupEntity = $ServicegroupsTable->get($id);

            $servicegroupEntity->setAccess('uuid', false);
            $servicegroupEntity = $ServicegroupsTable->patchEntity($servicegroupEntity, [
                'services' => [
                    '_ids' => $serviceIdsToSave
                ]
            ]);
            $servicegroupEntity->id = $id;
            $ServicegroupsTable->save($servicegroupEntity);
            if ($servicegroupEntity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $servicegroupEntity->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                $fakeRequest = [
                    'Servicegroup' => [
                        'services' => [
                            '_ids' => $serviceIdsToSave
                        ]
                    ]
                ];

                //No errors
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'edit',
                    'servicegroups',
                    $servicegroupEntity->id,
                    OBJECT_SERVICEGROUP,
                    $servicegroup['Servicegroup']['container']['parent_id'],
                    $User->getId(),
                    $servicegroup['Servicegroup']['container']['name'],
                    array_merge($ServicegroupsTable->resolveDataForChangelog($fakeRequest), $fakeRequest),
                    array_merge($ServicegroupsTable->resolveDataForChangelog($servicegroupForChangelog), $servicegroupForChangelog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($servicegroupEntity); // REST API ID serialization
                    return;
                }
            }
            $this->set('servicegroup', $servicegroupEntity);
            $this->set('_serialize', ['servicegroup']);
        }
    }

    /**
     * @deprecated
     */
    public function listToPdf() {
        $this->layout = 'Admin.default';

        $ServicegroupFilter = new ServicegroupFilter($this->request);
        $query = [
            'recursive'  => -1,
            'contain'    => [
                'Container',
                'Service' => [
                    'fields'          => [
                        'Service.id',
                        'Service.name',
                        'Service.uuid'
                    ],
                    'Host'            => [
                        'fields' => [
                            'Host.id',
                            'Host.name'
                        ],
                    ],
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.name'
                        ],
                    ]
                ],
                'Servicetemplate'

            ],
            'order'      => $ServicegroupFilter->getOrderForPaginator('Container.name', 'asc'),
            'conditions' => $ServicegroupFilter->indexFilter(),
        ];


        if (!$this->hasRootPrivileges) {
            $query['conditions']['Container.parent_id'] = $this->MY_RIGHTS;
        }
        $servicegroups = $this->Servicegroup->find('all', $query);
        $servicegroupCount = count($servicegroups);
        $serviceUuids = Hash::extract($servicegroups, '{n}.Service.{n}.uuid');
        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields
            ->currentState()
            ->lastStateChange()
            ->lastCheck()
            ->nextCheck()
            ->output()
            ->problemHasBeenAcknowledged()
            ->acknowledgementType()
            ->scheduledDowntimeDepth()
            ->notificationsEnabled();
        $servicegroupstatus = $this->Servicestatus->byUuids(array_unique($serviceUuids), $ServicestatusFields);
        $hostsArray = [];
        $serviceCount = 0;

        foreach ($servicegroups as $servicegroup) {
            foreach ($servicegroup['Service'] as $service) {
                $serviceCount++;
                $hostsArray[$service['Host']['id']] = $service['Host']['name'];
            }
        }
        $hostCount = sizeof($hostsArray);
        $this->set(compact('servicegroups', 'servicegroupstatus', 'servicegroupCount', 'hostCount', 'serviceCount'));

        $filename = 'Servicegroups_' . strtotime('now') . '.pdf';
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

    /**
     * @deprecated
     */
    public function extended() {
        $this->layout = 'blank';
        $User = new User($this->Auth);

        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
            $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
            $this->set('QueryHandler', new QueryHandler($Systemsettings->getQueryHandlerPath()));
            $this->set('username', $User->getFullName());
            return;
        }

        $ServicegroupFilter = new ServicegroupFilter($this->request);
        $query = [
            'recursive'  => -1,
            'contain'    => [
                'Container',
            ],
            'conditions' => $ServicegroupFilter->indexFilter(),
            'order'      => $ServicegroupFilter->getOrderForPaginator('Container.name', 'asc'),
            'limit'      => $this->Paginator->settings['limit']
        ];
        if (!$this->hasRootPrivileges) {
            $query['conditions']['Container.parent_id'] = $this->MY_RIGHTS;
        }

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            unset($query['limit']);
            $servicegroups = $this->Servicegroup->find('all', $query);
        } else {
            $this->Paginator->settings = $query;
            $this->Paginator->settings['page'] = $ServicegroupFilter->getPage();
            $servicegroups = $this->Paginator->paginate();
        }

        $this->set('servicegroups', $servicegroups);
        $this->set('_serialize', ['servicegroups', 'username']);
    }


    /****************************
     *       AJAX METHODS       *
     ****************************/

    /**
     * @throws Exception
     */
    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_SERVICEGROUP, [], $this->hasRootPrivileges);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), OBJECT_SERVICEGROUP, [], $this->hasRootPrivileges);
        }
        $containers = Api::makeItJavaScriptAble($containers);


        $this->set('containers', $containers);
        $this->set('_serialize', ['containers']);
    }

    /**
     * @param null $containerId
     */
    public function loadServicetemplates($containerId = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $containerId = $this->request->query('containerId');
        $selected = $this->request->query('selected');
        $ServicetemplateFilter = new ServicetemplateFilter($this->request);

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

        $containerIds = [ROOT_CONTAINER, $containerId];
        if ($containerId == ROOT_CONTAINER) {
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
        }

        $servicetemplates = Api::makeItJavaScriptAble(
            $ServicetemplatesTable->getServicetemplatesForAngular($containerIds, $ServicetemplateFilter, $selected)
        );

        $this->set('servicetemplates', $servicetemplates);
        $this->set('_serialize', ['servicetemplates']);
    }

    /**
     * @param null $id
     * @deprecated
     */
    public function loadServicegroupWithServicesById($id = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Servicegroup->exists($id)) {
            throw new NotFoundException(__('Invalid service group'));
        }

        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        $hostContainers = [];
        $hosts = [];
        $services = [];

        $query = [
            'recursive'  => -1,
            'contain'    => [
                'Container',
                'Service' => [
                    'fields'          => [
                        'Service.id',
                        'Service.uuid',
                        'Service.name',
                        'Service.active_checks_enabled',
                        'Service.disabled',
                    ],
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.name',
                            'Servicetemplate.active_checks_enabled'
                        ]
                    ],
                    'Host'            => [
                        'Container',
                        'fields' => [
                            'Host.id',
                            'Host.uuid',
                            'Host.name',
                            'Host.active_checks_enabled'
                        ]
                    ]
                ]
            ],
            'conditions' => [
                'Servicegroup.id' => $id
            ]
        ];

        $servicegroup = $this->Servicegroup->find('first', $query);
        $servicegroup['Servicegroup']['allowEdit'] = $this->hasPermission('edit', 'servicegroups');;
        if ($this->hasRootPrivileges === false && $servicegroup['Servicegroup']['allowEdit'] === true) {
            $servicegroup['Servicegroup']['allowEdit'] = $this->allowedByContainerId($servicegroup['Container']['parent_id']);
        }

        foreach ($servicegroup['Service'] as $service) {
            $hosts[$service['Host']['id']] = $service['Host']['uuid'];
            $services[$service['id']] = $service['uuid'];
            if (!empty($services) && $this->hasRootPrivileges === false && $this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
                $hostContainers[$service['Host']['id']] = Hash::extract($service['Host']['Container'], '{n}.id');
            }
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->currentState();
        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields
            ->currentState()
            ->lastStateChange()
            ->lastCheck()
            ->nextCheck()
            ->output()
            ->problemHasBeenAcknowledged()
            ->acknowledgementType()
            ->scheduledDowntimeDepth()
            ->notificationsEnabled()
            ->perfdata();
        $hoststatus = $this->Hoststatus->byUuid($hosts, $HoststatusFields);
        $servicestatus = $this->Servicestatus->byUuid($services, $ServicestatusFields);

        if (!empty($services) && $this->hasRootPrivileges === false && $this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
            $hostContainers[$service['Host']['id']] = Hash::extract($service['Host']['Container'], '{n}.id');
        }
        $all_services = [];
        foreach ($servicegroup['Service'] as $key => $service) {
            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $containerIds = [];
                if (isset($hostContainers[$service['Host']['id']])) {
                    $containerIds = $hostContainers[$service['Host']['id']];
                }
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIds);
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $service['Hoststatus'] = (!empty($hoststatus[$service['Host']['uuid']])) ? $hoststatus[$service['Host']['uuid']]['Hoststatus'] : [];
            $service['Servicestatus'] = (!empty($servicestatus[$service['uuid']])) ? $servicestatus[$service['uuid']]['Servicestatus'] : [];

            $Service = new \itnovum\openITCOCKPIT\Core\Views\Service([
                'Service'         => $service,
                'Servicetemplate' => $service['Servicetemplate'],
                'Host'            => $service['Host']
            ],
                null,
                $allowEdit
            );

            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service, $allowEdit);
            $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($service['Hoststatus'], $UserTime);
            $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($service['Servicestatus'], $UserTime);
            $PerfdataChecker = new PerfdataChecker($Host, $Service, $this->PerfdataBackend, $Servicestatus);

            $tmpRecord = [
                'Service'       => $Service->toArray(),
                'Host'          => $Host->toArray(),
                'Servicestatus' => $Servicestatus->toArray(),
                'Hoststatus'    => $Hoststatus->toArray()
            ];
            $tmpRecord['Service']['has_graph'] = $PerfdataChecker->hasPerfdata();
            $all_services[] = $tmpRecord;
        }

        $serviceStatusForServicegroup = Hash::apply(
            $all_services,
            '{n}.Servicestatus[isInMonitoring=true].currentState',
            'array_count_values'
        );
        //refill missing service states
        $statusOverview = array_replace(
            [0 => 0, 1 => 0, 2 => 0, 3 => 0],
            $serviceStatusForServicegroup
        );
        $statusOverview = array_combine([
            __('ok'),
            __('warning'),
            __('critical'),
            __('unknown')
        ], $statusOverview
        );

        # get a list of sort columns and their data to pass to array_multisort
        $sortAllServices = [];
        foreach ($all_services as $k => $v) {
            $sortAllServices['Host']['hostname'][$k] = $v['Host']['hostname'];
            $sortAllServices['Service']['servicename'][$k] = $v['Service']['servicename'];
        }

        # sort by host name asc and service name asc
        if (!empty($all_services)) {
            array_multisort($sortAllServices['Host']['hostname'], SORT_ASC, $sortAllServices['Service']['servicename'], SORT_ASC, $all_services);
        }

        $selectedServiceGroup = [
            'Servicegroup'  => $servicegroup['Servicegroup'],
            'Container'     => $servicegroup['Container'],
            'Services'      => $all_services,
            'StatusSummary' => $statusOverview
        ];

        $servicegroup = $selectedServiceGroup;

        $this->set(compact(['servicegroup']));
        $this->set('_serialize', ['servicegroup']);
    }

    /**
     * @deprecated
     */
    public function loadServicegroupsByContainerId() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $containerId = $this->request->query('containerId');
        $selected = $this->request->query('selected');
        $ServicegroupFilter = new ServicegroupFilter($this->request);

        $containerIds = [ROOT_CONTAINER, $containerId];
        if ($containerId == ROOT_CONTAINER) {
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
        }

        $query = [
            'recursive'  => -1,
            'contain'    => [
                'Container'
            ],
            'order'      => $ServicegroupFilter->getOrderForPaginator('Container.name', 'asc'),
            'conditions' => $ServicegroupFilter->indexFilter(),
            'limit'      => $this->Paginator->settings['limit']
        ];

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            unset($query['limit']);
            $servicegroups = $this->Servicegroup->find('all', $query);
        } else {
            $this->Paginator->settings = $query;
            $this->Paginator->settings['page'] = $ServicegroupFilter->getPage();
            $servicegroups = $this->Paginator->paginate();
        }

        $servicegroups = Api::makeItJavaScriptAble(
            Hash::combine(
                $servicegroups,
                '{n}.Servicegroup.id',
                '{n}.Container.name'
            )
        );

        $this->set(compact(['servicegroups']));
        $this->set('_serialize', ['servicegroups']);
    }

    /**
     * @deprecated
     */
    public function loadServicegroupsByString() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->query('selected');

        $ServicegroupFilter = new ServicegroupFilter($this->request);

        $ServicegroupConditions = new ServicegroupConditions($ServicegroupFilter->indexFilter());
        $ServicegroupConditions->setContainerIds($this->MY_RIGHTS);

        $servicegroups = Api::makeItJavaScriptAble(
            $this->Servicegroup->getServicegroupsForAngular($ServicegroupConditions, $selected)
        );

        $this->set(compact(['servicegroups']));
        $this->set('_serialize', ['servicegroups']);
    }
}
