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
use App\Model\Table\ContactsToContactgroupsTable;
use App\Model\Table\ContainersTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\KeyValueStore;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\ContactgroupsFilter;


/**
 * @property Contactgroup $Contactgroup
 * @property Container $Container
 * @property Contact $Contact
 * @property User $User
 * @property Changelog $Changelog
 *
 * @property AppPaginatorComponent $Paginator
 */
class ContactgroupsController extends AppController {

    public $uses = [
        'Contactgroup',
        'Container',
        'Contact',
        'User',
    ];

    public $layout = 'blank';


    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
        /** @var $ContactsToContactgroupsTable ContactsToContactgroupsTable */
        $ContactsToContactgroupsTable = TableRegistry::getTableLocator()->get('ContactsToContactgroups');

        $ContactgroupsFilter = new ContactgroupsFilter($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $ContactgroupsFilter->getPage());

        $MY_RIGHTS = [];
        if ($this->hasRootPrivileges === false) {
            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
            $MY_RIGHTS = $ContainersTable->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        }
        $contactgroups = $ContactgroupsTable->getContactgroupsIndex($ContactgroupsFilter, $PaginateOMat, $MY_RIGHTS);
        foreach ($contactgroups as $index => $contactgroup) {
            $contactgroups[$index]['Contactgroup']['allow_edit'] = $this->isWritableContainer($contactgroup['Contactgroup']['container']['parent_id']);
            $contactgroups[$index]['Contactgroup']['contact_count'] = $ContactsToContactgroupsTable->getContactsCountByContactgroupId($contactgroup['Contactgroup']['id']);
        }


        $this->set('all_contactgroups', $contactgroups);
        $toJson = ['all_contactgroups', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_contactgroups', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');

        if (!$ContactgroupsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid contact group'));
        }

        $contactgroup = $ContactgroupsTable->getContactgroupById($id);
        if (!$this->allowedByContainerId($contactgroup['Contactgroup']['container']['parent_id'])) {
            throw new ForbiddenException('403 Forbidden');
        }

        $this->set('contactgroup', $contactgroup);
        $this->set('_serialize', ['contactgroup']);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post')) {
            /** @var $ContactgroupsTable ContactgroupsTable */
            $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
            $this->request->data['Contactgroup']['uuid'] = \itnovum\openITCOCKPIT\Core\UUID::v4();
            $this->request->data['Contactgroup']['container']['containertype_id'] = CT_CONTACTGROUP;
            $contactgroup = $ContactgroupsTable->newEntity();
            $contactgroup = $ContactgroupsTable->patchEntity($contactgroup, $this->request->data('Contactgroup'));

