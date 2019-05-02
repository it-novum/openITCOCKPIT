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
use App\Model\Table\CommandargumentsTable;
use App\Model\Table\CommandsTable;
use App\Model\Table\ContactgroupsTable;
use App\Model\Table\ContactsTable;
use App\Model\Table\ContainersTable;
use App\Model\Table\ServicegroupsTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplatecommandargumentvaluesTable;
use App\Model\Table\ServicetemplateeventcommandargumentvaluesTable;
use App\Model\Table\ServicetemplatesTable;
use App\Model\Table\TimeperiodsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\KeyValueStore;
use itnovum\openITCOCKPIT\Core\ServiceConditions;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\ServicetemplateFilter;

/**
 * @property Changelog $Changelog
 * @property Servicetemplate $Servicetemplate
 * @property Timeperiod $Timeperiod
 * @property Contact $Contact
 * @property Contactgroup $Contactgroup
 * @property Container $Container
 * @property Customvariable $Customvariable
 * @property Servicetemplatecommandargumentvalue $Servicetemplatecommandargumentvalue
 * @property Servicetemplateeventcommandargumentvalue $Servicetemplateeventcommandargumentvalue
 *
 * @property AppPaginatorComponent $Paginator
 */
class ServicetemplatesController extends AppController {

    public $layout = 'blank';

    public $uses = [
        'Servicetemplate',
        'Service',
        'Timeperiod',
        'Contact',
        'Contactgroup',
        'Servicegroup',
        'Container',
        'Customvariable',
        'Servicetemplatecommandargumentvalue',
        'Servicetemplateeventcommandargumentvalue',
        'Servicetemplategroup',
        'Servicecommandargumentvalue',
        'Serviceeventcommandargumentvalue',
        'Documentation'
    ];

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

