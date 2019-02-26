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
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatecommandargumentvaluesTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\TimeperiodsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\HosttemplateFilter;


/**
 * @property Hosttemplate $Hosttemplate
 * @property Timeperiod $Timeperiod
 * @property Contact $Contact
 * @property Contactgroup $Contactgroup
 * @property Container $Container
 * @property Customvariable $Customvariable
 * @property Hosttemplatecommandargumentvalue $Hosttemplatecommandargumentvalue
 *
 * @property AppPaginatorComponent $Paginator
 */
class HosttemplatesController extends AppController {
    public $uses = [
        'Hosttemplate',
        'Timeperiod',
        'Command',
        'Contact',
        'Contactgroup',
        'Container',
        'Customvariable',
        'Hosttemplatecommandargumentvalue',
        'Hostcommandargumentvalue',
        'Hostgroup',
        'Documentation'
    ];

    //public $layout = 'Admin.default';
    public $layout = 'blank';

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');

        $HosttemplateFilter = new HosttemplateFilter($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $HosttemplateFilter->getPage());

        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges) {
            $MY_RIGHTS = [];
        }
        $hosttemplates = $HosttemplatesTable->getHosttemplatesIndex($HosttemplateFilter, $PaginateOMat, $MY_RIGHTS);

        foreach ($hosttemplates as $index => $hosttemplate) {
            $hosttemplates[$index]['Hosttemplate']['allow_edit'] = true;
            if ($this->hasRootPrivileges === false) {
                $hosttemplates[$index]['Hosttemplate']['allow_edit'] = $this->isWritableContainer($hosttemplate['Hosttemplate']['container_id']);
            }
        }


        $this->set('all_hosttemplates', $hosttemplates);
        $toJson = ['all_hosttemplates', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_hosttemplates', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    /**
     * @param null|int $id
     */
    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');

        if (!$HosttemplatesTable->existsById($id)) {
            throw new NotFoundException(__('Invalid host template'));
        }

        $hosttemplate = $HosttemplatesTable->getHosttemplateById($id, [
            'Containers',
            'Hosttemplatecommandargumentvalues',
            'Customvariables'
        ]);


        if (!$this->allowedByContainerId($hosttemplate['Hosttemplate']['container']['id'])) {
            throw new ForbiddenException('403 Forbidden');
        }

        $this->set('hosttemplate', $hosttemplate);
        $this->set('_serialize', ['hosttemplate']);
    }

    /**
     * @param null|int $hosttemplatetype_id
     */
    public function add($hosttemplatetype_id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post')) {
            /** @var $HosttemplatesTable HosttemplatesTable */
            $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
            $this->request->data['Hosttemplate']['uuid'] = UUID::v4();
            $this->request->data['Hosttemplate']['hosttemplatetype_id'] = GENERIC_HOSTTEMPLATE;

            if ($hosttemplatetype_id !== null && is_numeric($hosttemplatetype_id)) {
                //Legacy???
                $this->request->data['Hosttemplate']['hosttemplatetype_id'] = $hosttemplatetype_id;
            }

            $hosttemplate = $HosttemplatesTable->newEntity();
            $hosttemplate = $HosttemplatesTable->patchEntity($hosttemplate, $this->request->data('Hosttemplate'));

            $HosttemplatesTable->save($hosttemplate);
            if ($hosttemplate->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $hosttemplate->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors

                $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

                $extDataForChangelog = $HosttemplatesTable->resolveDataForChangelog($this->request->data);
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'add',
                    'hosttemplates',
                    $hosttemplate->get('id'),
                    OBJECT_HOSTTEMPLATE,
                    $hosttemplate->get('container_id'),
                    $User->getId(),
                    $hosttemplate->get('name'),
                    array_merge($this->request->data, $extDataForChangelog)
                );

                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }


                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($hosttemplate); // REST API ID serialization
                    return;
                }
            }
            $this->set('hosttemplate', $hosttemplate);
            $this->set('_serialize', ['hosttemplate']);
        }
    }

    /**
     * @param null|int $id
     * @param null|int $hosttemplatetype_id
     * @deprecated
     */
    public function edit($id = null, $hosttemplatetype_id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');

        if (!$HosttemplatesTable->existsById($id)) {
            throw new NotFoundException(__('Host template not found'));
        }

        $hosttemplate = $HosttemplatesTable->getHosttemplateForEdit($id);
        $hosttemplateForChangeLog = $hosttemplate;

        if (!$this->allowedByContainerId($hosttemplate['Hosttemplate']['container_id'])) {
            $this->render403();
            return;
        }

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return contact information
            $commands = $CommandsTable->getCommandByTypeAsList(HOSTCHECK_COMMAND);
            $this->set('commands', Api::makeItJavaScriptAble($commands));
            $this->set('hosttemplate', $hosttemplate);
            $this->set('_serialize', ['hosttemplate', 'commands']);
            return;
        }

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            //Update contact data
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            $hosttemplateEntity = $HosttemplatesTable->get($id);
            $hosttemplateEntity = $HosttemplatesTable->patchEntity($hosttemplateEntity, $this->request->data('Hosttemplate'));
            $HosttemplatesTable->save($hosttemplateEntity);
            if ($hosttemplateEntity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $hosttemplateEntity->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors

                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'edit',
                    'hosttemplates',
                    $hosttemplateEntity->id,
                    OBJECT_HOSTTEMPLATE,
                    $hosttemplateEntity->get('container_id'),
                    $User->getId(),
                    $hosttemplateEntity->name,
                    array_merge($HosttemplatesTable->resolveDataForChangelog($this->request->data), $this->request->data),
                    array_merge($HosttemplatesTable->resolveDataForChangelog($hosttemplateForChangeLog), $hosttemplateForChangeLog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($hosttemplateEntity); // REST API ID serialization
                    return;
                }
            }
            $this->set('hosttemplate', $hosttemplateEntity);
            $this->set('_serialize', ['hosttemplate']);
        }
    }

    /**
     * @param null $id
     * @deprecated
     */
    public function delete($id = null) {
        $userId = $this->Auth->user('id');
        if (!$this->Hosttemplate->exists($id)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Hosttemplate->id = $id;
        $hosttemplate = $this->Hosttemplate->find('first', [
            'recursive'  => -1,
            'contain'    => [
                'Container'
            ],
            'conditions' => [
                'Hosttemplate.id' => $id,
            ]
        ]);

        if (!$this->allowedByContainerId(Hash::extract($hosttemplate, 'Container.id'))) {
            $this->render403();
            return;
        }
        $redirect = $this->Hosttemplate->redirect($this->request->params, ['action' => 'index']);
        $flashHref = $this->Hosttemplate->flashRedirect($this->request->params, ['action' => 'usedBy']);
        $flashHref[] = $this->Hosttemplate->id;
        $flashHref[] = $hosttemplate['Hosttemplate']['hosttemplatetype_id'];

        if ($this->Hosttemplate->__allowDelete($id)) {
            if ($this->Hosttemplate->delete()) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $id,
                    OBJECT_HOSTTEMPLATE,
                    $hosttemplate['Hosttemplate']['container_id'],
                    $userId,
                    $hosttemplate['Hosttemplate']['name'],
                    $hosttemplate
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                //Delete Documentation record if exists
                $documentation = $this->Documentation->findByUuid($hosttemplate['Hosttemplate']['uuid']);
                if (isset($documentation['Documentation']['id'])) {
                    $this->Documentation->delete($documentation['Documentation']['id']);
                    unset($documentation);
                }


                //Hosttemplate deleted, now we need to delete all hosts + services that are using this template
                $this->loadModel('Host');
                $hosts = $this->Host->find('all', [
                    'conditions' => [
                        'Host.hosttemplate_id' => $id,
                    ],
                ]);
                foreach ($hosts as $host) {
                    $this->Host->__delete($host, $this->Auth->user('id'));
                }

                $this->setFlash(__('Hosttemplate deleted'));

                $this->redirect($redirect);
            }
            $this->setFlash(__('Could not delete hosttemplate'), false);
            $this->redirect($redirect);
        }
        $this->setFlash(__('Could not delete hosttemplate: <a href="' . Router::url($flashHref) . '">') . $hosttemplate['Hosttemplate']['name'] . '</a>', false);
        $this->redirect($redirect);
    }

    /**
     * @param null $id
     * @deprecated
     */
    public function copy($id = null) {
        //get the source ids from the Hosttemplates which shall be copied
        $sourceIds = func_get_args();
        $userId = $this->Auth->user('id');
        //get the data of the Hosttemplates
        $hosttemplates = $this->Hosttemplate->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Hosttemplate.id' => $sourceIds,
            ],
            'contain'    => [
                'Contact'                          => [
                    'fields' => [
                        'Contact.id',
                        'Contact.name'
                    ],
                ],
                'Contactgroup'                     => [
                    'fields'    => [
                        'Contactgroup.id',
                    ],
                    'Container' => [
                        'fields' => [
                            'Container.name'
                        ]
                    ]
                ],
                'Hostgroup'                        => [
                    'fields'    => [
                        'Hostgroup.id',
                    ],
                    'Container' => [
                        'fields' => [
                            'Container.name'
                        ]
                    ]
                ],
                'CheckCommand'                     => [
                    'fields' => [
                        'CheckCommand.id',
                        'CheckCommand.name',
                    ]
                ],
                'Customvariable'                   => [
                    'fields' => [
                        'name',
                        'value',
                        'objecttype_id'
                    ],
                ],
                'NotifyPeriod'                     => [
                    'fields' => [
                        'NotifyPeriod.id',
                        'NotifyPeriod.name',
                    ]
                ],
                'CheckPeriod'                      => [
                    'fields' => [
                        'CheckPeriod.id',
                        'CheckPeriod.name',
                    ]
                ],
                'Hosttemplatecommandargumentvalue' => [
                    'fields' => [
                        'Hosttemplatecommandargumentvalue.commandargument_id',
                        'Hosttemplatecommandargumentvalue.value',
                    ]
                ],
            ],
        ]);

        $hosttemplates = Hash::combine($hosttemplates, '{n}.Hosttemplate.id', '{n}');
        $oldHosttemplatesCopy = $hosttemplates;

        foreach ($oldHosttemplatesCopy as $key => $oldHosttemplate) {
            unset($oldHosttemplatesCopy[$key]['Hosttemplate']['created']);
            unset($oldHosttemplatesCopy[$key]['Hosttemplate']['modified']);
            unset($oldHosttemplatesCopy[$key]['Hosttemplate']['id']);
            unset($oldHosttemplatesCopy[$key]['Hosttemplate']['uuid']);
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $datasource = $this->Hosttemplate->getDataSource();
            try {
                $datasource->begin();
                foreach ($this->request->data['Hosttemplate'] as $newHosttemplate) {
                    $contactIds = Hash::extract($oldHosttemplatesCopy[$newHosttemplate['source']], 'Contact.{n}.id');
                    $contactgroupIds = Hash::extract($oldHosttemplatesCopy[$newHosttemplate['source']], 'Contactgroup.{n}.id');
                    $hostgroupIds = Hash::extract($oldHosttemplatesCopy[$newHosttemplate['source']], 'Hostgroup.{n}.id');
                    $hosttemplateCommandargumentValues = (!empty($oldHosttemplatesCopy[$newHosttemplate['source']]['Hosttemplatecommandargumentvalue'])) ? Hash::remove($oldHosttemplatesCopy[$newHosttemplate['source']]['Hosttemplatecommandargumentvalue'], '{n}.hosttemplate_id') : [];
                    $customVariables = (!empty($oldHosttemplatesCopy[$newHosttemplate['source']]['Customvariable'])) ? Hash::remove(
                        $oldHosttemplatesCopy[$newHosttemplate['source']]['Customvariable'], '{n}.object_id'
                    ) : [];
                    $newHosttemplateData = [
                        'Hosttemplate'                     => Hash::merge($oldHosttemplatesCopy[$newHosttemplate['source']]['Hosttemplate'], [
                            'uuid'         => $this->Hosttemplate->createUUID(),
                            'name'         => $newHosttemplate['name'],
                            'description'  => $newHosttemplate['description'],
                            'Contact'      => $contactIds,
                            'Contactgroup' => $contactgroupIds,
                            'Hostgroup'    => $hostgroupIds,
                        ]),
                        'Customvariable'                   => $customVariables,
                        'Hosttemplatecommandargumentvalue' => $hosttemplateCommandargumentValues,
                        'Contact'                          => $contactIds,
                        'Contactgroup'                     => $contactgroupIds,
                        'Hostgroup'                        => $hostgroupIds
                    ];
                    if (!empty($hosttemplates[$newHosttemplate['source']]['Contactgroup'])) {
                        $contactgroups = [];
                        foreach ($hosttemplates[$newHosttemplate['source']]['Contactgroup'] as $contactgroup) {
                            $contactgroups[] = [
                                'id'   => $contactgroup['id'],
                                'name' => $contactgroup['Container']['name']
                            ];
                        }
                        $hosttemplates[$newHosttemplate['source']]['Contactgroup'] = $contactgroups;
                    }
                    if (!empty($hosttemplates[$newHosttemplate['source']]['Hostgroup'])) {
                        $hostgroups = [];
                        foreach ($hosttemplates[$newHosttemplate['source']]['Hostgroup'] as $hostgroup) {
                            $hostgroups[] = [
                                'id'   => $hostgroup['id'],
                                'name' => $hostgroup['Container']['name']
                            ];
                        }
                        $hosttemplates[$newHosttemplate['source']]['Hostgroup'] = $hostgroups;
                    }

                    unset($oldHosttemplatesCopy[$newHosttemplate['source']]['Contact']);
                    unset($oldHosttemplatesCopy[$newHosttemplate['source']]['Contactgroup']);
                    unset($oldHosttemplatesCopy[$newHosttemplate['source']]['Hostgroup']);

                    $this->Hosttemplate->create();
                    if (!$this->Hosttemplate->saveAll($newHosttemplateData)) {
                        throw new Exception("Hosttemplate could not be saved");
                    }
                    $changelog_data = $this->Changelog->parseDataForChangelog(
                        $this->params['action'],
                        $this->params['controller'],
                        $this->Hosttemplate->id,
                        OBJECT_HOSTTEMPLATE,
                        $hosttemplates[$newHosttemplate['source']]['Hosttemplate']['container_id'],
                        $userId,
                        $newHosttemplate['name'],
                        Hash::merge(
                            $hosttemplates[$newHosttemplate['source']], [
                            'Servicetemplate' => [
                                'name'        => $newHosttemplate['name'],
                                'description' => $newHosttemplate['description'],
                            ]
                        ])
                    );
                    if ($changelog_data) {
                        CakeLog::write('log', serialize($changelog_data));
                    }
                }

                $datasource->commit();

                $this->setFlash(__('Hosttemplate successfully copied'));
                $this->redirect(['action' => 'index']);
            } catch (Exception $e) {
                $datasource->rollback();
                //@TODO switch for error msg
                $this->setFlash(__('Hosttemplates could not be copied'), false);
                $this->redirect(['action' => 'index']);
            }
        }


        $this->set(compact('hosttemplates'));
        $this->set('back_url', $this->referer());
    }


    public function usedBy($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if (!$this->Hosttemplate->exists($id)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        $hosttemplate = $this->Hosttemplate->find('first', [
            'recursive'  => -1,
            'fields'     => [
                'Hosttemplate.name'
            ],
            'conditions' => [
                'Hosttemplate.id' => $id
            ]
        ]);

        if (!$this->allowedByContainerId(Hash::extract($hosttemplate, 'Container.id'), false)) {
            $this->render403();
            return;
        }

        $this->loadModel('Host');
        $hosts = $this->Host->find('all', [
            'recursive'  => -1,
            'order'      => [
                'Host.name' => 'ASC',
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
            'conditions' => [
                'HostsToContainers.container_id' => $this->MY_RIGHTS,
                'Host.hosttemplate_id'           => $id,
            ],
            'contain'    => [
                'Container'
            ],
            'fields'     => [
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.address',
            ],
            'group'      => 'Host.id'
        ]);

        $all_hosts = [];
        foreach ($hosts as $host) {
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($host);
            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $Host->getContainerIds());
                $allowEdit = $ContainerPermissions->hasPermission();
            }
            $tmpRecord = [
                'Host' => $Host->toArray()
            ];
            $tmpRecord['Host']['allow_edit'] = $allowEdit;
            $all_hosts[] = $tmpRecord;
        }


        $this->set(compact(['all_hosts', 'hosttemplate']));
        $this->set('_serialize', ['all_hosts', 'hosttemplate']);
    }

    /****************************
     *       AJAX METHODS       *
     ****************************/

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

        $hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
        $hostgroups = Api::makeItJavaScriptAble($hostgroups);

        $this->set(compact(['timeperiods', 'checkperiods', 'contacts', 'contactgroups', 'hostgroups']));
        $this->set('_serialize', ['timeperiods', 'checkperiods', 'contacts', 'contactgroups', 'hostgroups']);
    }

    /**
     * @param null|int $hosttemplateId
     */
    public function loadContainers($hosttemplateId = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_HOSTTEMPLATE, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), OBJECT_HOSTTEMPLATE, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        }

        $areContainersRestricted = false;
        if (is_numeric($hosttemplateId)) {
            //Edit mode

            /** @var $HosttemplatesTable HosttemplatesTable */
            $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
            /** @var $HostsTable HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

            $hosttemplatesContainerId = $HosttemplatesTable->getContainerIdById($hosttemplateId);
            $usedContainerIds = $HostsTable->getHostPrimaryContainerIdsByHosttemplateId($hosttemplateId);

            if (!empty($usedContainerIds)) {
                //This host template is used by some hosts.
                //Container options needs to be needs to be restricted if the hosts are using some sub containers...
                $restrictedContainers = [];
                foreach ($containers as $containerId => $path) {
                    $containerId = (int)$containerId;
                    if (in_array($containerId, [ROOT_CONTAINER, $hosttemplatesContainerId], true)) {
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
        $commands = $CommandsTable->getCommandByTypeAsList(HOSTCHECK_COMMAND);

        $this->set('commands', Api::makeItJavaScriptAble($commands));
        $this->set('_serialize', ['commands']);
    }

    /**
     * @param null $commandId
     * @param null $hosttemplateId
     */
    public function loadCommandArguments($commandId = null, $hosttemplateId = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        /** @var $CommandargumentsTable CommandargumentsTable */
        $CommandargumentsTable = TableRegistry::getTableLocator()->get('Commandarguments');

        //HosttemplatecommandargumentvaluesTable

        if (!$CommandsTable->existsById($commandId)) {
            throw new NotFoundException(__('Invalid command'));
        }

        $hosttemplatecommandargumentvalues = [];

        if ($hosttemplateId != null) {
            //User passed an hosttemplateId, so we are in a non add mode!
            //Check if the hosttemplate has defined command arguments

            /** @var $HosttemplatecommandargumentvaluesTable HosttemplatecommandargumentvaluesTable */
            $HosttemplatecommandargumentvaluesTable = TableRegistry::getTableLocator()->get('Hosttemplatecommandargumentvalues');

            $hosttemplateCommandArgumentValues = $HosttemplatecommandargumentvaluesTable->getByHosttemplateIdAndCommandId($hosttemplateId, $commandId);

            foreach ($hosttemplateCommandArgumentValues as $hosttemplateCommandArgumentValue) {
                $hosttemplatecommandargumentvalues[] = [
                    'commandargument_id' => $hosttemplateCommandArgumentValue['commandargument_id'],
                    'hosttemplate_id'    => $hosttemplateCommandArgumentValue['hosttemplate_id'],
                    'value'              => $hosttemplateCommandArgumentValue['value'],
                    'commandargument'    => [
                        'name'       => $hosttemplateCommandArgumentValue['commandargument']['name'],
                        'human_name' => $hosttemplateCommandArgumentValue['commandargument']['human_name'],
                        'command_id' => $hosttemplateCommandArgumentValue['commandargument']['command_id'],
                    ]
                ];
            }
        }

        //Get command arguments
        if (empty($hosttemplatecommandargumentvalues)) {
            //Hosttemplate has no command arguments defined
            //Or we are in hosttemplates/add ?

            //Load command arguments of the check command
            foreach ($CommandargumentsTable->getByCommandId($commandId) as $commandargument) {
                $hosttemplatecommandargumentvalues[] = [
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

        $this->set('hosttemplatecommandargumentvalues', $hosttemplatecommandargumentvalues);
        $this->set('_serialize', ['hosttemplatecommandargumentvalues']);
    }


}
