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

use App\Lib\Exceptions\MissingDbBackendException;
use App\Lib\Interfaces\HoststatusTableInterface;
use App\Lib\Interfaces\ServicestatusTableInterface;
use App\Model\Entity\Changelog;
use App\Model\Table\ChangelogsTable;
use App\Model\Table\ContainersTable;
use App\Model\Table\HostsTable;
use App\Model\Table\ServicegroupsTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplatesTable;
use Cake\Cache\Cache;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\KeyValueStore;
use itnovum\openITCOCKPIT\Core\ServiceConditions;
use itnovum\openITCOCKPIT\Core\ServicegroupConditions;
use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\UUID;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Views\PerfdataChecker;
use itnovum\openITCOCKPIT\Core\Views\Service;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\ServiceFilter;
use itnovum\openITCOCKPIT\Filter\ServicegroupFilter;
use itnovum\openITCOCKPIT\Filter\ServicetemplateFilter;


/**
 * Class ServicegroupsController
 * @package App\Controller
 */
class ServicegroupsController extends AppController {


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
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $ServicegroupFilter->getPage());
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
        $this->viewBuilder()->setOption('serialize', $toJson);
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
        $this->viewBuilder()->setOption('serialize', ['servicegroup']);
    }


    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }


        if ($this->request->is('post')) {
            $User = new User($this->getUser());

            /** @var $ServicegroupsTable ServicegroupsTable */
            $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

            $servicegroup = $ServicegroupsTable->newEmptyEntity();
            $servicegroup = $ServicegroupsTable->patchEntity($servicegroup, $this->request->getData('Servicegroup'));
            $servicegroup->set('uuid', UUID::v4());
            $servicegroup->get('container')->set('containertype_id', CT_SERVICEGROUP);

            $ServicegroupsTable->save($servicegroup);
            if ($servicegroup->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $servicegroup->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                //No errors

                $requestData = $this->request->getData();
                $extDataForChangelog = $ServicegroupsTable->resolveDataForChangelog($requestData);
                /** @var  ChangelogsTable $ChangelogsTable */
                $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

                $changelog_data = $ChangelogsTable->parseDataForChangelog(
                    'add',
                    'servicegroups',
                    $servicegroup->get('id'),
                    OBJECT_SERVICEGROUP,
                    $servicegroup->get('container')->get('parent_id'),
                    $User->getId(),
                    $servicegroup->get('container')->get('name'),
                    array_merge($requestData, $extDataForChangelog)
                );

                if ($changelog_data) {
                    /** @var Changelog $changelogEntry */
                    $changelogEntry = $ChangelogsTable->newEntity($changelog_data);
                    $ChangelogsTable->save($changelogEntry);
                }

                Cache::clear('permissions');

                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($servicegroup); // REST API ID serialization
                    return;
                }
            }
            $this->set('servicegroup', $servicegroup);
            $this->viewBuilder()->setOption('serialize', ['servicegroup']);
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
            //Return service group information
            $this->set('servicegroup', $servicegroup);
            $this->viewBuilder()->setOption('serialize', ['servicegroup']);
            return;
        }

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            $User = new User($this->getUser());
            $servicegroupEntity = $ServicegroupsTable->get($id, [
                'contain' => [
                    'Containers'
                ]
            ]);

            $servicegroupEntity->setAccess('uuid', false);
            $servicegroupEntity = $ServicegroupsTable->patchEntity($servicegroupEntity, $this->request->getData('Servicegroup'));
            $servicegroupEntity->id = $id;

            $ServicegroupsTable->save($servicegroupEntity);
            if ($servicegroupEntity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $servicegroupEntity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                //No errors

                $requestData = $this->request->getData();
                /** @var  ChangelogsTable $ChangelogsTable */
                $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

                $changelog_data = $ChangelogsTable->parseDataForChangelog(
                    'edit',
                    'servicegroups',
                    $servicegroupEntity->id,
                    OBJECT_SERVICEGROUP,
                    $servicegroupEntity->get('container')->get('parent_id'),
                    $User->getId(),
                    $servicegroupEntity->get('container')->get('name'),
                    array_merge($ServicegroupsTable->resolveDataForChangelog($requestData), $requestData),
                    array_merge($ServicegroupsTable->resolveDataForChangelog($servicegroupForChangelog), $servicegroupForChangelog)
                );
                if ($changelog_data) {
                    /** @var Changelog $changelogEntry */
                    $changelogEntry = $ChangelogsTable->newEntity($changelog_data);
                    $ChangelogsTable->save($changelogEntry);
                }

                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($servicegroupEntity); // REST API ID serialization
                    return;
                }
            }
            $this->set('servicegroup', $servicegroupEntity);
            $this->viewBuilder()->setOption('serialize', ['servicegroup']);
        }
    }

    /**
     * @param int|null $id
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $ServicegroupsTable ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if (!$ServicegroupsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Servicegroup'));
        }

        $servicegroup = $ServicegroupsTable->getServicegroupById($id);
        $container = $ContainersTable->get($servicegroup->get('container')->get('id'), [
            'contain' => [
                'Servicegroups'
            ]
        ]);

        if (!$this->allowedByContainerId($servicegroup->get('container')->get('parent_id'))) {
            $this->render403();
            return;
        }

        if ($ContainersTable->allowDelete($container->id, $this->MY_RIGHTS)) {
            if ($ContainersTable->delete($container)) {
                $User = new User($this->getUser());
                /** @var  ChangelogsTable $ChangelogsTable */
                $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

                $changelog_data = $ChangelogsTable->parseDataForChangelog(
                    'delete',
                    'servicegroups',
                    $id,
                    OBJECT_SERVICEGROUP,
                    $container->get('parent_id'),
                    $User->getId(),
                    $container->get('name'),
                    [
                        'Servicegroup' => $servicegroup->toArray()
                    ]
                );
                if ($changelog_data) {
                    /** @var Changelog $changelogEntry */
                    $changelogEntry = $ChangelogsTable->newEntity($changelog_data);
                    $ChangelogsTable->save($changelogEntry);
                }

                $this->set('success', true);
                $this->viewBuilder()->setOption('serialize', ['success']);
                return;
            }

            $this->response = $this->response->withStatus(500);
            $this->set('success', false);
            $this->viewBuilder()->setOption('serialize', ['success']);
        } else {
            $this->response = $this->response->withStatus(500);
            $this->set('success', false);
            $this->set('message', __('Container is not empty'));
            $this->set('containerId', $container->id);
            $this->viewBuilder()->setOption('serialize', ['success', 'message', 'containerId']);
        }

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
            $id = $this->request->getData('Servicegroup.id');
            $serviceIds = $this->request->getData('Servicegroup.services._ids');
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


            $User = new User($this->getUser());
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
                $this->response = $this->response->withStatus(400);
                $this->set('error', $servicegroupEntity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
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
                /** @var  ChangelogsTable $ChangelogsTable */
                $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

                $changelog_data = $ChangelogsTable->parseDataForChangelog(
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
                    /** @var Changelog $changelogEntry */
                    $changelogEntry = $ChangelogsTable->newEntity($changelog_data);
                    $ChangelogsTable->save($changelogEntry);
                }

                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($servicegroupEntity); // REST API ID serialization
                    return;
                }
            }
            $this->set('servicegroup', $servicegroupEntity);
            $this->viewBuilder()->setOption('serialize', ['servicegroup']);
        }
    }

    /**
     * @throws MissingDbBackendException
     */
    public function listToPdf() {
        /** @var $ServicegroupsTable ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');
        /** @var $HoststatusTable HoststatusTableInterface */
        $HoststatusTable = $this->DbBackend->getHoststatusTable();
        /** @var $ServicestatusTable ServicestatusTableInterface */
        $ServicestatusTable = $this->DbBackend->getServicestatusTable();
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $MY_RIGHTS = [];
        if (!$this->hasRootPrivileges) {
            $MY_RIGHTS = $this->MY_RIGHTS;
        }
        $ServicegroupFilter = new ServicegroupFilter($this->request);

        $servicegroups = $ServicegroupsTable->getServicegroupsIndex($ServicegroupFilter, null, $MY_RIGHTS);


        $User = new User($this->getUser());

        $numberOfServicegroups = sizeof($servicegroups);

        $all_servicegroups = [];
        foreach ($servicegroups as $servicegroup) {
            $serviceIds = $ServicegroupsTable->getServiceIdsByServicegroupId($servicegroup['id']);

            $ServiceFilter = new ServiceFilter($this->request);
            $ServiceConditions = new ServiceConditions($ServiceFilter->indexFilter());

            $ServiceConditions->setIncludeDisabled(false);
            $ServiceConditions->setServiceIds($serviceIds);
            $ServiceConditions->setContainerIds($this->MY_RIGHTS);

            $services = [];
            if (!empty($serviceIds)) {
                if ($this->DbBackend->isNdoUtils()) {
                    $services = $ServicesTable->getServiceIndex($ServiceConditions);
                }

                if ($this->DbBackend->isStatusengine3()) {
                    $services = $ServicesTable->getServiceIndexStatusengine3($ServiceConditions);
                }

                if ($this->DbBackend->isCrateDb()) {
                    throw new MissingDbBackendException('MissingDbBackendException');
                }
            }


            $servicegroupServiceUuids = Hash::extract($services, '{n}.Service.uuid');
            $ServicestatusFields = new ServicestatusFields($this->DbBackend);
            $ServicestatusFields->wildcard();
            $servicestatusOfServicegroup = $ServicestatusTable->byUuids($servicegroupServiceUuids, $ServicestatusFields);

            $hostUuids = array_unique(Hash::extract($services, '{n}._matchingData.Hosts.uuid'));

            $HoststatusFields = new HoststatusFields($this->DbBackend);
            $HoststatusFields
                ->currentState();
            $hoststatusCache = $HoststatusTable->byUuid(
                $hostUuids,
                $HoststatusFields
            );

            foreach ($services as $index => $service) {
                $Host = new Host($service['_matchingData']['Hosts']);
                $hoststatus = [];
                if (isset($hoststatusCache[$Host->getUuid()]['Hoststatus'])) {
                    $hoststatus = $hoststatusCache[$Host->getUuid()]['Hoststatus'];
                }
                $services[$index]['Hoststatus'] = $hoststatus;
            }

            $all_servicegroups[] = [
                'Servicegroup'  => $servicegroup,
                'Services'      => $services,
                'Servicestatus' => $servicestatusOfServicegroup
            ];
        }
        $numberOfHosts = sizeof(array_unique(Hash::extract($all_servicegroups, '{n}.Services.{n}._matchingData.Hosts.uuid')));
        $numberOfServices = sizeof(array_unique(Hash::extract($all_servicegroups, '{n}.Services.{n}.uuid')));
        $this->set('servicegroups', $all_servicegroups);
        $this->set('numberOfServicegroups', $numberOfServicegroups);
        $this->set('numberOfServices', $numberOfServices);
        $this->set('numberOfHosts', $numberOfHosts);
        $this->set('User', $User);

        $this->viewBuilder()->setOption(
            'pdfConfig',
            [
                'download' => true,
                'filename' => __('Servicegroups_') . date('dmY_his') . '.pdf'
            ]
        );
    }

    public function extended() {
        //Only ship template
        $User = new User($this->getUser());
        $this->set('username', $User->getFullName());
        $this->viewBuilder()->setOption('serialize', ['username']);
    }


    /****************************
     *       AJAX METHODS       *
     ****************************/

    /**
     * @throws \Exception
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
        $this->viewBuilder()->setOption('serialize', ['containers']);
    }

    /**
     * @param int|null $containerId
     */
    public function loadServicetemplates($containerId = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $containerId = $this->request->getQuery('containerId');
        $selected = $this->request->getQuery('selected');
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
        $this->viewBuilder()->setOption('serialize', ['servicetemplates']);
    }

    /**
     * @param null $id
     * @throws MissingDbBackendException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function loadServicegroupWithServicesById($id = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ServicegroupsTable ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        /** @var $HoststatusTable HoststatusTableInterface */
        $HoststatusTable = $this->DbBackend->getHoststatusTable();

        if (!$ServicegroupsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Hostgroup'));
        }

        $servicegroup = $ServicegroupsTable->getServicegroupById($id);

        $User = new User($this->getUser());
        $UserTime = new UserTime($User->getTimezone(), $User->getDateformat());

        $serviceIds = $ServicegroupsTable->getServiceIdsByServicegroupId($id);

        $ServiceFilter = new ServiceFilter($this->request);
        $ServiceConditions = new ServiceConditions($ServiceFilter->indexFilter());

        $ServiceConditions->setIncludeDisabled(false);
        $ServiceConditions->setServiceIds($serviceIds);
        $ServiceConditions->setContainerIds($this->MY_RIGHTS);

        $all_services = [];
        $services = [];

        if (!empty($serviceIds)) {
            if ($this->DbBackend->isNdoUtils()) {
                /** @var $ServicesTable ServicesTable */
                $ServicesTable = TableRegistry::getTableLocator()->get('Services');
                $services = $ServicesTable->getServiceIndex($ServiceConditions);
            }

            if ($this->DbBackend->isStatusengine3()) {
                /** @var $ServicesTable ServicesTable */
                $ServicesTable = TableRegistry::getTableLocator()->get('Services');
                $services = $ServicesTable->getServiceIndexStatusengine3($ServiceConditions);
            }

            if ($this->DbBackend->isCrateDb()) {
                throw new MissingDbBackendException('MissingDbBackendException');
            }
        }

        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->currentState();

        $servicegroupServicestatusOverview = [
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0
        ];

        $hostContainers = [];
        if ($this->hasRootPrivileges === false) {
            if ($this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
                foreach ($services as $index => $service) {
                    $hostId = $service['_matchingData']['Hosts']['id'];
                    if (!isset($hostContainers[$hostId])) {
                        $hostContainers[$hostId] = $HostsTable->getHostContainerIdsByHostId($hostId);
                    }

                    $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $hostContainers[$hostId]);
                    $services[$index]['allow_edit'] = $ContainerPermissions->hasPermission();
                }
            }
        } else {
            //Root user
            foreach ($services as $index => $service) {
                $services[$index]['allow_edit'] = $this->hasRootPrivileges;
            }
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields
            ->currentState()
            ->lastHardStateChange()
            ->lastStateChange()
            ->lastCheck()
            ->nextCheck();
        $hoststatusCache = $HoststatusTable->byUuid(
            array_unique(Hash::extract($services, '{n}._matchingData.Hosts.uuid')),
            $HoststatusFields
        );

        foreach ($services as $service) {
            $allowEdit = $service['allow_edit'];
            $Host = new Host($service['_matchingData']['Hosts'], $allowEdit);
            if (isset($hoststatusCache[$Host->getUuid()]['Hoststatus'])) {
                $Hoststatus = new Hoststatus($hoststatusCache[$Host->getUuid()]['Hoststatus'], $UserTime);
            } else {
                $Hoststatus = new Hoststatus([], $UserTime);
            }
            $Service = new Service($service, null, $allowEdit);
            $Servicestatus = new Servicestatus($service['Servicestatus'], $UserTime);
            $PerfdataChecker = new PerfdataChecker($Host, $Service, $this->PerfdataBackend, $Servicestatus, $this->DbBackend, $service['service_type']);

            $tmpRecord = [
                'Service'       => $Service->toArray(),
                'Host'          => $Host->toArray(),
                'Hoststatus'    => $Hoststatus->toArray(),
                'Servicestatus' => $Servicestatus->toArray()
            ];
            $tmpRecord['Service']['has_graph'] = $PerfdataChecker->hasPerfdata();
            $all_services[] = $tmpRecord;

            $servicegroupServicestatusOverview[$Servicestatus->currentState()]++;
        }

        $statusOverview = array_combine([
            'ok',
            'warning',
            'critical',
            'unknown'
        ], $servicegroupServicestatusOverview
        );

        $servicegroup = [
            'Servicegroup'  => $servicegroup->toArray(),
            'Services'      => $all_services,
            'StatusSummary' => $statusOverview
        ];

        $this->set('servicegroup', $servicegroup);
        $this->viewBuilder()->setOption('serialize', ['servicegroup']);
    }

    public function loadServicegroupsByContainerId() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $ServicegroupsTable ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

        $containerId = $this->request->getQuery('containerId');
        $resolveContainerIds = $this->request->getQuery('resolveContainerIds', false);

        if (!is_numeric($containerId)) {
            throw new BadRequestException('containerId is missing or not numeric');
        }

        $containerIds = [ROOT_CONTAINER, $containerId];
        if ($containerId == ROOT_CONTAINER) {
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds(ROOT_CONTAINER, true, [CT_SERVICEGROUP]);
        } else if ($containerId !== ROOT_CONTAINER && $resolveContainerIds) {
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId, true, [CT_SERVICEGROUP]);
            $containerIds = array_merge($containerIds, [ROOT_CONTAINER, $containerId]);
        }

        $servicegroups = $ServicegroupsTable->getServicegroupsByContainerId($containerIds, 'list');
        $servicegroups = Api::makeItJavaScriptAble($servicegroups);

        $this->set('servicegroups', $servicegroups);
        $this->viewBuilder()->setOption('serialize', ['servicegroups']);
    }

    public function loadServicegroupsByString() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->getQuery('selected');

        /** @var $ServicegroupsTable ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

        $ServicegroupFilter = new ServicegroupFilter($this->request);

        $ServicegroupConditions = new ServicegroupConditions($ServicegroupFilter->indexFilter());
        $ServicegroupConditions->setContainerIds($this->MY_RIGHTS);

        $servicegroups = Api::makeItJavaScriptAble(
            $ServicegroupsTable->getServicegroupsForAngular($ServicegroupConditions, $selected)
        );

        $this->set('servicegroups', $servicegroups);
        $this->viewBuilder()->setOption('serialize', ['servicegroups']);
    }
}