            $ContactgroupsTable->save($contactgroup);
            if ($contactgroup->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $contactgroup->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors
                $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
                $extDataForChangelog = $ContactgroupsTable->resolveDataForChangelog($this->request->data);
                Cache::clear(false, 'permissions');
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'add',
                    'contactgroups',
                    $contactgroup->get('id'),
                    OBJECT_CONTACTGROUP,
                    $contactgroup->get('container')->get('parent_id'),
                    $User->getId(),
                    $contactgroup->get('container')->get('name'),
                    array_merge($this->request->data, $extDataForChangelog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($contactgroup); // REST API ID serialization
                    return;
                }
            }
            $this->set('contactgroup', $contactgroup);
            $this->set('_serialize', ['contactgroup']);
        }
    }


    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');

        if (!$ContactgroupsTable->existsById($id)) {
            throw new NotFoundException(__('Contact group not found'));
        }

        $contactgroup = $ContactgroupsTable->getContactgroupForEdit($id);
        $contactgroupForChangeLog = $contactgroup;

        if (!$this->isWritableContainer($contactgroup['Contactgroup']['container']['parent_id'])) {
            $this->render403();
            return;
        }

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return contact information
            $this->set('contactgroup', $contactgroup);
            $this->set('_serialize', ['contactgroup']);
            return;
        }

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            //Update contact data
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

            $contactgroupEntity = $ContactgroupsTable->get($id, [
                'contain' => [
                    'Containers'
                ]
            ]);
            $contactgroupEntity->setAccess('uuid', false);
            $contactgroupEntity = $ContactgroupsTable->patchEntity($contactgroupEntity, $this->request->data('Contactgroup'));
            $contactgroupEntity->id = $id;

            $ContactgroupsTable->save($contactgroupEntity);
            if ($contactgroupEntity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $contactgroupEntity->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors

                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'edit',
                    'contactgroups',
                    $contactgroupEntity->get('id'),
                    OBJECT_CONTACTGROUP,
                    $contactgroupEntity->get('container')->get('parent_id'),
                    $User->getId(),
                    $contactgroupEntity->get('container')->get('name'),
                    array_merge($ContactgroupsTable->resolveDataForChangelog($this->request->data), $this->request->data),
                    array_merge($ContactgroupsTable->resolveDataForChangelog($contactgroupForChangeLog), $contactgroupForChangeLog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($contactgroupEntity); // REST API ID serialization
                    return;
                }
            }
            $this->set('contactgroup', $contactgroupEntity);
            $this->set('_serialize', ['contactgroup']);
        }
    }

    public function delete($id) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');

        if (!$ContactgroupsTable->existsById($id)) {
            throw new NotFoundException(__('Contact group not found'));
        }

        $contactgroupEntity = $ContactgroupsTable->get($id, [
            'contain' => [
                'Containers'
            ]
        ]);

        if (!$this->isWritableContainer($contactgroupEntity->get('container')->get('parent_id'))) {
            $this->render403();
            return;
        }

        if (!$ContactgroupsTable->allowDelete($id)) {
            $usedBy = [
                [
                    'baseUrl' => '#',
                    'state'   => 'ContactgroupsUsedBy',
                    'message' => __('Used by other objects'),
                    'module'  => 'Core'
                ]
            ];

            $this->response->statusCode(400);
            $this->set('success', false);
            $this->set('id', $id);
            $this->set('message', __('Issue while deleting contact'));
            $this->set('usedBy', $usedBy);
            $this->set('_serialize', ['success', 'id', 'message', 'usedBy']);
            return;
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $container = $ContainersTable->get($contactgroupEntity->get('container')->get('id'), [
            'contain' => [
                'Contactgroups'
            ]
        ]);

        if ($ContainersTable->delete($container)) {
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            Cache::clear(false, 'permissions');
            $changelog_data = $this->Changelog->parseDataForChangelog(
                'delete',
                'contactgroup',
                $id,
                OBJECT_CONTACTGROUP,
                $contactgroupEntity->get('container')->get('parent_id'),
                $User->getId(),
                $contactgroupEntity->get('container')->get('name'),
                $contactgroupEntity->toArray()
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


    public function copy($id = null) {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');

        if ($this->request->is('get')) {
            $contactgroups = $ContactgroupsTable->getContactgroupsForCopy(func_get_args());
            $this->set('contactgroups', $contactgroups);
            $this->set('_serialize', ['contactgroups']);
            return;
        }

        $hasErrors = false;

        if ($this->request->is('post')) {
            $Cache = new KeyValueStore();

            $postData = $this->request->data('data');
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            $userId = $User->getId();

            foreach ($postData as $index => $contactgroupData) {
                if (!isset($contactgroupData['Contactgroup']['id'])) {
                    //Create/clone contact group
                    $sourceContactgroupId = $contactgroupData['Source']['id'];
                    if (!$Cache->has($sourceContactgroupId)) {
                        $sourceContactgroup = $ContactgroupsTable->get($sourceContactgroupId, [
                            'contain' => [
                                'Containers',
                                'Contacts'
                            ]
                        ])->toArray();
                        $contacts = Hash::extract($sourceContactgroup['contacts'], '{n}.id');
                        $sourceContactgroup['contacts'] = $contacts;

                        $Cache->set($sourceContactgroup['id'], $sourceContactgroup);
                    }

                    $sourceContactgroup = $Cache->get($sourceContactgroupId);

                    $newContactgroupData = [
                        'description' => $contactgroupData['Contactgroup']['description'],
                        'uuid'        => \itnovum\openITCOCKPIT\Core\UUID::v4(),
                        'container'   => [
                            'name'             => $contactgroupData['Contactgroup']['container']['name'],
                            'containertype_id' => CT_CONTACTGROUP,
                            'parent_id'        => $sourceContactgroup['container']['parent_id']
                        ],
                        'contacts'    => [
                            '_ids' => $sourceContactgroup['contacts']
                        ]
                    ];

                    $newContactgroupEntity = $ContactgroupsTable->newEntity($newContactgroupData);
                }

                $action = 'copy';
                if (isset($contactgroupData['Contact']['id'])) {
                    //Update existing contact
                    //This happens, if a user copy multiple contacts, and one run into an validation error
                    //All contacts without validation errors got already saved to the database
                    $newContactgroupEntity = $ContactgroupsTable->get($contactgroupData['Contactgroup']['id']);
                    $newContactgroupEntity = $ContactgroupsTable->patchEntity($newContactgroupEntity, $contactgroupData['Contactgroup']);
                    $newContactgroupData = $newContactgroupEntity->toArray();
                    $action = 'edit';
                }
                $ContactgroupsTable->save($newContactgroupEntity);

                $postData[$index]['Error'] = [];
                if ($newContactgroupEntity->hasErrors()) {
                    $hasErrors = true;
                    $postData[$index]['Error'] = $newContactgroupEntity->getErrors();
                } else {
                    //No errors
                    $postData[$index]['Contactgroup']['id'] = $newContactgroupEntity->get('id');

                    $changelog_data = $this->Changelog->parseDataForChangelog(
                        $action,
                        'contactgroups',
                        $postData[$index]['Contactgroup']['id'],
                        OBJECT_CONTACTGROUP,
                        $newContactgroupEntity->get('container')->get('parent_id'),
                        $userId,
                        $newContactgroupEntity->get('container')->get('name'),
                        ['Contactgroup' => $newContactgroupData]
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
     * @todo Refactor with Cake4
     */
    public function usedBy($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if (!$this->Contactgroup->exists($id)) {
            throw new NotFoundException(__('Invalid contact group'));
        }

        $this->Contactgroup->bindModel([
            'hasAndBelongsToMany' => [
                'Hosttemplate'      => [
                    'className' => 'Hosttemplate',
                    'joinTable' => 'contactgroups_to_hosttemplates',
                    'type'      => 'INNER'
                ],
                'Host'              => [
                    'className' => 'Host',
                    'joinTable' => 'contactgroups_to_hosts',
                    'type'      => 'INNER'
                ],
                'Servicetemplate'   => [
                    'className' => 'Servicetemplate',
                    'joinTable' => 'contactgroups_to_servicetemplates',
                    'type'      => 'INNER'
                ],
                'Service'           => [
                    'className' => 'Service',
                    'joinTable' => 'contactgroups_to_services',
                    'type'      => 'INNER'
                ],
                'Hostescalation'    => [
                    'className' => 'Hostescalation',
                    'joinTable' => 'contactgroups_to_hostescalations',
                    'type'      => 'INNER'
                ],
                'Serviceescalation' => [
                    'className' => 'Serviceescalation',
                    'joinTable' => 'contactgroups_to_serviceescalations',
                    'type'      => 'INNER'
                ],
            ]
        ]);

        $contactgroupWithRelations = $this->Contactgroup->find('first', [
            'recursive'  => -1,
            'contain'    => [
                'Container',
                'Hosttemplate'    => [
                    'fields' => [
                        'Hosttemplate.id',
                        'Hosttemplate.name'
                    ]
                ],
                'Host'            => [
                    'fields' => [
                        'Host.id',
                        'Host.name',
                        'Host.address'
                    ]
                ],
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.id',
                        'Servicetemplate.name'
                    ]
                ],
                'Service'         => [
                    'fields'          => [
                        'Service.id',
                        'Service.name'
                    ],
                    'Host'            => [
                        'fields' => [
                            'Host.name'
                        ]
                    ],
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.name'
                        ]
                    ]
                ],
                'Hostescalation.id',
                'Serviceescalation.id'
            ],
            'conditions' => [
                'Contactgroup.id' => $id
            ]
        ]);

        /* Format service name for api "hostname|Service oder Service template name" */
        array_walk($contactgroupWithRelations['Service'], function (&$service) {
            $serviceName = $service['name'];
            if (empty($service['name'])) {
                $serviceName = $service['Servicetemplate']['name'];
            }
            $service['name'] = sprintf('%s|%s', $service['Host']['name'], $serviceName);
        });

        //Sort host template, host, service template and service by name
        foreach (['Hosttemplate', 'Host', 'Servicetemplate', 'Service'] as $modelName) {
            $contactgroupWithRelations[$modelName] = Hash::sort($contactgroupWithRelations[$modelName], '{n}.name', 'asc', [
                    'type'       => 'natural',
                    'ignoreCase' => true
                ]
            );
        }
        if (!$this->allowedByContainerId(Hash::extract($contactgroupWithRelations, 'Contactgroup.container_id'), false)) {
            $this->render403();
            return;
        }
        $this->set(compact(['contactgroupWithRelations']));
        $this->set('_serialize', ['contactgroupWithRelations']);
        $this->set('back_url', $this->referer());
    }


    /****************************
     *       AJAX METHODS       *
     ****************************/

    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');


        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
        }

        $this->set('containers', Api::makeItJavaScriptAble($containers));
        $this->set('_serialize', ['containers']);
    }

    public function loadContacts($containerIds = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');

        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerIds);
        $contacts = $ContactsTable->contactsByContainerId($containerIds, 'list');
        $contacts = Api::makeItJavaScriptAble($contacts);

        $data = [
            'contacts' => $contacts,
        ];
        $this->set($data);
        $this->set('_serialize', array_keys($data));
    }
}