        $ServicetemplateFilter = new ServicetemplateFilter($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $ServicetemplateFilter->getPage());

        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges) {
            $MY_RIGHTS = [];
        }
        $servicetemplates = $ServicetemplatesTable->getServicetemplatesIndex($ServicetemplateFilter, $PaginateOMat, $MY_RIGHTS);

        foreach ($servicetemplates as $index => $servicetemplate) {
            $servicetemplates[$index]['Servicetemplate']['allow_edit'] = true;
            if ($this->hasRootPrivileges === false) {
                $servicetemplates[$index]['Servicetemplate']['allow_edit'] = $this->isWritableContainer($servicetemplate['Servicetemplate']['container_id']);
            }
        }


        $this->set('all_servicetemplates', $servicetemplates);
        $toJson = ['all_servicetemplates', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_servicetemplates', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    /**
     * @param null $id
     */
    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

        if (!$ServicetemplatesTable->existsById($id)) {
            throw new NotFoundException(__('Invalid service template'));
        }

        $servicetemplate = $ServicetemplatesTable->getServicetemplateById($id, [
            'Containers',
            'Servicetemplatecommandargumentvalues',
            'Servicetemplateeventcommandargumentvalues',
            'Customvariables'
        ]);


        if (!$this->allowedByContainerId($servicetemplate['Servicetemplate']['container']['id'])) {
            throw new ForbiddenException('403 Forbidden');
        }

        $this->set('servicetemplate', $servicetemplate);
        $this->set('_serialize', ['servicetemplate']);
    }

    /**
     * @param int|null $servicetemplatetype_id
     */
    public function add($servicetemplatetype_id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post')) {
            /** @var $ServicetemplatesTable ServicetemplatesTable */
            $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
            $this->request->data['Servicetemplate']['uuid'] = UUID::v4();
            $this->request->data['Servicetemplate']['servicetemplatetype_id'] = GENERIC_SERVICE;

            if ($servicetemplatetype_id !== null && is_numeric($servicetemplatetype_id)) {
                //Legacy???
                $this->request->data['Servicetemplate']['servicetemplatetype_id'] = $servicetemplatetype_id;
            }

            $servicetemplate = $ServicetemplatesTable->newEntity();
            $servicetemplate = $ServicetemplatesTable->patchEntity($servicetemplate, $this->request->data('Servicetemplate'));

            $ServicetemplatesTable->save($servicetemplate);
            if ($servicetemplate->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $servicetemplate->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors

                $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

                $extDataForChangelog = $ServicetemplatesTable->resolveDataForChangelog($this->request->data);
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'add',
                    'servicetemplates',
                    $servicetemplate->get('id'),
                    OBJECT_SERVICETEMPLATE,
                    $servicetemplate->get('container_id'),
                    $User->getId(),
                    $servicetemplate->get('template_name'),
                    array_merge($this->request->data, $extDataForChangelog)
                );

                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }


                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($servicetemplate); // REST API ID serialization
                    return;
                }
            }
            $this->set('servicetemplate', $servicetemplate);
            $this->set('_serialize', ['servicetemplate']);
        }
    }

    /**
     * @param int|null $id
     * @param int|null $servicetemplatetype_id
     */
    public function edit($id = null, $servicetemplatetype_id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');

        if (!$ServicetemplatesTable->existsById($id)) {
            throw new NotFoundException(__('Service template not found'));
        }

        $servicetemplate = $ServicetemplatesTable->getServicetemplateForEdit($id);
        $servicetemplateForChangeLog = $servicetemplate;

        if (!$this->allowedByContainerId($servicetemplate['Servicetemplate']['container_id'])) {
            $this->render403();
            return;
        }

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return service template information
            $commands = $CommandsTable->getCommandByTypeAsList(CHECK_COMMAND);
            $eventhandlerCommands = $CommandsTable->getCommandByTypeAsList(EVENTHANDLER_COMMAND);
            $this->set('commands', Api::makeItJavaScriptAble($commands));
            $this->set('eventhandlerCommands', Api::makeItJavaScriptAble($eventhandlerCommands));
            $this->set('servicetemplate', $servicetemplate);
            $this->set('_serialize', ['servicetemplate', 'commands', 'eventhandlerCommands']);
            return;
        }

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            //Update service template data
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

            $servicetemplateEntity = $ServicetemplatesTable->get($id);
            $servicetemplateEntity->setAccess('uuid', false);
            $servicetemplateEntity = $ServicetemplatesTable->patchEntity($servicetemplateEntity, $this->request->data('Servicetemplate'));
            $servicetemplateEntity->id = $id;

            $ServicetemplatesTable->save($servicetemplateEntity);
            if ($servicetemplateEntity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $servicetemplateEntity->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors

                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'edit',
                    'servicetemplates',
                    $servicetemplateEntity->id,
                    OBJECT_SERVICETEMPLATE,
                    $servicetemplateEntity->get('container_id'),
                    $User->getId(),
                    $servicetemplateEntity->template_name,
                    array_merge($ServicetemplatesTable->resolveDataForChangelog($this->request->data), $this->request->data),
                    array_merge($ServicetemplatesTable->resolveDataForChangelog($servicetemplateForChangeLog), $servicetemplateForChangeLog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($servicetemplateEntity); // REST API ID serialization
                    return;
                }
            }
            $this->set('servicetemplate', $servicetemplateEntity);
            $this->set('_serialize', ['servicetemplate']);
        }
    }


    /**
     * @param null $id
     * @deprecated
     */
    public function delete($id = null) {
        if (!$this->Servicetemplate->exists($id)) {
            throw new NotFoundException(__('Invalid service template'));
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $servicetemplate = $this->Servicetemplate->find('first', [
            'recursive'  => -1,
            'contain'    => [
                'Container'
            ],
            'conditions' => [
                'Servicetemplate.id' => $id,
            ]
        ]);

        if (!$this->allowedByContainerId(Hash::extract($servicetemplate, 'Container.id'))) {
            $this->render403();
            return;
        }

        $this->Servicetemplate->id = $id;

        if ($this->Servicetemplate->__allowDelete($id)) {
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            if ($this->Servicetemplate->delete()) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $id,
                    OBJECT_SERVICETEMPLATE,
                    $servicetemplate['Servicetemplate']['container_id'],
                    $User->getId(),
                    $servicetemplate['Servicetemplate']['name'],
                    $servicetemplate
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                //Delete Documentation record if exists
                $documentation = $this->Documentation->findByUuid($servicetemplate['Servicetemplate']['uuid']);
                if (isset($documentation['Documentation']['id'])) {
                    $this->Documentation->delete($documentation['Documentation']['id']);
                    unset($documentation);
                }

                //Delete all services that were created using this template
                $this->loadModel('Service');
                $services = $this->Service->find('all', [
                    'conditions' => [
                        'Service.servicetemplate_id' => $id,
                    ],
                ]);
                foreach ($services as $service) {
                    $this->Service->__delete($service, $this->Auth->user('id'));
                }

                $this->set('success', true);
                $this->set('message', __('Service template successfully deleted'));
                $this->set('_serialize', ['success']);
                return;
            }

            $usedBy = [
                [
                    'baseUrl' => '#',
                    'state'   => 'ServicetemplatesUsedBy',
                    'message' => __('Used by other objects'),
                    'module'  => 'Core'
                ]
            ];

            $this->set('success', false);
            $this->set('id', $id);
            $this->set('message', __('Issue while deleting service template'));
            $this->set('usedBy', $usedBy);
            $this->set('_serialize', ['success', 'id', 'message', 'usedBy']);
        }
    }


    /**
     * @param null $id
     * @deprecated
     */
    public function copy($id = null) {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

        if ($this->request->is('get')) {
            $servicetemplates = $ServicetemplatesTable->getServicetemplatesForCopy(func_get_args());
            /** @var $CommandsTable CommandsTable */
            $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
            $commands = $CommandsTable->getCommandByTypeAsList(CHECK_COMMAND);
            $eventhandlerCommands = $CommandsTable->getCommandByTypeAsList(EVENTHANDLER_COMMAND);
            $this->set('servicetemplates', $servicetemplates);
            $this->set('commands', Api::makeItJavaScriptAble($commands));
            $this->set('eventhandlerCommands', Api::makeItJavaScriptAble($eventhandlerCommands));
            $this->set('_serialize', ['servicetemplates', 'commands', 'eventhandlerCommands']);
            return;
        }

        $hasErrors = false;

        if ($this->request->is('post')) {
            $Cache = new KeyValueStore();

            $postData = $this->request->data('data');
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

            foreach ($postData as $index => $servicetemplateData) {
                if (!isset($servicetemplateData['Servicetemplate']['id'])) {
                    //Create/clone servicetemplate
                    $sourceServicetemplateId = $servicetemplateData['Source']['id'];
                    if (!$Cache->has($sourceServicetemplateId)) {
                        $sourceServicetemplate = $ServicetemplatesTable->getServicetemplateForEdit($sourceServicetemplateId);
                        $sourceServicetemplate = $sourceServicetemplate['Servicetemplate'];
                        unset($sourceServicetemplate['id'], $sourceServicetemplate['uuid']);

                        foreach ($sourceServicetemplate['servicetemplatecommandargumentvalues'] as $i => $servicetemplatecommandargumentvalues) {
                            unset($sourceServicetemplate['servicetemplatecommandargumentvalues'][$i]['id']);
                            unset($sourceServicetemplate['servicetemplatecommandargumentvalues'][$i]['servicetemplate_id']);
                        }

                        $Cache->set($sourceServicetemplateId, $sourceServicetemplate);
                    }

                    $sourceServicetemplate = $Cache->get($sourceServicetemplateId);

                    $newServicetemplateData = $sourceServicetemplate;
                    $newServicetemplateData['uuid'] = UUID::v4();
                    $newServicetemplateData['template_name'] = $servicetemplateData['Servicetemplate']['template_name'];
                    $newServicetemplateData['name'] = $servicetemplateData['Servicetemplate']['name'];
                    $newServicetemplateData['description'] = $servicetemplateData['Servicetemplate']['description'];
                    $newServicetemplateData['command_id'] = $servicetemplateData['Servicetemplate']['command_id'];
                    if (!empty($servicetemplateData['Servicetemplate']['servicetemplatecommandargumentvalues'])) {
                        $newServicetemplateData['servicetemplatecommandargumentvalues'] = $servicetemplateData['Servicetemplate']['servicetemplatecommandargumentvalues'];
                    }

                    $newServicetemplateEntity = $ServicetemplatesTable->newEntity($newServicetemplateData);
                }

                $action = 'copy';
                if (isset($servicetemplateData['Servicetemplate']['id'])) {
                    //Update existing servicetemplates
                    //This happens, if a user copy multiple servicetemplates, and one run into an validation error
                    //All servicetemplates without validation errors got already saved to the database
                    $newServicetemplateEntity = $ServicetemplatesTable->get($servicetemplateData['Servicetemplate']['id']);
                    $newServicetemplateEntity = $ServicetemplatesTable->patchEntity($newServicetemplateEntity, $servicetemplateData['Servicetemplate']);
                    $newServicetemplateData = $newServicetemplateEntity->toArray();
                    $action = 'edit';
                }
                $ServicetemplatesTable->save($newServicetemplateEntity);

                $postData[$index]['Error'] = [];
                if ($newServicetemplateEntity->hasErrors()) {
                    $hasErrors = true;
                    $postData[$index]['Error'] = $newServicetemplateEntity->getErrors();
                } else {
                    //No errors
                    $postData[$index]['Servicetemplate']['id'] = $newServicetemplateEntity->get('id');

                    $changelog_data = $this->Changelog->parseDataForChangelog(
                        $action,
                        'servicetemplates',
                        $postData[$index]['Servicetemplate']['id'],
                        OBJECT_SERVICETEMPLATE,
                        [ROOT_CONTAINER],
                        $User->getId(),
                        $newServicetemplateEntity->get('template_name'),
                        ['Servicetemplate' => $newServicetemplateData]
                    );
                    if ($changelog_data) {
                        CakeLog::write('log', serialize($changelog_data));
                    }
                }
            }
        }

        if ($hasErrors) {
            $this->response->statusCode(400);
        }
        $this->set('result', $postData);
        $this->set('_serialize', ['result']);
    }

    /**
     * @param null $id
     * @throws Exception
     * @deprecated
     */
    public function assignGroup($id = null) {
        $servicetmpl = $this->Servicetemplate->find('all', [
            'conditions' => [
                'Servicetemplate.id' => func_get_args(),
            ],
            'contain'    => [
                'Contact'      => [
                    'fields' => [
                        'Contact.id',
                    ],
                ],
                'Contactgroup' => [
                    'fields' => [
                        'Contactgroup.id',
                    ],
                ],
            ],
        ]);

        $servicetemplates = Hash::combine($servicetmpl, '{n}.Servicetemplate.id', '{n}');

        $myServiceTemplates = [];
        $checkedContanerId = null;
        $sameContaner = true;
        foreach ($servicetemplates as $servicetemplate) {
            if (isset($servicetemplate['Servicetemplate']['id'])) {
                if (is_null($checkedContanerId)) {
                    $checkedContanerId = $servicetemplate['Servicetemplate']['container_id'];
                } else if ($checkedContanerId != $servicetemplate['Servicetemplate']['container_id']) {
                    $sameContaner = false;
                    break;
                }
                $myServiceTemplates[$servicetemplate['Servicetemplate']['id']] = $servicetemplate['Servicetemplate']['name'];
            }
        }
        if (is_null($checkedContanerId)) {
            $this->setFlash(__('Please choose at least one Servicetemplate'), false);
            $this->redirect(['action' => 'index']);
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $resolvedPathContainerName = $ContainersTable->easyPath([$checkedContanerId], OBJECT_SERVICETEMPLATEGROUP, [], $this->hasRootPrivileges);
        if (!isset($resolvedPathContainerName[$checkedContanerId])) {
            $this->setFlash(__('Please choose at least one Servicetemplate'), false);
            $this->redirect(['action' => 'index']);
        }
        $checkedContanerName = $resolvedPathContainerName[$checkedContanerId];
        if (!$sameContaner) {
            $this->setFlash(__('Servicetemplates must belong to the same container'), false);
            $this->redirect(['action' => 'index']);
        }
        if (!in_array($checkedContanerId, $this->MY_RIGHTS)) {
            $this->setFlash(__('You have no permission to view these servicetemplates'), false);
            $this->redirect(['action' => 'index']);
        }

        $myContainerId = $ContainersTable->resolveChildrenOfContainerIds($checkedContanerId);
        $allServicetemplates = $this->Servicetemplate->servicetemplatesByContainerId($myContainerId, 'list');
        $allServicetemplateGroups = $this->Servicetemplategroup->find('all', [
            'conditions' => ['Container.parent_id' => $checkedContanerId],
        ]);
        $servicetemplateGroupList = [];
        foreach ($allServicetemplateGroups as $servicetemplateGroup) {
            $servicetemplateGroupList[$servicetemplateGroup['Servicetemplategroup']['id']] = $servicetemplateGroup['Container']['name'];
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Servicetemplate'] = $this->request->data['Servicetemplategroup']['Servicetemplate'];
            $this->request->data['Container']['containertype_id'] = CT_SERVICETEMPLATEGROUP;
            if ($this->request->data['service-form']['new'] === '1') {
                unset($this->request->data['Servicetemplategroup']['id']);
                App::uses('UUID', 'Lib');
                $this->request->data['Servicetemplategroup']['uuid'] = UUID::v4();
            } else {
                foreach ($allServicetemplateGroups as $myServicetemplateGroup) {
                    if ($myServicetemplateGroup['Servicetemplategroup']['id'] == $this->request->data['Servicetemplategroup']['id']) {
                        foreach ($myServicetemplateGroup['Servicetemplate'] as $myServiceTemlate) {
                            if (!in_array($myServiceTemlate['id'], $this->request->data['Servicetemplate'])) {
                                $this->request->data['Servicetemplate'][] = $myServiceTemlate['id'];
                            }
                        }
                    }
                }
                unset($this->request->data['Container']);
            }
            unset($this->request->data['service-form']);

            if ($this->Servicetemplategroup->saveAll($this->request->data)) {
                $this->setFlash(__('All Servicetemplates were successfully allocated'));
                $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('servicetemplates', 'myServiceTemplates', 'allServicetemplates', 'servicetemplateGroupList', 'checkedContanerName', 'checkedContanerId'));
        $this->set('back_url', $this->referer());
    }

    /**
     * @param null $id
     * @deprecated
     */
    public function usedBy($id = null) {

        $this->layout = 'angularjs';
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if (!$this->Servicetemplate->exists($id)) {
            throw new NotFoundException(__('Invalid servicetemplate'));
        }

        $servicetemplate = $this->Servicetemplate->findById($id);

        if (!$this->allowedByContainerId(Hash::extract($servicetemplate, 'Container.id'), false)) {
            $this->render403();

            return;
        }
        $ServiceConditions = new ServiceConditions();
        $ServiceConditions->setContainerIds($this->MY_RIGHTS);
        $query = [
            'recursive'  => -1,
            'conditions' => [
                'Servicetemplate.id'             => $id,
                'HostsToContainers.container_id' => $ServiceConditions->getContainerIds()
            ],
            'contain'    => ['Servicetemplate', 'Host'],
            'fields'     => [
                'Service.id',
                'Service.name',

                'Servicetemplate.id',
                'Servicetemplate.name',

                'Host.name',
                'Host.id',
                'Host.uuid',
                'Host.address',

                'HostsToContainers.container_id',
            ],
            'joins'      => [
                [
                    'table'      => 'hosts_to_containers',
                    'alias'      => 'HostsToContainers',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ],
            ],
            'group'      => [
                'Service.id',
            ],
        ];

        $services = $this->Service->find('all', $query);

        $hostContainers = [];
        if (!empty($services) && $this->hasRootPrivileges === false && $this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
            $hostIds = array_unique(Hash::extract($services, '{n}.Host.id'));
            $_hostContainers = $this->Host->find('all', [
                'contain'    => [
                    'Container',
                ],
                'fields'     => [
                    'Host.id',
                    'Container.*',
                ],
                'conditions' => [
                    'Host.id' => $hostIds,
                ],
            ]);
            foreach ($_hostContainers as $host) {
                $hostContainers[$host['Host']['id']] = Hash::extract($host['Container'], '{n}.id');
            }
        }


        foreach ($services as $service) {
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

            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service, $allowEdit);
            $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($service, null, $allowEdit);


            $tmpRecord = [
                'Service' => $Service->toArray(),
                'Host'    => $Host->toArray(),
            ];
            $all_services[] = $tmpRecord;
        }


        $this->set(compact(['all_services', 'servicetemplate']));
        $this->set('_serialize', ['all_services', 'servicetemplate']);
    }

    /****************************
     *       AJAX METHODS       *
     ****************************/

    /**
     * @param int|null $servicetemplateId
     * @throws Exception
     */
    public function loadContainers($servicetemplateId = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_SERVICETEMPLATE, [], $this->hasRootPrivileges, [CT_SERVICETEMPLATEGROUP]);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), OBJECT_SERVICETEMPLATE, [], $this->hasRootPrivileges, [CT_SERVICETEMPLATEGROUP]);
        }

        $areContainersRestricted = false;
        if (is_numeric($servicetemplateId)) {
            //Edit mode

            /** @var $ServicetemplatesTable ServicetemplatesTable */
            $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
            /** @var $ServicesTable ServicesTable */
            $ServicesTable = TableRegistry::getTableLocator()->get('Services');

            $servicetemplatesContainerId = $ServicetemplatesTable->getContainerIdById($servicetemplateId);
            $usedContainerIds = $ServicesTable->getHostPrimaryContainerIdsByServicetemplateId($servicetemplateId);

            if (!empty($usedContainerIds)) {
                //This service template is used by some some.
                //Container options needs to be needs to be restricted if the services/hosts are using some sub containers...
                $restrictedContainers = [];
                foreach ($containers as $containerId => $path) {
                    $containerId = (int)$containerId;
                    if (in_array($containerId, [ROOT_CONTAINER, $servicetemplatesContainerId], true)) {
                        $restrictedContainers[$containerId] = $path;
                    } else {
                        $areContainersRestricted = true;
                    }
                }
                $containers = $restrictedContainers;
            }
        }


        $this->set('containers', Api::makeItJavaScriptAble($containers));
        $this->set('areContainersRestricted', $areContainersRestricted);
        $this->set('_serialize', ['containers', 'areContainersRestricted']);
    }

    public function loadCommands() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        $commands = $CommandsTable->getCommandByTypeAsList(CHECK_COMMAND);

        $eventhandlerCommands = [
            0 => __('None')
        ];

        //Use foreach because of arra_merge remove the keys and adding None after getCommandByTypeAsList()
        //will display "None" as the last element in the select box
        foreach ($CommandsTable->getCommandByTypeAsList(EVENTHANDLER_COMMAND) as $eventhandlerCommndId => $eventhandlerCommandName) {
            $eventhandlerCommands[$eventhandlerCommndId] = $eventhandlerCommandName;
        }

        $this->set('commands', Api::makeItJavaScriptAble($commands));
        $this->set('eventhandlerCommands', Api::makeItJavaScriptAble($eventhandlerCommands));
        $this->set('_serialize', ['commands', 'eventhandlerCommands']);
    }

    /**
     * @param int|null $commandId
     * @param int|null $servicetemplateId
     */
    public function loadCommandArguments($commandId = null, $servicetemplateId = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        /** @var $CommandargumentsTable CommandargumentsTable */
        $CommandargumentsTable = TableRegistry::getTableLocator()->get('Commandarguments');

        //ServicetemplatecommandargumentvaluesTable

        if (!$CommandsTable->existsById($commandId)) {
            throw new NotFoundException(__('Invalid command'));
        }

        $servicetemplatecommandargumentvalues = [];

        if ($servicetemplateId != null) {
            //User passed an servicetemplateId, so we are in a non add mode!
            //Check if the servicetemplate has defined command arguments

            /** @var $ServicetemplatecommandargumentvaluesTable ServicetemplatecommandargumentvaluesTable */
            $ServicetemplatecommandargumentvaluesTable = TableRegistry::getTableLocator()->get('Servicetemplatecommandargumentvalues');

            $servicetemplateCommandArgumentValues = $ServicetemplatecommandargumentvaluesTable->getByServicetemplateIdAndCommandId($servicetemplateId, $commandId);

            foreach ($servicetemplateCommandArgumentValues as $servicetemplateCommandArgumentValue) {
                $servicetemplatecommandargumentvalues[] = [
                    'commandargument_id' => $servicetemplateCommandArgumentValue['commandargument_id'],
                    'servicetemplate_id' => $servicetemplateCommandArgumentValue['servicetemplate_id'],
                    'value'              => $servicetemplateCommandArgumentValue['value'],
                    'commandargument'    => [
                        'name'       => $servicetemplateCommandArgumentValue['commandargument']['name'],
                        'human_name' => $servicetemplateCommandArgumentValue['commandargument']['human_name'],
                        'command_id' => $servicetemplateCommandArgumentValue['commandargument']['command_id'],
                    ]
                ];
            }
        }

        //Get command arguments
        if (empty($servicetemplatecommandargumentvalues)) {
            //Servicetemplate has no command arguments defined
            //Or we are in servicetemplates/add ?

            //Load command arguments of the check command
            foreach ($CommandargumentsTable->getByCommandId($commandId) as $commandargument) {
                $servicetemplatecommandargumentvalues[] = [
                    'commandargument_id' => $commandargument['Commandargument']['id'],
                    'value'              => '',
                    'commandargument'    => [
                        'name'       => $commandargument['Commandargument']['name'],
                        'human_name' => $commandargument['Commandargument']['human_name'],
                        'command_id' => $commandargument['Commandargument']['command_id'],
                    ]
                ];
            }
        };

        $this->set('servicetemplatecommandargumentvalues', $servicetemplatecommandargumentvalues);
        $this->set('_serialize', ['servicetemplatecommandargumentvalues']);
    }

    /**
     * @param int|null $commandId
     * @param int|null $servicetemplateId
     */
    public function loadEventhandlerCommandArguments($commandId = null, $servicetemplateId = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        /** @var $CommandargumentsTable CommandargumentsTable */
        $CommandargumentsTable = TableRegistry::getTableLocator()->get('Commandarguments');

        //ServicetemplateeventcommandargumentvaluesTable

        if (!$CommandsTable->existsById($commandId)) {
            throw new NotFoundException(__('Invalid command'));
        }

        $servicetemplateeventhandlercommandargumentvalues = [];

        if ($servicetemplateId != null) {
            //User passed an servicetemplateId, so we are in a non add mode!
            //Check if the servicetemplate has defined command arguments for the event handler

            /** @var $ServicetemplateeventcommandargumentvaluesTable ServicetemplateeventcommandargumentvaluesTable */
            $ServicetemplateeventcommandargumentvaluesTable = TableRegistry::getTableLocator()->get('Servicetemplateeventcommandargumentvalues');

            $servicetemplateEventhandlerCommandArgumentValues = $ServicetemplateeventcommandargumentvaluesTable->getByServicetemplateIdAndCommandId($servicetemplateId, $commandId);

            foreach ($servicetemplateEventhandlerCommandArgumentValues as $servicetemplateEventhandlerCommandArgumentValue) {
                $servicetemplateeventhandlercommandargumentvalues[] = [
                    'commandargument_id' => $servicetemplateEventhandlerCommandArgumentValue['commandargument_id'],
                    'servicetemplate_id' => $servicetemplateEventhandlerCommandArgumentValue['servicetemplate_id'],
                    'value'              => $servicetemplateEventhandlerCommandArgumentValue['value'],
                    'commandargument'    => [
                        'name'       => $servicetemplateEventhandlerCommandArgumentValue['commandargument']['name'],
                        'human_name' => $servicetemplateEventhandlerCommandArgumentValue['commandargument']['human_name'],
                        'command_id' => $servicetemplateEventhandlerCommandArgumentValue['commandargument']['command_id'],
                    ]
                ];
            }
        }

        //Get command arguments
        if (empty($servicetemplateeventhandlercommandargumentvalues)) {
            //Servicetemplate has no command arguments defined
            //Or we are in servicetemplates/add ?

            //Load event handler command arguments of the check command
            foreach ($CommandargumentsTable->getByCommandId($commandId) as $commandargument) {
                $servicetemplateeventhandlercommandargumentvalues[] = [
                    'commandargument_id' => $commandargument['Commandargument']['id'],
                    'value'              => '',
                    'commandargument'    => [
                        'name'       => $commandargument['Commandargument']['name'],
                        'human_name' => $commandargument['Commandargument']['human_name'],
                        'command_id' => $commandargument['Commandargument']['command_id'],
                    ]
                ];
            }
        };

        $this->set('servicetemplateeventhandlercommandargumentvalues', $servicetemplateeventhandlercommandargumentvalues);
        $this->set('_serialize', ['servicetemplateeventhandlercommandargumentvalues']);
    }

    /**
     * @param int|null $container_id
     */
    public function loadElementsByContainerId($container_id = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var $ServicegroupsTable ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

        if (!$ContainersTable->existsById($container_id)) {
            throw new NotFoundException(__('Invalid Container'));
        }

        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($container_id);

        $timeperiods = $TimeperiodsTable->timeperiodsByContainerId($containerIds, 'list');
        $timeperiods = Api::makeItJavaScriptAble($timeperiods);
        $checkperiods = $timeperiods;

        $contacts = $ContactsTable->contactsByContainerId($containerIds, 'list');
        $contacts = Api::makeItJavaScriptAble($contacts);

        $contactgroups = $ContactgroupsTable->getContactgroupsByContainerId($containerIds, 'list', 'id');
        $contactgroups = Api::makeItJavaScriptAble($contactgroups);

        $servicegroups = $ServicegroupsTable->getServicegroupsByContainerId($containerIds, 'list');
        $servicegroups = Api::makeItJavaScriptAble($servicegroups);

        $this->set('timeperiods', $timeperiods);
        $this->set('checkperiods', $checkperiods);
        $this->set('contacts', $contacts);
        $this->set('contactgroups', $contactgroups);
        $this->set('servicegroups', $servicegroups);
        $this->set('_serialize', ['timeperiods', 'checkperiods', 'contacts', 'contactgroups', 'servicegroups']);
    }

    /**
     * @param int|null $containerId
     */
    public function loadServicetemplatesByContainerId($containerId = null) {
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

}
