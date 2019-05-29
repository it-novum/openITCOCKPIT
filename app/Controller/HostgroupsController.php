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

use App\Lib\Exceptions\MissingDbBackendException;
use App\Lib\Interfaces\HoststatusTableInterface;
use App\Model\Table\ContainersTable;
use App\Model\Table\HostgroupsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\HostConditions;
use itnovum\openITCOCKPIT\Core\HostgroupConditions;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\ValueObjects\CumulatedValue;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\HostFilter;
use itnovum\openITCOCKPIT\Filter\HostgroupFilter;
use itnovum\openITCOCKPIT\Filter\HosttemplateFilter;

/**
 * Class HostgroupsController
 *
 * @property AppPaginatorComponent $Paginator
 * @property DbBackend $DbBackend
 */
class HostgroupsController extends AppController {

    /**
     * @var array
     * @deprecated
     */
    public $uses = [
        'Hostgroup',
        'Service',
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS
    ];

    public $layout = 'blank';

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        $HostgroupFilter = new HostgroupFilter($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $HostgroupFilter->getPage());

        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges) {
            $MY_RIGHTS = [];
        }

        $hostgroups = $HostgroupsTable->getHostgroupsIndex($HostgroupFilter, $PaginateOMat, $MY_RIGHTS);

        $all_hostgroups = [];
        foreach ($hostgroups as $hostgroup) {
            $hostgroup['allowEdit'] = $this->hasPermission('edit', 'hostgroups');
            if ($this->hasRootPrivileges === false && $hostgroup['allowEdit'] === true) {
                $hostgroup['allowEdit'] = $this->allowedByContainerId($hostgroup['parent_id']);
            }

            $all_hostgroups[] = $hostgroup;
        }

        $this->set('all_hostgroups', $all_hostgroups);
        $toJson = ['all_hostgroups', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_hostgroups', 'scroll'];
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

        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');

        if (!$HostgroupsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Hostgroup'));
        }

        $hostgroup = $HostgroupsTable->get($id, [
            'contain' => [
                'Containers'
            ]
        ]);

        if (!$this->allowedByContainerId($hostgroup->get('container')->get('parent_id'))) {
            $this->render403();
            return;
        }

        $this->set('hostgroup', $hostgroup);
        $this->set('_serialize', ['hostgroup']);
    }

    public function extended() {
        if (!$this->isApiRequest()) {
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            $this->set('username', $User->getFullName());
        }
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }


        if ($this->request->is('post')) {
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

            /** @var $HostgroupsTable HostgroupsTable */
            $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
            $this->request->data['Hostgroup']['uuid'] = UUID::v4();
            $this->request->data['Hostgroup']['container']['containertype_id'] = CT_HOSTGROUP;
            $hostgroup = $HostgroupsTable->newEntity();
            $hostgroup = $HostgroupsTable->patchEntity($hostgroup, $this->request->data('Hostgroup'));

            $HostgroupsTable->save($hostgroup);
            if ($hostgroup->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $hostgroup->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors

                $extDataForChangelog = $HostgroupsTable->resolveDataForChangelog($this->request->data);
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'add',
                    'hostgroups',
                    $hostgroup->get('id'),
                    OBJECT_HOSTGROUP,
                    $hostgroup->get('container')->get('parent_id'),
                    $User->getId(),
                    $hostgroup->get('container')->get('name'),
                    array_merge($this->request->data, $extDataForChangelog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                //@todo refactor with cake4
                Cache::clear(false, 'permissions');

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($hostgroup); // REST API ID serialization
                    return;
                }
            }
            $this->set('hostgroup', $hostgroup);
            $this->set('_serialize', ['hostgroup']);
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

        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');

        if (!$HostgroupsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Hostgroup'));
        }

        $hostgroup = $HostgroupsTable->getHostgroupForEdit($id);
        $hostgroupForChangelog = $hostgroup;

        if (!$this->allowedByContainerId($hostgroup['Hostgroup']['container']['parent_id'])) {
            $this->render403();
            return;
        }

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return host group information
            $this->set('hostgroup', $hostgroup);
            $this->set('_serialize', ['hostgroup']);
            return;
        }

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            //Update contact data
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            $hostgroupEntity = $HostgroupsTable->get($id);

            $hostgroupEntity->setAccess('uuid', false);
            $hostgroupEntity = $HostgroupsTable->patchEntity($hostgroupEntity, $this->request->data('Hostgroup'));
            $hostgroupEntity->id = $id;
            $HostgroupsTable->save($hostgroupEntity);
            if ($hostgroupEntity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $hostgroupEntity->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors

                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'edit',
                    'hostgroups',
                    $hostgroupEntity->id,
                    OBJECT_HOSTGROUP,
                    $hostgroupEntity->get('container')->get('parent_id'),
                    $User->getId(),
                    $hostgroupEntity->get('container')->get('name'),
                    array_merge($HostgroupsTable->resolveDataForChangelog($this->request->data), $this->request->data),
                    array_merge($HostgroupsTable->resolveDataForChangelog($hostgroupForChangelog), $hostgroupForChangelog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($hostgroupEntity); // REST API ID serialization
                    return;
                }
            }
            $this->set('hostgroup', $hostgroupEntity);
            $this->set('_serialize', ['hostgroup']);
        }
    }

    /**
     * @param int|null $id
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if (!$HostgroupsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Hostgroup'));
        }

        $hostgroup = $HostgroupsTable->getHostgroupById($id);
        $container = $ContainersTable->get($hostgroup->get('container')->get('id'), [
            'contain' => [
                'Hostgroups'
            ]
        ]);

        if (!$this->allowedByContainerId($hostgroup->get('container')->get('parent_id'))) {
            $this->render403();
            return;
        }

        if ($ContainersTable->delete($container)) {
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            $changelog_data = $this->Changelog->parseDataForChangelog(
                'delete',
                'hostgroups',
                $id,
                OBJECT_HOSTGROUP,
                $container->get('parent_id'),
                $User->getId(),
                $container->get('name'),
                [
                    'Hostgroup' => $hostgroup->toArray()
                ]
            );
            if ($changelog_data) {
                CakeLog::write('log', serialize($changelog_data));
            }

            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }

        $this->response->statusCode(500);
        $this->set('success', false);
        $this->set('_serialize', ['success']);
    }


    /**
     * @param int|null $id
     * @deprecated
     */
    public function loadHostgroupWithHostsById($id = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');

        if (!$HostgroupsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Hostgroup'));
        }

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $UserTime = UserTime::fromUser($User);

        $hostIds = $HostgroupsTable->getHostIdsByHostgroupId($id);


        $HostFilter = new HostFilter($this->request);
        $HostConditions = new HostConditions();

        $HostConditions->setIncludeDisabled(false);
        $HostConditions->setHostIds($hostIds);
        $HostConditions->setContainerIds($this->MY_RIGHTS);

        if($this->DbBackend->isNdoUtils()){
            /** @var $HostsTable HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
            $hosts = $HostsTable->getHostsIndex($HostFilter, $HostConditions);

            print_r($hosts);
        }

        if($this->DbBackend->isStatusengine3()){
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        if($this->DbBackend->isCrateDb()){
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        debug($hostIds);

        return;
        /***** OLD CODE ****/

        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');

        if (!$HostgroupsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Hostgroup'));
        }


        $HostFilter = new HostFilter($this->request);
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        $hostContainers = [];
        $hosts = [];

        $query = [
            'recursive'  => -1,
            'contain'    => [
                'Container',
                'Host' => [
                    'Container',
                    'fields'     => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name',
                        'Host.active_checks_enabled',
                        'Host.disabled',
                        'Host.satellite_id'
                    ],
                    'conditions' => [
                        'Host.disabled' => 0
                    ],
                    'Service'    => [
                        'fields' => [
                            'Service.uuid'
                        ]
                    ],
                    'order'      => [
                        'Host.name'
                    ]

                ]
            ],
            'conditions' => [
                'Hostgroup.id' => $id
            ]
        ];

        $hostFilterConditions = $HostFilter->indexFilter();
        if (!empty($hostFilterConditions)) {
            foreach ($hostFilterConditions as $field => $condition) {
                $query['contain']['Host']['conditions'][$field] = $condition;
            }
        }

        $hostgroup = $this->Hostgroup->find('first', $query);
        $hostgroup['Hostgroup']['allowEdit'] = $this->hasPermission('edit', 'hostgroups');;
        if ($this->hasRootPrivileges === false && $hostgroup['Hostgroup']['allowEdit'] === true) {
            $hostgroup['Hostgroup']['allowEdit'] = $this->allowedByContainerId($hostgroup['Container']['parent_id']);
        }
        foreach ($hostgroup['Host'] as $host) {
            $hosts[$host['id']] = $host['uuid'];
            if (!empty($hosts) && $this->hasRootPrivileges === false && $this->hasPermission('edit', 'hosts')) {
                $hostContainers[$host['id']] = \Cake\Utility\Hash::extract($host['Container'], '{n}.id');
            }
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->currentState()
            ->lastStateChange()
            ->lastCheck()
            ->nextCheck()
            ->problemHasBeenAcknowledged()
            ->acknowledgementType()
            ->scheduledDowntimeDepth()
            ->activeChecksEnabled()
            ->notificationsEnabled();

        $hoststatus = $this->Hoststatus->byUuid($hosts, $HoststatusFields);
        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->currentState();


        $all_hosts = [];
        foreach ($hostgroup['Host'] as $key => $host) {
            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $containerIds = [];
                if (isset($hostContainers[$host['id']])) {
                    $containerIds = $hostContainers[$host['id']];
                }
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIds);
                $allowEdit = $ContainerPermissions->hasPermission();
            }
            $serviceUuids = \Cake\Utility\Hash::extract($host, 'Service.{n}.uuid');
            $servicestatus = $this->Servicestatus->byUuid($serviceUuids, $ServicestatusFields);
            $serviceStateSummary = $this->Service->getServiceStateSummary($servicestatus, false);

            $CumulatedValue = new CumulatedValue($serviceStateSummary['state']);
            $serviceStateSummary['cumulatedState'] = $CumulatedValue->getKeyFromCumulatedValue();
            $serviceStateSummary['state'] = array_combine([
                __('ok'),
                __('warning'),
                __('critical'),
                __('unknown')
            ], $serviceStateSummary['state']
            );
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host(['Host' => $host], $allowEdit);
            $host['Hoststatus'] = (!empty($hoststatus[$host['uuid']])) ? $hoststatus[$host['uuid']]['Hoststatus'] : [];
            $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($host['Hoststatus'], $UserTime);

            $tmpRecord = [
                'Host'                 => $Host->toArray(),
                'Hoststatus'           => $Hoststatus->toArray(),
                'ServicestatusSummary' => $serviceStateSummary
            ];
            $all_hosts[] = $tmpRecord;
        }

        $hostStatusForHostgroup = \Cake\Utility\Hash::apply(
            $all_hosts,
            '{n}.Hoststatus[isInMonitoring=true].currentState',
            'array_count_values'
        );
        //refill missing hosts states
        $statusOverview = array_replace(
            [0 => 0, 1 => 0, 2 => 0],
            $hostStatusForHostgroup
        );
        $statusOverview = array_combine([
            __('up'),
            __('down'),
            __('unreachable')
        ], $statusOverview
        );

        # get a list of sort columns and their data to pass to array_multisort
        $sortAllServices = [];
        foreach ($all_hosts as $k => $v) {
            $sortAllServices['Host']['hostname'][$k] = $v['Host']['hostname'];
        }

        # sort by host name asc and service name asc
        if (!empty($all_services)) {
            array_multisort($sortAllServices['Host']['hostname'], SORT_ASC, $all_hosts);
        }

        $selectedHostGroup = [
            'Hostgroup'     => $hostgroup['Hostgroup'],
            'Container'     => $hostgroup['Container'],
            'Hosts'         => $all_hosts,
            'StatusSummary' => $statusOverview
        ];

        $hostgroup = $selectedHostGroup;

        $this->set('hostgroup', $hostgroup);
        $this->set('_serialize', ['hostgroup']);
    }

    /**
     * @throws \App\Lib\Exceptions\MissingDbBackendException
     */
    public function listToPdf() {
        $this->layout = 'Admin.default';

        $HostgroupFilter = new HostgroupFilter($this->request);

        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');

        /** @var $HoststatusTable HoststatusTableInterface */
        $HoststatusTable = $this->DbBackend->getHoststatusTable();

        $MY_RIGHTS = [];
        if ($this->hasRootPrivileges === false) {
            $MY_RIGHTS = $this->MY_RIGHTS;
        }
        $hostgroups = $HostgroupsTable->getHostgroupsForPdf($HostgroupFilter, $MY_RIGHTS);


        $hostgroupHostCount = 0;
        foreach ($hostgroups as $index => $hostgroup) {
            $hostgroupHostUuids = \Cake\Utility\Hash::extract($hostgroup, 'hosts.{n}.uuid');
            $hostgroupHostCount += count($hostgroupHostUuids);
            $HoststatusFields = new HoststatusFields($this->DbBackend);
            $HoststatusFields->wildcard();
            $hoststatusOfHostgroup = $HoststatusTable->byUuids($hostgroupHostUuids, $HoststatusFields);
            $hostgroups[$index]['all_hoststatus'] = $hoststatusOfHostgroup;
        }

        $hostgroupCount = count($hostgroups);
        $this->set('hostgroups', $hostgroups);
        $this->set('hostgroupCount', $hostgroupCount);
        $this->set('hostgroupHostCount', $hostgroupHostCount);


        $filename = 'Hostgroups_' . strtotime('now') . '.pdf';
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

    public function addHostsToHostgroup() {
        //Only ship template
        return;
    }

    public function append() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        if ($this->request->is('post')) {
            $id = $this->request->data('Hostgroup.id');
            $hostIds = $this->request->data('Hostgroup.hosts._ids');
            if (!is_array($hostIds)) {
                $hostIds = [$hostIds];
            }

            if (empty($hostIds)) {
                //No hosts to add
                return;
            }

            /** @var $HostgroupsTable HostgroupsTable */
            $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
            /** @var $HostsTable HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

            if (!$HostgroupsTable->existsById($id)) {
                throw new NotFoundException(__('Invalid Hostgroup'));
            }

            $hostgroup = $HostgroupsTable->getHostgroupForEdit($id);
            $hostgroupForChangelog = $hostgroup;
            if (!$this->allowedByContainerId($hostgroup['Hostgroup']['container']['parent_id'])) {
                $this->render403();
                return;
            }

            //Merge new hosts with existing hosts from host group
            $hostIds = array_unique(array_merge(
                $hostgroup['Hostgroup']['hosts']['_ids'],
                $hostIds
            ));

            $containerId = $hostgroup['Hostgroup']['container']['parent_id'];

            if ($containerId == ROOT_CONTAINER) {
                //Don't panic! Only root users can edit /root objects ;)
                //So no loss of selected hosts/host templates
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

            $hostIdsToSave = [];
            foreach ($hostIds as $hostId) {
                $host = $HostsTable->getHostSharing($hostId);
                foreach ($host['Host']['hosts_to_containers_sharing']['_ids'] as $hostContainerId) {
                    if (in_array($hostContainerId, $containerIds, true)) {
                        $hostIdsToSave[] = $hostId;
                        continue 2;
                    }
                }
            }


            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            $hostgroupEntity = $HostgroupsTable->get($id);

            $hostgroupEntity->setAccess('uuid', false);
            $hostgroupEntity = $HostgroupsTable->patchEntity($hostgroupEntity, [
                'hosts' => [
                    '_ids' => $hostIdsToSave
                ]
            ]);
            $hostgroupEntity->id = $id;
            $HostgroupsTable->save($hostgroupEntity);
            if ($hostgroupEntity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $hostgroupEntity->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                $fakeRequest = [
                    'Hostgroup' => [
                        'hosts' => [
                            '_ids' => $hostIdsToSave
                        ]
                    ]
                ];

                //No errors
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'edit',
                    'hostgroups',
                    $hostgroupEntity->id,
                    OBJECT_HOSTGROUP,
                    $hostgroup['Hostgroup']['container']['parent_id'],
                    $User->getId(),
                    $hostgroup['Hostgroup']['container']['name'],
                    array_merge($HostgroupsTable->resolveDataForChangelog($fakeRequest), $fakeRequest),
                    array_merge($HostgroupsTable->resolveDataForChangelog($hostgroupForChangelog), $hostgroupForChangelog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($hostgroupEntity); // REST API ID serialization
                    return;
                }
            }
            $this->set('hostgroup', $hostgroupEntity);
            $this->set('_serialize', ['hostgroup']);
        }
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
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
        }
        $containers = Api::makeItJavaScriptAble($containers);


        $this->set('containers', $containers);
        $this->set('_serialize', ['containers']);
    }

    public function loadHosts() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $containerId = $this->request->query('containerId');
        $selected = $this->request->query('selected');

        $HostFilter = new HostFilter($this->request);

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if ($containerId == ROOT_CONTAINER) {
            //Don't panic! Only root users can edit /root objects ;)
            //So no loss of selected hosts/host templates
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
        $HostCondition = new HostConditions($HostFilter->ajaxFilter());
        $HostCondition->setContainerIds($containerIds);
        $hosts = Api::makeItJavaScriptAble(
            $HostsTable->getHostsForAngular($HostCondition, $selected)
        );
        $this->set('hosts', $hosts);
        $this->set('_serialize', ['hosts']);
    }

    /**
     * @param int|null $containerId
     */
    public function loadHosttemplates($containerId = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $containerId = $this->request->query('containerId');
        $selected = $this->request->query('selected');
        $HosttemplateFilter = new HosttemplateFilter($this->request);

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');

        $containerIds = [ROOT_CONTAINER, $containerId];
        if ($containerId == ROOT_CONTAINER) {
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
        }

        $hosttemplates = Api::makeItJavaScriptAble(
            $HosttemplatesTable->getHosttemplatesForAngular($containerIds, $HosttemplateFilter, $selected)
        );

        $this->set('hosttemplates', $hosttemplates);
        $this->set('_serialize', ['hosttemplates']);
    }

    public function loadHostgroupsByString() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->query('selected');

        $HostgroupFilter = new HostgroupFilter($this->request);

        $HostgroupCondition = new HostgroupConditions($HostgroupFilter->indexFilter());
        $HostgroupCondition->setContainerIds($this->MY_RIGHTS);

        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');

        $hostgroups = Api::makeItJavaScriptAble(
            $HostgroupsTable->getHostgroupsForAngular($HostgroupCondition, $selected)
        );

        $this->set('hostgroups', $hostgroups);
        $this->set('_serialize', ['hostgroups']);
    }

    public function loadHosgroupsByContainerId() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        $containerId = $this->request->query('containerId');
        $HostgroupFilter = new HostgroupFilter($this->request);

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');

        if ($containerId == ROOT_CONTAINER) {
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds(ROOT_CONTAINER, true, [CT_HOSTGROUP]);
        } else {
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId, false, [CT_HOSTGROUP]);
        }
        $HostgroupCondition = new HostgroupConditions($HostgroupFilter->indexFilter());
        $HostgroupCondition->setContainerIds($containerIds);

        $hostgroups = $HostgroupsTable->getHostgroupsByContainerIdNew($HostgroupCondition);
        $hostgroups = Api::makeItJavaScriptAble($hostgroups);

        $this->set('hostgroups', $hostgroups);
        $this->set('_serialize', ['hostgroups']);
    }

}
