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

declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\ContainersTable;
use App\Model\Table\HostgroupsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplategroupsTable;
use App\Model\Table\ServicetemplatesTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\Comparison\ServiceComparisonForSave;
use itnovum\openITCOCKPIT\Core\HostgroupConditions;
use itnovum\openITCOCKPIT\Core\KeyValueStore;
use itnovum\openITCOCKPIT\Core\ServicetemplategroupsConditions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\HostgroupFilter;
use itnovum\openITCOCKPIT\Filter\ServicetemplategroupsFilter;


/**
 * @property Servicetemplategroup $Servicetemplategroup
 * @property Servicetemplate $Servicetemplate
 * @property Container $Container
 * @property Host $Host
 * @property Hostgroup $Hostgroup
 * @property AppPaginatorComponent $Paginator
 */
class ServicetemplategroupsController extends AppController {
    public $layout = 'blank';

    public $uses = [
        'Changelog',
    ];


    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
        $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');

        $ServicetemplategroupsFilter = new ServicetemplategroupsFilter($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $ServicetemplategroupsFilter->getPage());

        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges) {
            $MY_RIGHTS = [];
        }
        $servicetemplategroups = $ServicetemplategroupsTable->getServicetemplategroupsIndex($ServicetemplategroupsFilter, $PaginateOMat, $MY_RIGHTS);

        foreach ($servicetemplategroups as $index => $servicetemplategroup) {
            $servicetemplategroups[$index]['Servicetemplategroup']['allow_edit'] = true;
            if ($this->hasRootPrivileges === false) {
                $servicetemplategroups[$index]['Servicetemplategroup']['allow_edit'] = $this->isWritableContainer($servicetemplategroup['Servicetemplategroup']['container_id']);
            }
        }


        $this->set('all_servicetemplategroups', $servicetemplategroups);
        $toJson = ['all_servicetemplategroups', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_servicetemplategroups', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }

    /**
     * @param int|null $id
     */
    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
        $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');

        if (!$ServicetemplategroupsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid service template group'));
        }

        $servicetemplategroup = $ServicetemplategroupsTable->getServicetemplategroupForView($id);
        if (!$this->allowedByContainerId($servicetemplategroup->get('Containers')['parent_id'])) {
            $this->render403();
            return;
        }

        $this->set('servicetemplategroup', $servicetemplategroup);
        $this->viewBuilder()->setOption('serialize', ['servicetemplategroup']);
    }

    /**
     * @throws Exception
     */
    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
        $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');
        $this->request->data['Servicetemplategroup']['uuid'] = \itnovum\openITCOCKPIT\Core\UUID::v4();
        $this->request->data['Servicetemplategroup']['container']['containertype_id'] = CT_SERVICETEMPLATEGROUP;


        $servicetemplategroup = $ServicetemplategroupsTable->newEmptyEntity();
        $servicetemplategroup = $ServicetemplategroupsTable->patchEntity($servicetemplategroup, $this->request->data('Servicetemplategroup'));

        $ServicetemplategroupsTable->save($servicetemplategroup);
        if ($servicetemplategroup->hasErrors()) {
            $this->response->statusCode(400);
            $this->set('error', $servicetemplategroup->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        } else {
            //No errors

            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

            $extDataForChangelog = $ServicetemplategroupsTable->resolveDataForChangelog($this->request->data);
            $changelog_data = $this->Changelog->parseDataForChangelog(
                'add',
                'servicetemplategroups',
                $servicetemplategroup->get('id'),
                OBJECT_SERVICETEMPLATEGROUP,
                $servicetemplategroup->get('container')->get('parent_id'),
                $User->getId(),
                $servicetemplategroup->get('container')->get('name'),
                array_merge($this->request->data, $extDataForChangelog)
            );

            if ($changelog_data) {
                CakeLog::write('log', serialize($changelog_data));
            }


            if ($this->request->ext == 'json') {
                $this->serializeCake4Id($servicetemplategroup); // REST API ID serialization
                return;
            }
        }
        $this->set('servicetemplategroup', $servicetemplategroup);
        $this->viewBuilder()->setOption('serialize', ['servicetemplategroup']);
    }

    /**
     * @param int|null $id
     * @throws Exception
     */
    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
        $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');

        if (!$ServicetemplategroupsTable->existsById($id)) {
            throw new NotFoundException(__('Service template group not found'));
        }

        $servicetemplategroup = $ServicetemplategroupsTable->getServicetemplategroupForEdit($id);
        $servicetemplategroupForChangeLog = $servicetemplategroup;

        if (!$this->isWritableContainer($servicetemplategroup['Servicetemplategroup']['container']['parent_id'])) {
            $this->render403();
            return;
        }

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return service template group information
            $this->set('servicetemplategroup', $servicetemplategroup);
            $this->viewBuilder()->setOption('serialize', ['servicetemplategroup']);
            return;
        }

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            //Update service template group data
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

            $servicetemplategroupEntity = $ServicetemplategroupsTable->get($id, [
                'contain' => [
                    'Containers'
                ]
            ]);
            $servicetemplategroupEntity->setAccess('uuid', false);
            $servicetemplategroupEntity = $ServicetemplategroupsTable->patchEntity($servicetemplategroupEntity, $this->request->data('Servicetemplategroup'));
            $servicetemplategroupEntity->id = $id;

            $ServicetemplategroupsTable->save($servicetemplategroupEntity);
            if ($servicetemplategroupEntity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $servicetemplategroupEntity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                //No errors

                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'edit',
                    'servicetemplategroups',
                    $servicetemplategroupEntity->get('id'),
                    OBJECT_SERVICETEMPLATEGROUP,
                    $servicetemplategroupEntity->get('container')->get('parent_id'),
                    $User->getId(),
                    $servicetemplategroupEntity->get('container')->get('name'),
                    array_merge($ServicetemplategroupsTable->resolveDataForChangelog($this->request->data), $this->request->data),
                    array_merge($ServicetemplategroupsTable->resolveDataForChangelog($servicetemplategroupForChangeLog), $servicetemplategroupForChangeLog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($servicetemplategroupEntity); // REST API ID serialization
                    return;
                }
            }
            $this->set('servicetemplategroup', $servicetemplategroupEntity);
            $this->viewBuilder()->setOption('serialize', ['servicetemplategroup']);
        }
    }

    public function append() {
        $this->layout = 'blank';
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        if ($this->request->is('post')) {
            $id = $this->request->data('Servicetemplategroup.id');
            $servicetemplateIds = $this->request->data('Servicetemplategroup.servicetemplates._ids');
            if (!is_array($servicetemplateIds)) {
                $servicetemplateIds = [$servicetemplateIds];
            }

            if (empty($servicetemplateIds)) {
                //No service templates to add
                return;
            }

            /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
            $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');
            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
            /** @var $ServicetemplatesTable ServicetemplatesTable */
            $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

            if (!$ServicetemplategroupsTable->existsById($id)) {
                throw new NotFoundException(__('Invalid service template group'));
            }

            $servicetemplategroup = $ServicetemplategroupsTable->getServicetemplategroupForEdit($id);
            $servicetemplategroupForChangelog = $servicetemplategroup;
            if (!$this->allowedByContainerId($servicetemplategroup['Servicetemplategroup']['container']['parent_id'])) {
                $this->render403();
                return;
            }

            //Merge new service templates with existing service templates from service template group
            $servicetemplateIds = array_unique(array_merge(
                $servicetemplategroup['Servicetemplategroup']['servicetemplates']['_ids'],
                $servicetemplateIds
            ));

            $containerId = $servicetemplategroup['Servicetemplategroup']['container']['parent_id'];

            if ($containerId == ROOT_CONTAINER) {
                //Don't panic! Only root users can edit /root objects ;)
                //So no loss of selected service templates
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

            $servicetemplatesIdsToSave = [];
            foreach ($servicetemplateIds as $servicetemplateId) {
                $servicetemplate = $ServicetemplatesTable->find()
                    ->select([
                        'Servicetemplates.id',
                        'Servicetemplates.container_id'
                    ])
                    ->where([
                        'Servicetemplates.id' => $servicetemplateId
                    ])
                    ->firstOrFail();

                if (in_array($servicetemplate->get('container_id'), $containerIds, true)) {
                    $servicetemplatesIdsToSave[] = $servicetemplateId;
                }
            }


            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            $servicetemplategroupEntity = $ServicetemplategroupsTable->get($id);

            $servicetemplategroupEntity->setAccess('uuid', false);
            $servicetemplategroupEntity = $ServicetemplategroupsTable->patchEntity($servicetemplategroupEntity, [
                'servicetemplates' => [
                    '_ids' => $servicetemplatesIdsToSave
                ]
            ]);
            $servicetemplategroupEntity->id = $id;
            $ServicetemplategroupsTable->save($servicetemplategroupEntity);
            if ($servicetemplategroupEntity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $servicetemplategroupEntity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                $fakeRequest = [
                    'Servicetemplategroup' => [
                        'description'      => $servicetemplategroup['Servicetemplategroup']['description'],
                        'servicetemplates' => [
                            '_ids' => $servicetemplatesIdsToSave
                        ]
                    ]
                ];

                //No errors
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'edit',
                    'servicetemplategroups',
                    $servicetemplategroupEntity->id,
                    OBJECT_SERVICETEMPLATEGROUP,
                    $servicetemplategroup['Servicetemplategroup']['container']['parent_id'],
                    $User->getId(),
                    $servicetemplategroup['Servicetemplategroup']['container']['name'],
                    array_merge($ServicetemplategroupsTable->resolveDataForChangelog($fakeRequest), $fakeRequest),
                    array_merge($ServicetemplategroupsTable->resolveDataForChangelog($servicetemplategroupForChangelog), $servicetemplategroupForChangelog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($servicetemplategroupEntity); // REST API ID serialization
                    return;
                }
            }
            $this->set('servicetemplategroup', $servicetemplategroupEntity);
            $this->viewBuilder()->setOption('serialize', ['servicetemplategroup']);
        }
    }

    /**
     * @param int|null $id
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
        $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');

        if (!$ServicetemplategroupsTable->existsById($id)) {
            throw new NotFoundException(__('Service template group not found'));
        }

        $servicetemplategroupEntity = $ServicetemplategroupsTable->get($id, [
            'contain' => [
                'Containers'
            ]
        ]);

        if (!$this->isWritableContainer($servicetemplategroupEntity->get('container')->get('parent_id'))) {
            $this->render403();
            return;
        }


        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $container = $ContainersTable->get($servicetemplategroupEntity->get('container')->get('id'), [
            'contain' => [
                'Servicetemplategroups'
            ]
        ]);

        if ($ContainersTable->delete($container)) {
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            Cache::clear(false, 'permissions');
            $changelog_data = $this->Changelog->parseDataForChangelog(
                'delete',
                'servicetemplategroups',
                $id,
                OBJECT_SERVICETEMPLATEGROUP,
                $servicetemplategroupEntity->get('container')->get('parent_id'),
                $User->getId(),
                $servicetemplategroupEntity->get('container')->get('name'),
                $servicetemplategroupEntity->toArray()
            );
            if ($changelog_data) {
                CakeLog::write('log', serialize($changelog_data));
            }

            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->response->statusCode(500);
        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }


    /********************************
     *      ALLOCATION METHODS      *
     ********************************/

    /**
     * @param int|null $servicetemplategroupId
     */
    public function allocateToHost($servicetemplategroupId = null) {
        $this->layout = 'blank';
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
        $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if (!$ServicetemplategroupsTable->existsById($servicetemplategroupId)) {
            throw new NotFoundException('Invalid service template group');
        }

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return list of services that will be created by the system

            $hostId = $this->request->query('hostId');
            if (!$HostsTable->existsById($hostId)) {
                throw new NotFoundException('Invalid host');
            }

            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

            $containerIds = $ContainersTable->resolveChildrenOfContainerIds($HostsTable->getHostPrimaryContainerIdByHostId($hostId));

            $servicetemplategroup = $ServicetemplategroupsTable->getServicetemplatesforAllocation($servicetemplategroupId, $containerIds);
            $targetHostwithServices = $HostsTable->getServicesForServicetemplateAllocation($hostId);

            $servicetemplatesForDeploy = [];
            foreach ($servicetemplategroup['servicetemplates'] as $servicetemplate) {
                $doesServicetemplateExistsOnTargetHost = false;
                $doesServicetemplateExistsOnTargetHostAndIsDisabled = false;
                $createServiceOnTargetHost = true;

                $doesServicetemplateExistsOnTargetHost = isset($targetHostwithServices['services'][$servicetemplate['id']]);
                if (isset($targetHostwithServices['services'][$servicetemplate['id']])) {
                    $doesServicetemplateExistsOnTargetHostAndIsDisabled = (bool)$targetHostwithServices['services'][$servicetemplate['id']]['disabled'];
                }

                if ($doesServicetemplateExistsOnTargetHost || $doesServicetemplateExistsOnTargetHostAndIsDisabled) {
                    $createServiceOnTargetHost = false;
                }

                $servicetemplatesForDeploy[] = [
                    'servicetemplate'                                    => $servicetemplate,
                    'doesServicetemplateExistsOnTargetHost'              => $doesServicetemplateExistsOnTargetHost,
                    'doesServicetemplateExistsOnTargetHostAndIsDisabled' => $doesServicetemplateExistsOnTargetHostAndIsDisabled,
                    'createServiceOnTargetHost'                          => $createServiceOnTargetHost
                ];
            }

            $this->set('servicetemplatesForDeploy', $servicetemplatesForDeploy);
            $this->viewBuilder()->setOption('serialize', ['servicetemplatesForDeploy']);
            return;
        }

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            //Create the services out of the service template group on the selected host

            $hostId = $this->request->data('Host.id');
            if (!$HostsTable->existsById($hostId)) {
                throw new NotFoundException('Invalid host');
            }

            $servicetemplateIds = $this->request->data('Servicetemplates._ids');
            if (empty($servicetemplateIds)) {
                //No service templates selected...
                $this->set('success', false);
                $this->set('message', __('No service template ids set'));
                $this->viewBuilder()->setOption('serialize', ['success', 'message']);
                return;
            }

            /** @var $HosttemplatesTable HosttemplatesTable */
            $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
            /** @var $ServicetemplatesTable ServicetemplatesTable */
            $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
            /** @var $ServicesTable ServicesTable */
            $ServicesTable = TableRegistry::getTableLocator()->get('Services');

            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

            $host = $HostsTable->get($hostId);
            $hostContactsAndContactgroupsById = $HostsTable->getContactsAndContactgroupsById($host->get('id'));
            $hosttemplateContactsAndContactgroupsById = $HosttemplatesTable->getContactsAndContactgroupsById($host->get('hosttemplate_id'));

            $newServiceIds = [];
            $errors = [];
            foreach ($servicetemplateIds as $servicetemplateId) {
                $servicetemplate = $ServicetemplatesTable->getServicetemplateForDiff($servicetemplateId);

                $servicename = $servicetemplate['Servicetemplate']['name'];

                $serviceData = ServiceComparisonForSave::getServiceSkeleton($hostId, $servicetemplateId);
                $ServiceComparisonForSave = new ServiceComparisonForSave(
                    ['Service' => $serviceData],
                    $servicetemplate,
                    $hostContactsAndContactgroupsById,
                    $hosttemplateContactsAndContactgroupsById
                );
                $serviceData = $ServiceComparisonForSave->getDataForSaveForAllFields();
                $serviceData['uuid'] = \itnovum\openITCOCKPIT\Core\UUID::v4();

                //Add required fields for validation
                $serviceData['servicetemplate_flap_detection_enabled'] = $servicetemplate['Servicetemplate']['flap_detection_enabled'];
                $serviceData['servicetemplate_flap_detection_on_ok'] = $servicetemplate['Servicetemplate']['flap_detection_on_ok'];
                $serviceData['servicetemplate_flap_detection_on_warning'] = $servicetemplate['Servicetemplate']['flap_detection_on_warning'];
                $serviceData['servicetemplate_flap_detection_on_critical'] = $servicetemplate['Servicetemplate']['flap_detection_on_critical'];
                $serviceData['servicetemplate_flap_detection_on_unknown'] = $servicetemplate['Servicetemplate']['flap_detection_on_unknown'];

                $service = $ServicesTable->newEntity($serviceData);

                $ServicesTable->save($service);
                if ($service->hasErrors()) {
                    $errors[] = $service->getErrors();
                } else {
                    //No errors

                    $extDataForChangelog = $ServicesTable->resolveDataForChangelog(['Service' => $serviceData]);
                    $changelog_data = $this->Changelog->parseDataForChangelog(
                        'add',
                        'services',
                        $service->get('id'),
                        OBJECT_SERVICE,
                        $host->get('container_id'),
                        $User->getId(),
                        $host->get('name') . '/' . $servicename,
                        array_merge(['Service' => $serviceData], $extDataForChangelog)
                    );

                    if ($changelog_data) {
                        CakeLog::write('log', serialize($changelog_data));
                    }

                    $newServiceIds[] = $service->get('id');
                }
            }


            $this->set('success', true);
            $this->set('services', ['_ids' => $newServiceIds]);
            $this->set('hostId', $hostId);
            $this->set('errors', $errors);
            $this->viewBuilder()->setOption('serialize', ['success', 'services', 'hostId', 'errors']);
            return;
        }
    }

    /**
     * @param int|null $servicetemplategroupId
     */
    public function allocateToHostgroup($servicetemplategroupId = null) {
        $this->layout = 'blank';
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
        $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');

        if (!$ServicetemplategroupsTable->existsById($servicetemplategroupId)) {
            throw new NotFoundException('Invalid service template group');
        }

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return list of services that will be created by the system

            $hostgroupId = $this->request->query('hostgroupId');
            if (!$HostgroupsTable->existsById($hostgroupId)) {
                throw new NotFoundException('Invalid host group');
            }

            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

            $hostIds = $HostgroupsTable->getHostIdsByHostgroupId($hostgroupId);

            $hosts = [];
            foreach ($hostIds as $hostId) {
                $host = $HostsTable->get($hostId);
                $viewHost = new \itnovum\openITCOCKPIT\Core\Views\Host($host->toArray());
                if ($viewHost->isDisabled()) {
                    continue;
                }

                $containerIds = $ContainersTable->resolveChildrenOfContainerIds($viewHost->getContainerId());

                $servicetemplategroup = $ServicetemplategroupsTable->getServicetemplatesforAllocation($servicetemplategroupId, $containerIds);
                $targetHostwithServices = $HostsTable->getServicesForServicetemplateAllocation($hostId);

                $servicetemplatesForDeploy = [];
                foreach ($servicetemplategroup['servicetemplates'] as $servicetemplate) {
                    $doesServicetemplateExistsOnTargetHost = false;
                    $doesServicetemplateExistsOnTargetHostAndIsDisabled = false;
                    $createServiceOnTargetHost = true;

                    $doesServicetemplateExistsOnTargetHost = isset($targetHostwithServices['services'][$servicetemplate['id']]);
                    if (isset($targetHostwithServices['services'][$servicetemplate['id']])) {
                        $doesServicetemplateExistsOnTargetHostAndIsDisabled = (bool)$targetHostwithServices['services'][$servicetemplate['id']]['disabled'];
                    }

                    if ($doesServicetemplateExistsOnTargetHost || $doesServicetemplateExistsOnTargetHostAndIsDisabled) {
                        $createServiceOnTargetHost = false;
                    }

                    $servicetemplatesForDeploy[] = [
                        'servicetemplate'                                    => $servicetemplate,
                        'doesServicetemplateExistsOnTargetHost'              => $doesServicetemplateExistsOnTargetHost,
                        'doesServicetemplateExistsOnTargetHostAndIsDisabled' => $doesServicetemplateExistsOnTargetHostAndIsDisabled,
                        'createServiceOnTargetHost'                          => $createServiceOnTargetHost
                    ];
                }

                $hosts[] = [
                    'host'     => $viewHost->toArray(),
                    'services' => $servicetemplatesForDeploy
                ];
            }

            $this->set('hostsWithServicetemplatesForDeploy', $hosts);
            $this->viewBuilder()->setOption('serialize', ['hostsWithServicetemplatesForDeploy']);
            return;
        }

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            //Create the services out of the service template group on the selected host

            $hostId = $this->request->data('Host.id');
            if (!$HostsTable->existsById($hostId)) {
                throw new NotFoundException('Invalid host');
            }

            $servicetemplateIds = $this->request->data('Servicetemplates._ids');
            if (empty($servicetemplateIds)) {
                //No service templates selected...
                $this->set('success', false);
                $this->set('message', __('No service template ids set'));
                $this->viewBuilder()->setOption('serialize', ['success', 'message']);
                return;
            }

            /** @var $HosttemplatesTable HosttemplatesTable */
            $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
            /** @var $ServicetemplatesTable ServicetemplatesTable */
            $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
            /** @var $ServicesTable ServicesTable */
            $ServicesTable = TableRegistry::getTableLocator()->get('Services');

            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

            $host = $HostsTable->get($hostId);
            $hostContactsAndContactgroupsById = $HostsTable->getContactsAndContactgroupsById($host->get('id'));
            $hosttemplateContactsAndContactgroupsById = $HosttemplatesTable->getContactsAndContactgroupsById($host->get('hosttemplate_id'));

            $newServiceIds = [];
            $errors = [];
            foreach ($servicetemplateIds as $servicetemplateId) {
                $servicetemplate = $ServicetemplatesTable->getServicetemplateForDiff($servicetemplateId);

                $servicename = $servicetemplate['Servicetemplate']['name'];

                $serviceData = ServiceComparisonForSave::getServiceSkeleton($hostId, $servicetemplateId);
                $ServiceComparisonForSave = new ServiceComparisonForSave(
                    ['Service' => $serviceData],
                    $servicetemplate,
                    $hostContactsAndContactgroupsById,
                    $hosttemplateContactsAndContactgroupsById
                );
                $serviceData = $ServiceComparisonForSave->getDataForSaveForAllFields();
                $serviceData['uuid'] = \itnovum\openITCOCKPIT\Core\UUID::v4();

                //Add required fields for validation
                $serviceData['servicetemplate_flap_detection_enabled'] = $servicetemplate['Servicetemplate']['flap_detection_enabled'];
                $serviceData['servicetemplate_flap_detection_on_ok'] = $servicetemplate['Servicetemplate']['flap_detection_on_ok'];
                $serviceData['servicetemplate_flap_detection_on_warning'] = $servicetemplate['Servicetemplate']['flap_detection_on_warning'];
                $serviceData['servicetemplate_flap_detection_on_critical'] = $servicetemplate['Servicetemplate']['flap_detection_on_critical'];
                $serviceData['servicetemplate_flap_detection_on_unknown'] = $servicetemplate['Servicetemplate']['flap_detection_on_unknown'];

                $service = $ServicesTable->newEntity($serviceData);

                $ServicesTable->save($service);
                if ($service->hasErrors()) {
                    $errors[] = $service->getErrors();
                } else {
                    //No errors

                    $extDataForChangelog = $ServicesTable->resolveDataForChangelog(['Service' => $serviceData]);
                    $changelog_data = $this->Changelog->parseDataForChangelog(
                        'add',
                        'services',
                        $service->get('id'),
                        OBJECT_SERVICE,
                        $host->get('container_id'),
                        $User->getId(),
                        $host->get('name') . '/' . $servicename,
                        array_merge(['Service' => $serviceData], $extDataForChangelog)
                    );

                    if ($changelog_data) {
                        CakeLog::write('log', serialize($changelog_data));
                    }

                    $newServiceIds[] = $service->get('id');
                }
            }


            $this->set('success', true);
            $this->set('services', ['_ids' => $newServiceIds]);
            $this->set('hostId', $hostId);
            $this->set('errors', $errors);
            $this->viewBuilder()->setOption('serialize', ['success', 'services', 'hostId', 'errors']);
            return;
        }
    }

    /**
     * @param int|null $servicetemplategroupId
     */
    public function allocateToMatchingHostgroup($servicetemplategroupId = null) {
        $this->layout = 'blank';
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
        $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        $ServicetemplateCache = new KeyValueStore();

        if (!$ServicetemplategroupsTable->existsById($servicetemplategroupId)) {
            throw new NotFoundException('Invalid service template group');
        }

        $servicetemplategroupName = $ServicetemplategroupsTable->getServicetemplategroupNameById($servicetemplategroupId);

        //Find matching host group
        $MY_RIGHTS = [];
        if ($this->hasRootPrivileges === false) {
            $MY_RIGHTS = $this->MY_RIGHTS;
        }

        try {
            $hostgroup = $HostgroupsTable->getHostgroupByName($servicetemplategroupName, $MY_RIGHTS);
        } catch (RecordNotFoundException $e) {
            $this->response->statusCode(400);
            $this->set('success', false);
            $this->set('message', __('No matching host group "%s" found.', $servicetemplategroupName));
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
            return;
        }

        $hostgroupId = $hostgroup->get('id');

        //Gather list of services that will be created by the system
        if (!$HostgroupsTable->existsById($hostgroupId)) {
            throw new NotFoundException('Invalid host group');
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $hostIds = $HostgroupsTable->getHostIdsByHostgroupId($hostgroupId);

        $newServiceIds = [];
        $errors = [];
        foreach ($hostIds as $hostId) {
            $host = $HostsTable->get($hostId);
            $viewHost = new \itnovum\openITCOCKPIT\Core\Views\Host($host->toArray());
            if ($viewHost->isDisabled()) {
                continue;
            }

            $containerIds = $ContainersTable->resolveChildrenOfContainerIds($viewHost->getContainerId());

            $hostContactsAndContactgroupsById = $HostsTable->getContactsAndContactgroupsById($host->get('id'));
            $hosttemplateContactsAndContactgroupsById = $HosttemplatesTable->getContactsAndContactgroupsById($host->get('hosttemplate_id'));

            $servicetemplategroup = $ServicetemplategroupsTable->getServicetemplatesforAllocation($servicetemplategroupId, $containerIds);
            $targetHostwithServices = $HostsTable->getServicesForServicetemplateAllocation($hostId);

            foreach ($servicetemplategroup['servicetemplates'] as $servicetemplate) {
                $doesServicetemplateExistsOnTargetHost = false;
                $doesServicetemplateExistsOnTargetHostAndIsDisabled = false;
                $createServiceOnTargetHost = true;

                $doesServicetemplateExistsOnTargetHost = isset($targetHostwithServices['services'][$servicetemplate['id']]);
                if (isset($targetHostwithServices['services'][$servicetemplate['id']])) {
                    $doesServicetemplateExistsOnTargetHostAndIsDisabled = (bool)$targetHostwithServices['services'][$servicetemplate['id']]['disabled'];
                }

                if ($doesServicetemplateExistsOnTargetHost || $doesServicetemplateExistsOnTargetHostAndIsDisabled) {
                    $createServiceOnTargetHost = false;
                }

                if ($createServiceOnTargetHost === true) {
                    //Create the Service

                    $servicetemplateId = $servicetemplate['id'];

                    if (!$ServicetemplateCache->has($servicetemplateId)) {
                        $ServicetemplateCache->set($servicetemplateId, $ServicetemplatesTable->getServicetemplateForDiff($servicetemplateId));
                    }

                    $servicetemplate = $ServicetemplateCache->get($servicetemplateId);
                    $servicename = $servicetemplate['Servicetemplate']['name'];

                    $serviceData = ServiceComparisonForSave::getServiceSkeleton($hostId, $servicetemplateId);
                    $ServiceComparisonForSave = new ServiceComparisonForSave(
                        ['Service' => $serviceData],
                        $servicetemplate,
                        $hostContactsAndContactgroupsById,
                        $hosttemplateContactsAndContactgroupsById
                    );
                    $serviceData = $ServiceComparisonForSave->getDataForSaveForAllFields();
                    $serviceData['uuid'] = \itnovum\openITCOCKPIT\Core\UUID::v4();

                    //Add required fields for validation
                    $serviceData['servicetemplate_flap_detection_enabled'] = $servicetemplate['Servicetemplate']['flap_detection_enabled'];
                    $serviceData['servicetemplate_flap_detection_on_ok'] = $servicetemplate['Servicetemplate']['flap_detection_on_ok'];
                    $serviceData['servicetemplate_flap_detection_on_warning'] = $servicetemplate['Servicetemplate']['flap_detection_on_warning'];
                    $serviceData['servicetemplate_flap_detection_on_critical'] = $servicetemplate['Servicetemplate']['flap_detection_on_critical'];
                    $serviceData['servicetemplate_flap_detection_on_unknown'] = $servicetemplate['Servicetemplate']['flap_detection_on_unknown'];

                    $service = $ServicesTable->newEntity($serviceData);

                    $ServicesTable->save($service);
                    if ($service->hasErrors()) {
                        $errors[] = $service->getErrors();
                    } else {
                        //No errors

                        $extDataForChangelog = $ServicesTable->resolveDataForChangelog(['Service' => $serviceData]);
                        $changelog_data = $this->Changelog->parseDataForChangelog(
                            'add',
                            'services',
                            $service->get('id'),
                            OBJECT_SERVICE,
                            $host->get('container_id'),
                            $User->getId(),
                            $host->get('name') . '/' . $servicename,
                            array_merge(['Service' => $serviceData], $extDataForChangelog)
                        );

                        if ($changelog_data) {
                            CakeLog::write('log', serialize($changelog_data));
                        }

                        $newServiceIds[] = $service->get('id');
                    }


                }

            }
        }

        $this->set('success', true);
        $this->set('services', ['_ids' => $newServiceIds]);
        $this->set('message', __('Created %s new services', sizeof($newServiceIds)));
        $this->set('errors', $errors);
        $this->viewBuilder()->setOption('serialize', ['success', 'services', 'errors', 'message']);
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
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_SERVICETEMPLATEGROUP, [], $this->hasRootPrivileges);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), OBJECT_SERVICETEMPLATEGROUP, [], $this->hasRootPrivileges);
        }

        $this->set('containers', Api::makeItJavaScriptAble($containers));
        $this->viewBuilder()->setOption('serialize', ['containers']);
    }

    /**
     * @param int|null $containerId
     */
    public function loadServicetemplatesByContainerId($containerId = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

        if (!$ContainersTable->existsById($containerId)) {
            throw new NotFoundException(__('Invalid container id'));
        }

        $containerId = $ContainersTable->resolveChildrenOfContainerIds($containerId);
        $servicetemplates = $ServicetemplatesTable->getServicetemplatesByContainerId($containerId, 'list');
        $servicetemplates = Api::makeItJavaScriptAble($servicetemplates);

        $this->set('servicetemplates', $servicetemplates);
        $this->viewBuilder()->setOption('serialize', ['servicetemplates']);
    }

    public function loadServicetemplategroupsByString() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->query('selected');

        $ServicetemplategroupsFilter = new ServicetemplategroupsFilter($this->request);

        $ServicetemplategroupsConditions = new ServicetemplategroupsConditions($ServicetemplategroupsFilter->indexFilter());
        $ServicetemplategroupsConditions->setContainerIds($this->MY_RIGHTS);

        /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
        $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');

        $servicetemplategroups = Api::makeItJavaScriptAble(
            $ServicetemplategroupsTable->getServicetemplategroupsForAngular($ServicetemplategroupsConditions, $selected)
        );

        $this->set('servicetemplategroups', $servicetemplategroups);
        $this->viewBuilder()->setOption('serialize', ['servicetemplategroups']);
    }

    public function loadHostgroupsByString() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');

        $selected = $this->request->query('selected');

        $HostgroupFilter = new HostgroupFilter($this->request);

        $HostgroupCondition = new HostgroupConditions($HostgroupFilter->indexFilter());

        //Only get host groups where the user has write permissions
        $HostgroupCondition->setContainerIds($this->getWriteContainers());


        $hostgroups = Api::makeItJavaScriptAble(
            $HostgroupsTable->getHostgroupsForAngular($HostgroupCondition, $selected)
        );

        $this->set('hostgroups', $hostgroups);
        $this->viewBuilder()->setOption('serialize', ['hostgroups']);
    }

}
