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
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;


/**
 * @property Hosttemplate $Hosttemplate
 * @property Timeperiod $Timeperiod
 * @property Command $Command
 * @property Contact $Contact
 * @property Contactgroup $Contactgroup
 * @property Container $Container
 * @property Customvariable $Customvariable
 * @property Commandargument $Commandargument
 * @property Hosttemplatecommandargumentvalue $Hosttemplatecommandargumentvalue
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
        'Commandargument',
        'Hosttemplatecommandargumentvalue',
        'Hostcommandargumentvalue',
        'Hostgroup',
        'Documentation'
    ];
    public $layout = 'Admin.default';

    public $components = [
        'ListFilter.ListFilter',
        'CustomValidationErrors',
    ];
    public $helpers = [
        'ListFilter.ListFilter',
        'CustomValidationErrors',
        'CustomVariables',
    ];

    public $listFilters = [
        'index' => [
            'fields' => [
                'Hosttemplate.name' => [
                    'label' => 'Templatename',
                    'searchType' => 'wildcard',
                ],
            ],
        ],
    ];


    public function index() {
        $query = [
            'order' => [
                'Hosttemplate.name' => 'asc',
            ],
            'conditions' => [
                'Container.id' => $this->MY_RIGHTS,
                'Hosttemplate.hosttemplatetype_id' => GENERIC_HOSTTEMPLATE,
            ],
            'contain' => [
                'Container',
            ],
            'fields' => [
                'Hosttemplate.id',
                'Hosttemplate.uuid',
                'Hosttemplate.name',
                'Hosttemplate.description',
                'Hosttemplate.container_id',
                'Container.id',
                'Container.parent_id',
            ],
        ];
        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);

        if ($this->isApiRequest()) {
            unset($query['limit']);
            $all_hosttemplates = $this->Hosttemplate->find('all', $query);
        } else {
            $all_hosttemplates = $this->Paginator->paginate();
        }

        $this->set(compact(['all_hosttemplates']));
        $this->set('_serialize', ['all_hosttemplates']);
    }

    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();

        }
        if (!$this->Hosttemplate->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }
        $hosttemplate = $this->Hosttemplate->findById($id);
        if (!$this->allowedByContainerId(Hash::extract($hosttemplate, 'Container.id'))) {
            $this->render403();

            return;
        }
        $this->set(compact(['hosttemplate']));
        $this->set('_serialize', ['hosttemplate']);
    }

    public function edit($id = null, $hosttemplatetype_id = null) {
        $userId = $this->Auth->user('id');
        $customFildsToRefill = [
            'Hosttemplate' => [
                'notification_interval',
                'notify_on_recovery',
                'notify_on_down',
                'notify_on_unreachable',
                'notify_on_flapping',
                'notify_on_downtime',
                'check_interval',
                'retry_interval',
                'flap_detection_enabled',
                'flap_detection_on_up',
                'flap_detection_on_down',
                'flap_detection_on_unreachable',
                'priority',
                'active_checks_enabled',
            ],

        ];
        $this->CustomValidationErrors->checkForRefill($customFildsToRefill);

        $this->Hosttemplate->id = $id;
        if (!$this->Hosttemplate->exists()) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        $this->loadModel('Command');

        $hosttemplate = $this->Hosttemplate->find('first', [
            'recursive' => -1,
            'conditions' => [
                'Hosttemplate.id = ' => $id,
            ],
            'contain' => [
                'Contactgroup' => ['Container'],
                'Hostgroup' => ['Container'],
                'CheckCommand',
                'Container',
                'Customvariable',
                'NotifyPeriod',
                'CheckPeriod',
                'Contact',
                'Hosttemplatecommandargumentvalue' => ['Commandargument'],
                'Host'
            ],
        ]);
        $oldHosttemplateCheckCommandId = $hosttemplate['Hosttemplate']['command_id'];

        if (!$this->allowedByContainerId(Hash::extract($hosttemplate, 'Container.id'))) {
            $this->render403();

            return;
        }

        //Fehlende bzw. neu angelegte CommandArgummente ermitteln und anzeigen
        $commandarguments = $this->Commandargument->find('all', [
            'recursive' => -1,
            'conditions' => [
                'Commandargument.command_id' => $hosttemplate['CheckCommand']['id'],
            ],
        ]);

        // Data required for changelog
        $contacts = $this->Contact->find('list');
        $contactgroups = $this->Contactgroup->findList();
        $timeperiods = $this->Timeperiod->find('list');
        $commands = $this->Command->hostCommands('list');
        $hostgroups = $this->Hostgroup->find('list');
        // End changelog

        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOSTTEMPLATE, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_HOSTTEMPLATE, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        }

        if(count($hosttemplate['Host']) > 0){
            $newContainers = [];
            foreach($containers as $containerId => $containerName){
                if(!in_array($containerId, [ROOT_CONTAINER, $hosttemplate['Hosttemplate']['container_id']]))
                    continue;
                $newContainers[$containerId] = $containerName;
            }
            $containers = $newContainers;
        }

        // Data to refill form
        if ($this->request->is('post') || $this->request->is('put')) {
            $containerId = $this->request->data('Hosttemplate.container_id');
        } else {
            $containerId = $hosttemplate['Hosttemplate']['container_id'];
        }

        $containerId = array_unique([ROOT_CONTAINER, $containerId]);
        $containerIds = $this->Tree->resolveChildrenOfContainerIds($containerId);

        $_timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
        $_contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
        $_contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
        $_hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');


        //Fix that we dont lose any unsaved host macros, because of vaildation error
        if (isset($this->request->data['Customvariable'])) {
            $hosttemplate['Customvariable'] = $this->request->data['Customvariable'];
        }
        $this->Frontend->set('data_placeholder', __('Please choose a contact'));
        $this->Frontend->set('data_placeholder_empty', __('No entries found'));
        $this->Frontend->setJson('lang_minutes', __('minutes'));
        $this->Frontend->setJson('lang_seconds', __('seconds'));
        $this->Frontend->setJson('lang_and', __('and'));
        $this->Frontend->setJson('hosttemplate_id', $hosttemplate['Hosttemplate']['id']);

        $this->set('back_url', $this->referer());
        $this->set(compact(['hosttemplate', 'containers', 'commands', 'commandarguments']));

        if ($this->request->is('post') || $this->request->is('put')) {
            //default structure for *.Contact/Contractgroup arrays
            $ext_data_for_changelog = [
                'Contact' => [],
                'Contactgroup' => [],
                'Hostgroup' => []
            ];

            if ($this->request->data('Hosttemplate.Contact')) {
                if ($contactsForChangelog = $this->Contact->find('list', [
                    'conditions' => [
                        'Contact.id' => $this->request->data['Hosttemplate']['Contact'],
                    ],
                ])
                ) {
                    foreach ($contactsForChangelog as $contactId => $contactName) {
                        $ext_data_for_changelog['Contact'][] = [
                            'id' => $contactId,
                            'name' => $contactName,
                        ];
                    }
                    unset($contactsForChangelog);
                }
            }
            if ($this->request->data('Hosttemplate.Contactgroup')) {
                if ($contactgroupsForChangelog = $this->Contactgroup->find('all', [
                    'recursive' => -1,
                    'contain' => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                    ],
                    'fields' => [
                        'Contactgroup.id',
                    ],
                    'conditions' => [
                        'Contactgroup.id' => $this->request->data['Hosttemplate']['Contactgroup'],
                    ],
                ])
                ) {
                    foreach ($contactgroupsForChangelog as $contactgroupData) {
                        $ext_data_for_changelog['Contactgroup'][] = [
                            'id' => $contactgroupData['Contactgroup']['id'],
                            'name' => $contactgroupData['Container']['name'],
                        ];
                    }
                    unset($contactgroupsForChangelog);
                }
            }
            if ($this->request->data('Hosttemplate.notify_period_id')) {
                if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data['Hosttemplate']['notify_period_id'],
                    ],
                ])
                ) {
                    foreach ($timeperiodsForChangelog as $timeperiodId => $timeperiodName) {
                        $ext_data_for_changelog['NotifyPeriod'] = [
                            'id' => $timeperiodId,
                            'name' => $timeperiodName,
                        ];
                    }
                    unset($timeperiodsForChangelog);
                }
            }
            if ($this->request->data('Hosttemplate.check_period_id')) {
                if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data['Hosttemplate']['check_period_id'],
                    ],
                ])
                ) {
                    foreach ($timeperiodsForChangelog as $timeperiodId => $timeperiodName) {
                        $ext_data_for_changelog['CheckPeriod'] = [
                            'id' => $timeperiodId,
                            'name' => $timeperiodName,
                        ];
                    }
                    unset($timeperiodsForChangelog);
                }
            }
            if ($this->request->data('Hosttemplate.command_id')) {
                if ($commandsForChangelog = $this->Command->find('list', [
                    'conditions' => [
                        'Command.id' => $this->request->data['Hosttemplate']['command_id'],
                    ],
                ])
                ) {
                    foreach ($commandsForChangelog as $commandId => $commandName) {
                        $ext_data_for_changelog['CheckCommand'] = [
                            'id' => $commandId,
                            'name' => $commandName,
                        ];
                    }
                    unset($commandsForChangelog);
                }
            }

            if ($this->request->data('Hosttemplate.Hostgroup')) {
                if ($hostgroupsForChangelog = $this->Hostgroup->find('all', [
                    'recursive' => -1,
                    'contain' => [
                            'Container' => [
                                    'fields' => [
                                            'Container.name',
                                        ],
                                ],
                        ],
                    'fields' => [
                            'Hostgroup.id',
                        ],
                    'conditions' => [
                            'Hostgroup.id' => $this->request->data['Hosttemplate']['Hostgroup'],
                        ],
                ])
            ){
                foreach ($hostgroupsForChangelog as $hostgroupData) {
                        $ext_data_for_changelog['Hostgroup'][] = [
                                'id' => $hostgroupData['Hostgroup']['id'],
                                'name' => $hostgroupData['Container']['name'],
                            ];
                    }
                 unset($hostgroupsForChangelog);
                }
            }

            $this->request->data['Contact'] = ($this->request->data['Hosttemplate']['Contact']) ? $this->request->data['Hosttemplate']['Contact'] : [];
            $this->request->data['Contactgroup'] = ($this->request->data['Hosttemplate']['Contactgroup']) ? $this->request->data['Hosttemplate']['Contactgroup'] : [];
            $this->request->data['Hostgroup'] = ($this->request->data['Hosttemplate']['Hostgroup']) ? $this->request->data['Hosttemplate']['Hostgroup'] : [];

            //Delete Command argument values
            //Fetching all commandargument_id of the command arguments out of database:
            $commandargumentIdsOfDatabase = Hash::extract($hosttemplate['Hosttemplatecommandargumentvalue'], '{n}.commandargument_id');

            //Fetching all commandargument_id out of $this->request-data
            $commandargumentIdsOfRequest = [];
            if (isset($this->request->data['Hosttemplatecommandargumentvalue'])) {
                $commandargumentIdsOfRequest = Hash::extract($this->request->data['Hosttemplatecommandargumentvalue'], '{n}.commandargument_id');
            }

            if (!isset($this->request->data['Hosttemplatecommandargumentvalue'])) {
                $this->request->data['Hosttemplatecommandargumentvalue'] = [];
            }

            if ($hosttemplatetype_id !== null && is_numeric($hosttemplatetype_id)) {
                $this->request->data['Hosttemplate']['hosttemplatetype_id'] = $hosttemplatetype_id;
            }

            $this->Hosttemplate->set($this->request->data);
            if ($this->Hosttemplate->validates()) {
                // Checking if the user deleted this argument or changed the command and if we need to delete it out of the database
                foreach ($commandargumentIdsOfDatabase as $commandargumentId) {
                    if (!in_array($commandargumentId, $commandargumentIdsOfRequest)) {
                        // Deleteing the parameter of the argument out of database (sorry ugly php 5.4+ syntax - check twice before modify)
                        $this->Hosttemplatecommandargumentvalue->delete(
                            $this->Hosttemplatecommandargumentvalue->find('first', [
                                'conditions' => [
                                    'hosttemplate_id' => $hosttemplate['Hosttemplate']['id'],
                                    'commandargument_id' => $commandargumentId,
                                ],
                            ])
                            ['Hosttemplatecommandargumentvalue']
                        );
                    }
                }

                $this->Customvariable->deleteAll([
                    'object_id' => $hosttemplate['Hosttemplate']['id'],
                    'objecttype_id' => OBJECT_HOSTTEMPLATE,
                ], false);
            }
            //Save everything including custom variables
            if ($this->Hosttemplate->saveAll($this->request->data)) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $id,
                    OBJECT_HOSTTEMPLATE,
                    $this->request->data('Hosttemplate.container_id'),
                    $userId,
                    $this->request->data['Hosttemplate']['name'],
                    array_merge($this->request->data, $ext_data_for_changelog),
                    $hosttemplate
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if($oldHosttemplateCheckCommandId != $this->request->data['Hosttemplate']['command_id']){
                    //Check command of host template was changed
                    //Delete all custom command arguments of hosts
                    //if command_id from Host is NULL
                    $HostCommandArgumentValuesToDelete = $this->Hosttemplate->find('first', [
                        'recursive' => -1,
                        'contain' => [
                            'Host' => [
                                'conditions' => [
                                    'Host.command_id IS NULL'
                                ],
                                'fields' => [
                                    'Host.id'
                                ],
                            ]
                        ],
                        'conditions' => [
                            'Hosttemplate.id' => $this->Hosttemplate->id
                        ],
                        'fields' => [
                            'Hosttemplate.id'
                        ]
                    ]);

                    if(!empty($HostCommandArgumentValuesToDelete['Host'])){
                        $hostIds = Hash::extract($HostCommandArgumentValuesToDelete['Host'], '{n}.id');
                        if(!empty($hostIds)){
                            $this->Hostcommandargumentvalue->deleteAll([
                                'Hostcommandargumentvalue.host_id' => $hostIds
                            ]);
                        }
                    }
                }
                $flashHref = $this->Hosttemplate->flashRedirect($this->request->params, ['action' => 'edit']);
                $flashHref[] = $this->Hosttemplate->id;
                $flashHref[] = $hosttemplatetype_id;

                $this->setFlash(__('<a href="'.Router::url($flashHref).'">Hosttemplate</a> successfully saved.'));

                $redirect = $this->Hosttemplate->redirect($this->request->params, ['action' => 'index']);
                $this->redirect($redirect);

            } else {
                $this->setFlash(__('Could not save data'), false);
                $this->CustomValidationErrors->loadModel($this->Hosttemplate);
                $this->CustomValidationErrors->customFields(['notification_interval', 'check_interval', 'retry_interval', 'notify_on_recovery', 'flap_detection_on_up']);
                $this->CustomValidationErrors->fetchErrors();

                foreach ($this->Customvariable->validationErrors as $customVariableValidationError) {
                    if (isset($customVariableValidationError['name'])) {
                        $this->set('customVariableValidationError', current($customVariableValidationError['name']));
                    }
                }

                foreach ($this->Customvariable->validationErrors as $customVariableValidationError) {
                    if (isset($customVariableValidationError['value'])) {
                        $this->set('customVariableValidationErrorValue', current($customVariableValidationError['value']));
                    }
                }

                //Refill data that was loaded by ajax due to selected container id
                if ($this->Container->exists($this->request->data('Hosttemplate.container_id'))) {
                    $containerId = $this->request->data('Hosttemplate.container_id');
                    $containerIds = $this->Tree->resolveChildrenOfContainerIds($containerId);

                    $_timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
                    $_contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
                    $_contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
                    $_hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
                }
            }
        }

        //Restore contacts after submit
        $hosttemplate['Contact'] = Hash::combine($hosttemplate['Contact'], '{n}.id', '{n}.id');
        $hosttemplate['Contactgroup'] = Hash::combine($hosttemplate['Contactgroup'], '{n}.id', '{n}.id');
        $hosttemplate['Hostgroup'] = Hash::combine($hosttemplate['Hostgroup'], '{n}.id', '{n}.id');

        if ($this->request->is('post') || $this->request->is('put')) {
            $hosttemplate['Contact'] = $this->request->data['Hosttemplate']['Contact'];
            $hosttemplate['Contactgroup'] = $this->request->data['Hosttemplate']['Contactgroup'];
            $hosttemplate['Hostgroup'] = $this->request->data['Hosttemplate']['Hostgroup'];
        }

        $this->request->data = Hash::merge($hosttemplate, $this->request->data);
        $this->set(compact(['_timeperiods', '_contacts', '_contactgroups', '_hostgroups']));
    }

    public function add($hosttemplatetype_id = null) {
        $systemsettings = $this->Systemsetting->findAsArraySection('MONITORING');
        $active_checks_enabled = $systemsettings['MONITORING']['MONITORING.HOST_CHECK_ACTIVE_DEFAULT'];

        //Empty variables, get fild if Model::save() fails for refill
        $_timeperiods = [];
        $_contacts = [];
        $_contactgroups = [];
        $_hostgroups = [];

        $userId = $this->Auth->user('id');
        // Checking if the user hit submit and a validation error happents, to refill input fields
        $Customvariable = [];
        $customFildsToRefill = [
            'Hosttemplate' => [
                'notification_interval',
                'notify_on_recovery',
                'notify_on_down',
                'notify_on_unreachable',
                'notify_on_flapping',
                'notify_on_downtime',
                'check_interval',
                'retry_interval',
                'flap_detection_enabled',
                'flap_detection_on_up',
                'flap_detection_on_down',
                'flap_detection_on_unreachable',
                'priority',
                'active_checks_enabled',
            ],

        ];
        $this->CustomValidationErrors->checkForRefill($customFildsToRefill);

        //Fix that we dont lose any unsaved host macros, because of vaildation error
        if (isset($this->request->data['Customvariable'])) {
            $Customvariable = $this->request->data['Customvariable'];
        }
        $commands = $this->Command->hostCommands('list');

        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOSTTEMPLATE, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_HOSTTEMPLATE, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        }

        $this->Frontend->set('data_placeholder', __('Please choose a contact'));
        $this->Frontend->set('data_placeholder_empty', __('No entries found'));
        $this->Frontend->setJson('lang_minutes', __('minutes'));
        $this->Frontend->setJson('lang_seconds', __('seconds'));
        $this->Frontend->setJson('lang_and', __('and'));

        $this->set('back_url', $this->referer());
        $this->set(compact(['containers', 'commands', 'userContainerId', 'userValues', 'Customvariable', 'active_checks_enabled']));
        if ($this->request->is('post') || $this->request->is('put')) {
            //Fixing structure of $this->request->data for HATBM
            $ext_data_for_changelog = [
                'Contact' => [],
                'Contactgroup' => [],
            ];
            if ($this->request->data('Hosttemplate.Contact')) {
                if ($contactsForChangelog = $this->Contact->find('list', [
                    'recursive' => -1,
                    'conditions' => [
                        'Contact.id' => $this->request->data['Hosttemplate']['Contact'],
                    ],
                ])
                ) {
                    foreach ($contactsForChangelog as $contactId => $contactName) {
                        $ext_data_for_changelog['Contact'][] = [
                            'id' => $contactId,
                            'name' => $contactName,
                        ];
                    }
                    unset($contactsForChangelog);
                }
            }
            if ($this->request->data('Hosttemplate.Contactgroup')) {
                if ($contactgroupsForChangelog = $this->Contactgroup->find('all', [
                    'recursive' => -1,
                    'contain' => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                    ],
                    'fields' => [
                        'Contactgroup.id',
                    ],
                    'conditions' => [
                        'Contactgroup.id' => $this->request->data['Hosttemplate']['Contactgroup'],
                    ],
                ])
                ) {
                    foreach ($contactgroupsForChangelog as $contactgroupData) {
                        $ext_data_for_changelog['Contactgroup'][] = [
                            'id' => $contactgroupData['Contactgroup']['id'],
                            'name' => $contactgroupData['Container']['name'],
                        ];
                    }
                    unset($contactgroupsForChangelog);
                }
            }
            if ($this->request->data('Hosttemplate.notify_period_id')) {
                if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data['Hosttemplate']['notify_period_id'],
                    ],
                ])
                ) {
                    foreach ($timeperiodsForChangelog as $timeperiodId => $timeperiodName) {
                        $ext_data_for_changelog['NotifyPeriod'] = [
                            'id' => $timeperiodId,
                            'name' => $timeperiodName,
                        ];
                    }
                    unset($timeperiodsForChangelog);
                }
            }
            if ($this->request->data('Hosttemplate.check_period_id')) {
                if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data['Hosttemplate']['check_period_id'],
                    ],
                ])
                ) {
                    foreach ($timeperiodsForChangelog as $timeperiodId => $timeperiodName) {
                        $ext_data_for_changelog['CheckPeriod'] = [
                            'id' => $timeperiodId,
                            'name' => $timeperiodName,
                        ];
                    }
                    unset($timeperiodsForChangelog);
                }
            }
            if ($this->request->data('Hosttemplate.command_id')) {
                if ($commandsForChangelog = $this->Command->find('list', [
                    'conditions' => [
                        'Command.id' => $this->request->data['Hosttemplate']['command_id'],
                    ],
                ])
                ) {
                    foreach ($commandsForChangelog as $commandId => $commandName) {
                        $ext_data_for_changelog['CheckCommand'] = [
                            'id' => $commandId,
                            'name' => $commandName,
                        ];
                    }
                    unset($commandsForChangelog);
                }
            }

            if ($this->request->data('Hosttemplate.Hostgroup')) {
                if ($hostgroupsForChangelog = $this->Hostgroup->find('all', [
                        'recursive'  => -1,
                        'contain'    => [
                                'Container' => [
                                        'fields' => [
                                                'Container.name',
                                            ],
                                    ],
                            ],
                        'fields'     => [
                                'Hostgroup.id',
                            ],
                        'conditions' => [
                                'Hostgroup.id' => $this->request->data['Hosttemplate']['Hostgroup'],
                            ],
                        ])
                    ) {
                        foreach ($hostgroupsForChangelog as $hostgroupData) {
                                $ext_data_for_changelog['Hostgroup'][] = [
                                        'id'   => $hostgroupData['Hostgroup']['id'],
                                        'name' => $hostgroupData['Container']['name'],
                                    ];
                            }
                    unset($hostgroupsForChangelog);
                }
            }

            $this->request->data['Contact'] = $this->request->data['Hosttemplate']['Contact'];
            $this->request->data['Contactgroup'] = $this->request->data['Hosttemplate']['Contactgroup'];
            $this->request->data['Hosttemplate']['uuid'] = $this->Hosttemplate->createUUID();

            if (isset($this->request->data['Hosttemplate']['Hostgroup']) && is_array($this->request->data['Hosttemplate']['Hostgroup'])) {
                $this->request->data['Hostgroup']['Hostgroup'] = $this->request->data['Hosttemplate']['Hostgroup'];
            } else {
                $this->request->data['Hostgroup']['Hostgroup'] = [];
            }

            if ($hosttemplatetype_id !== null && is_numeric($hosttemplatetype_id)) {
                $this->request->data['Hosttemplate']['hosttemplatetype_id'] = $hosttemplatetype_id;
            }

            $this->Hosttemplate->set($this->request->data);
            //Save everything including custom variables
            $this->Hosttemplate->create();
            if ($this->Hosttemplate->saveAll($this->request->data)) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $this->Hosttemplate->id,
                    OBJECT_HOSTTEMPLATE,
                    $this->request->data('Hosttemplate.container_id'),
                    $userId,
                    $this->request->data['Hosttemplate']['name'],
                    array_merge($this->request->data, $ext_data_for_changelog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }
                if ($this->request->ext == 'json') {
                    $this->serializeId();

                    return;
                }
                $flashHref = $this->Hosttemplate->flashRedirect($this->request->params, ['action' => 'edit']);
                $flashHref[] = $this->Hosttemplate->id;
                $flashHref[] = $hosttemplatetype_id;
                $redirect = $this->Hosttemplate->redirect($this->request->params, ['action' => 'index']);
                $this->setFlash(__('<a href="'.Router::url($flashHref).'">Hosttemplate</a> successfully saved.'));
                $this->redirect($redirect);
            } else {

                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();
                    return;
                }
                $this->setFlash(__('Could not save data'), false);
                $this->CustomValidationErrors->loadModel($this->Hosttemplate);
                $this->CustomValidationErrors->customFields(['notification_interval', 'check_interval', 'retry_interval', 'notify_on_recovery', 'flap_detection_on_up']);
                $this->CustomValidationErrors->fetchErrors();

                foreach ($this->Customvariable->validationErrors as $customVariableValidationError) {
                    if (isset($customVariableValidationError['name'])) {
                        $this->set('customVariableValidationError', current($customVariableValidationError['name']));
                    }
                }

                foreach ($this->Customvariable->validationErrors as $customVariableValidationError) {
                    if (isset($customVariableValidationError['value'])) {
                        $this->set('customVariableValidationErrorValue', current($customVariableValidationError['value']));
                    }
                }

                //Refill data that was loaded by ajax due to selected container id
                if ($this->Container->exists($this->request->data('Hosttemplate.container_id'))) {
                    $container_id = $this->request->data('Hosttemplate.container_id');
                    $containerIds = $this->Tree->resolveChildrenOfContainerIds($container_id);

                    $_timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
                    $_contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
                    $_contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
                    $_hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
                }
            }
        }
        $this->set(compact(['_timeperiods', '_contacts', '_contactgroups', '_hostgroups']));
    }

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
            'recursive' => -1,
            'contain' => [
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
        $this->setFlash(__('Could not delete hosttemplate: <a href="'.Router::url($flashHref).'">').$hosttemplate['Hosttemplate']['name'].'</a>', false);
        $this->redirect($redirect);
    }

    public function mass_delete($id = null) {
        $userId = $this->Auth->user('id');
        $errorCount = 0;
        $loopCount = 0;
        foreach (func_get_args() as $hosttemplate_id) {
            if (!$this->Hosttemplate->exists($hosttemplate_id)) {
                throw new NotFoundException(__('Invalid hosttemplate'));
            }

            $hosttemplate = $this->Hosttemplate->findById($hosttemplate_id);
            $containerIdsToCheck = Hash::extract($hosttemplate, 'Hosttemplate.container_id');
            if (!$this->allowedByContainerId($containerIdsToCheck)) {
                $this->render403();

                return;
            }
            $this->Hosttemplate->id = $hosttemplate_id;
            if ($this->Hosttemplate->__allowDelete($hosttemplate_id)) {
                if ($this->Hosttemplate->delete()) {
                    $changelog_data = $this->Changelog->parseDataForChangelog(
                        $this->params['action'],
                        $this->params['controller'],
                        $hosttemplate_id,
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
                            'Host.hosttemplate_id' => $hosttemplate_id,
                        ],
                    ]);
                    foreach ($hosts as $host) {
                        $this->Host->__delete($host, $this->Auth->user('id'));
                    }
                } else {
                    $errorCount++;
                }
            } else {
                $errorCount++;
            }
            $loopCount++;
        }
        if ($errorCount == 0) {
            $this->setFlash(__('Hosttemplates deleted'));
            $this->redirect(['action' => 'index']);
        } else if ($errorCount > 0 && $loopCount > $errorCount) {
            $this->setFlash(__('Some of the Hosttemplates could not be deleted'), false);
            $this->redirect(['action' => 'index']);
        }
        $this->setFlash(__('Could not delete hosttemplates'), false);
        $this->redirect(['action' => 'index']);
    }

    public function copy($id = null) {
        //get the source ids from the Hosttemplates which shall be copied
        $sourceIds = func_get_args();
        $userId = $this->Auth->user('id');
        //get the data of the Hosttemplates
        $hosttemplates = $this->Hosttemplate->find('all', [
            'recursive' => -1,
            'conditions' => [
                'Hosttemplate.id' => $sourceIds,
            ],
            'contain' => [
                'Contact' => [
                    'fields' => [
                        'Contact.id',
                        'Contact.name'
                    ],
                ],
                'Contactgroup' => [
                    'fields' => [
                        'Contactgroup.id',
                    ],
                    'Container' => [
                        'fields' => [
                            'Container.name'
                        ]
                    ]
                ],
                'Hostgroup' => [
                    'fields' => [
                        'Hostgroup.id',
                    ],
                    'Container' => [
                        'fields' => [
                            'Container.name'
                        ]
                    ]
                ],
                'CheckCommand' => [
                    'fields' => [
                        'CheckCommand.id',
                        'CheckCommand.name',
                    ]
                ],
                'Customvariable' => [
                    'fields' => [
                        'name',
                        'value',
                        'objecttype_id'
                    ],
                ],
                'NotifyPeriod' => [
                    'fields' => [
                        'NotifyPeriod.id',
                        'NotifyPeriod.name',
                    ]
                ],
                'CheckPeriod' => [
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
                    $hosttemplateCommandargumentValues = (!empty($oldHosttemplatesCopy[$newHosttemplate['source']]['Hosttemplatecommandargumentvalue']))?Hash::remove($oldHosttemplatesCopy[$newHosttemplate['source']]['Hosttemplatecommandargumentvalue'], '{n}.hosttemplate_id'):[];
                    $customVariables = (!empty($oldHosttemplatesCopy[$newHosttemplate['source']]['Customvariable']))? Hash::remove(
                        $oldHosttemplatesCopy[$newHosttemplate['source']]['Customvariable'], '{n}.object_id'
                    ):[];
                    $newHosttemplateData = [
                        'Hosttemplate' => Hash::merge($oldHosttemplatesCopy[$newHosttemplate['source']]['Hosttemplate'], [
                            'uuid' => $this->Hosttemplate->createUUID(),
                            'name' => $newHosttemplate['name'],
                            'description' => $newHosttemplate['description'],
                            'Contact' => $contactIds,
                            'Contactgroup' => $contactgroupIds,
                            'Hostgroup' => $hostgroupIds,
                        ]),
                        'Customvariable' => $customVariables,
                        'Hosttemplatecommandargumentvalue' => $hosttemplateCommandargumentValues,
                        'Contact' => $contactIds,
                        'Contactgroup' => $contactgroupIds,
                        'Hostgroup' => $hostgroupIds
                    ];
                    if(!empty($hosttemplates[$newHosttemplate['source']]['Contactgroup'])){
                        $contactgroups = [];
                        foreach($hosttemplates[$newHosttemplate['source']]['Contactgroup'] as $contactgroup){
                            $contactgroups[] = [
                                'id' => $contactgroup['id'],
                                'name' => $contactgroup['Container']['name']
                            ];
                        }
                        $hosttemplates[$newHosttemplate['source']]['Contactgroup'] = $contactgroups;
                    }
                    if(!empty($hosttemplates[$newHosttemplate['source']]['Hostgroup'])){
                        $hostgroups = [];
                        foreach($hosttemplates[$newHosttemplate['source']]['Hostgroup'] as $hostgroup){
                            $hostgroups[] = [
                                'id' => $hostgroup['id'],
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

    public function addCustomMacro($counter) {
        $this->allowOnlyAjaxRequests();

        $this->set('objecttype_id', OBJECT_HOSTTEMPLATE);
        $this->set('counter', $counter);
    }


    public function loadArguments($command_id = null, $hosttemplate_id = null) {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Hosttemplate->exists($hosttemplate_id)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        $commandarguments = [];

        $commandarguments = $this->Hosttemplatecommandargumentvalue->find('all', [
            'conditions' => [
                'Commandargument.command_id' => $command_id,
                'Hosttemplatecommandargumentvalue.hosttemplate_id' => $hosttemplate_id,
            ],
        ]);

        //Checking if the hosttemplade has own arguments defined
        if (empty($commandarguments)) {

            $commandarguments = $this->Commandargument->find('all', [
                'recursive' => -1,
                'conditions' => [
                    'Commandargument.command_id' => $command_id,
                ],
            ]);
        }

        $this->set('commandarguments', $commandarguments);
    }

    public function loadArgumentsAdd($command_id = null) {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }
        //Deleting associations that we dont get values of other hosttemplates
        $this->Commandargument->unbindModel(
            ['hasOne' => ['Servicetemplatecommandargumentvalue', 'Servicecommandargumentvalue', 'Hosttemplatecommandargumentvalue', 'Hostcommandargumentvalue']]
        );

        $commandarguments = $this->Commandargument->find('all', [
            'conditions' => [
                'Commandargument.command_id' => $command_id,
            ],
        ]);

        $this->set('commandarguments', $commandarguments);
        $this->render('load_arguments');
    }

    public function usedBy($id = null) {
        $this->layout = 'angularjs';
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }


        if (!$this->Hosttemplate->exists($id)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }


        $hosttemplate = $this->Hosttemplate->find('first', [
            'recursive' => -1,
            'fields' => [
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
            'recursive' => -1,
            'order' => [
                'Host.name' => 'ASC',
            ],
            'joins' => [
                [
                    'table' => 'hosts_to_containers',
                    'alias' => 'HostsToContainers',
                    'type' => 'LEFT',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ],
            ],
            'conditions' => [
                'HostsToContainers.container_id' => $this->MY_RIGHTS,
                'Host.hosttemplate_id' => $id,
            ],
            'contain' => [
                'Container'
            ],
            'fields' => [
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.address',
            ],
            'group' => 'Host.id'
        ]);

        $all_hosts = [];
        foreach($hosts as $host){
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

    public function loadElementsByContainerId($container_id = null) {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Container->exists($container_id)) {
            throw new NotFoundException(__('Invalid Container'));
        }

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($container_id);

        $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
        $timeperiods = $this->Timeperiod->makeItJavaScriptAble($timeperiods);
        $checkperiods = $timeperiods;

        $contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
        $contacts = $this->Contact->makeItJavaScriptAble($contacts);

        $contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
        $contactgroups = $this->Contactgroup->makeItJavaScriptAble($contactgroups);

        $hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
        $hostgroups = $this->Hostgroup->makeItJavaScriptAble($hostgroups);

        $this->set(compact(['timeperiods', 'checkperiods', 'contacts', 'contactgroups', 'hostgroups']));
        $this->set('_serialize', ['timeperiods', 'checkperiods', 'contacts', 'contactgroups', 'hostgroups']);
    }
}
