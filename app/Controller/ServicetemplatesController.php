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
use itnovum\openITCOCKPIT\Core\ServiceConditions;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Filter\ServicetemplateFilter;

/**
 * @property ChangelogComponent                       $Changelog
 * @property CustomValidationErrorsComponent          $CustomValidationErrors
 * @property Servicetemplate                          $Servicetemplate
 * @property Timeperiod                               $Timeperiod
 * @property Command                                  $Command
 * @property Contact                                  $Contact
 * @property Contactgroup                             $Contactgroup
 * @property Container                                $Container
 * @property Commandargument                          $Commandargument
 * @property Customvariable                           $Customvariable
 * @property Servicetemplatecommandargumentvalue      $Servicetemplatecommandargumentvalue
 * @property Servicetemplateeventcommandargumentvalue $Servicetemplateeventcommandargumentvalue
 */
class ServicetemplatesController extends AppController
{
    public $layout = 'Admin.default';
    public $components = [
        'ListFilter.ListFilter',
        'CustomValidationErrors',
        'AdditionalLinks',
        'Flash',
    ];
    public $helpers = [
        'ListFilter.ListFilter',
        'CustomValidationErrors',
        'CustomVariables',
    ];
    public $listFilters = [
        'index' => [
            'fields' => [
                'Servicetemplate.template_name'=> ['label' => 'Template name', 'searchType' => 'wildcard'],
                'Servicetemplate.name'        => ['label' => 'Service name', 'searchType' => 'wildcard'],
                'Servicetemplate.description' => ['label' => 'Template description', 'searchType' => 'wildcard'],
            ],
        ],
    ];
    public $uses = [
        'Servicetemplate',
        'Service',
        'Timeperiod',
        'Command',
        'Contact',
        'Contactgroup',
        'Servicegroup',
        'Container',
        'Commandargument',
        'Customvariable',
        'Servicetemplatecommandargumentvalue',
        'Servicetemplateeventcommandargumentvalue',
        'Servicetemplategroup',
        'Servicecommandargumentvalue',
        'Serviceeventcommandargumentvalue',
        'Documentation'
    ];

    public function index()
    {
        $options = [
            'recursive'  => -1,
            'order'      => [
                'Servicetemplate.name' => 'asc',
            ],
            'conditions' => [
                //'Servicetemplate.servicetemplatetype_id' => GENERIC_SERVICE,
                'Container.id'                           => $this->MY_RIGHTS,
                'Servicetemplate.servicetemplatetype_id' => GENERIC_SERVICE,
            ],
            'fields'     => [
                'Servicetemplate.id',
                'Servicetemplate.uuid',
                'Servicetemplate.template_name',
                'Servicetemplate.name',
                'Servicetemplate.container_id',
                'Servicetemplate.description',
                'Container.*',
            ],
            'contain'    => [
                'Container',
            ],
        ];

        if ($this->isApiRequest()) {
            unset($options['limit']);
            $all_servicetemplates = $this->Servicetemplate->find('all', $options);
        } else {
            $this->Paginator->settings = Hash::merge($this->Paginator->settings, $options);
            $all_servicetemplates = $this->Paginator->paginate();
        }
        $resolvedContainerNames = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_SERVICETEMPLATE, [], $this->hasRootPrivileges);
        $this->set(compact(['all_servicetemplates', 'resolvedContainerNames']));
        $this->set('_serialize', ['all_servicetemplates']);
    }

    public function view($id = null)
    {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Servicetemplate->exists($id)) {
            throw new NotFoundException(__('404 Not Found'));
        }
        $servicetemplate = $this->Servicetemplate->find('first', [
            'recursive'  => -1,
            'contain'    => [
                'Container',
                'CheckPeriod',
                'NotifyPeriod',
                'CheckCommand',
                'EventhandlerCommand',
                'Customvariable',
                'Servicetemplatecommandargumentvalue',
                'Servicetemplateeventcommandargumentvalue',
                'Contactgroup',
                'Contact',
            ],
            'conditions' => [
                'Servicetemplate.id' => $id,
            ],
        ]);

        if (!$this->allowedByContainerId(Hash::extract($servicetemplate, 'Container.id'))) {
            $this->render403();

            return;
        }

        $this->set(compact(['servicetemplate']));
        $this->set('_serialize', ['servicetemplate']);
    }

    public function edit($id = null, $servicetemplatetype_id = null)
    {
        $userId = $this->Auth->user('id');
        $this->Servicetemplate->id = $id;
        if (!$this->Servicetemplate->exists()) {
            throw new NotFoundException(__('Invalid servicetemplate'));
        }

        $serviceTemplate = $this->Servicetemplate->find('first', [
            'conditions' => [
                'Servicetemplate.id' => $id,
            ],
            'contain'    => [
                'Contactgroup' => ['Container'],
                'Servicegroup' => ['Container'],
                'CheckCommand',
                'EventhandlerCommand',
                'Container',
                'Customvariable',
                'NotifyPeriod',
                'CheckPeriod',
                'Contact',
                'Service',
                'Servicetemplatecommandargumentvalue'      => ['Commandargument'],
                'Servicetemplateeventcommandargumentvalue' => ['Commandargument'],
            ],
        ]);
        if (!$this->allowedByContainerId(Hash::extract($serviceTemplate, 'Container.id'))) {
            $this->render403();

            return;
        }

        $servicetemplate_for_changelog = $serviceTemplate;


        $oldServicetemplateCheckCommandId = $serviceTemplate['Servicetemplate']['command_id'];
        $oldServicetemplateEventkCommandId = $serviceTemplate['Servicetemplate']['eventhandler_command_id'];

        $commands = $this->Command->serviceCommands('list');
        $eventHandlers = $this->Command->eventhandlerCommands('list');
        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_SERVICETEMPLATE, [], $this->hasRootPrivileges, [CT_SERVICETEMPLATEGROUP]);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_SERVICETEMPLATE, [], $this->hasRootPrivileges, [CT_SERVICETEMPLATEGROUP]);
        }

        if(count($serviceTemplate['Service']) > 0){
            $newContainers = [];
            foreach($containers as $containerId => $containerName){
                if(!in_array($containerId, [ROOT_CONTAINER, $serviceTemplate['Servicetemplate']['container_id']]))
                    continue;
                $newContainers[$containerId] = $containerName;
            }
            $containers = $newContainers;
        }

        // Data to refill form
        if ($this->request->is('post') || $this->request->is('put')) {
            $containerId = $this->request->data('Servicetemplate.container_id');
        } else {
            $containerId = $serviceTemplate['Servicetemplate']['container_id'];
        }

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($containerId);

        $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
        $contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
        $contactGroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
        $serviceGroups = $this->Servicegroup->servicegroupsByContainerId($containerIds, 'list');

        //Fehlende bzw. neu angelegte CommandArgummente ermitteln und anzeigen
        $commandarguments = $this->Commandargument->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Commandargument.command_id' => $serviceTemplate['CheckCommand']['id'],
            ],
        ]);

        //Fix that we dont lose any unsaved host macros, because of vaildation error
        if (isset($this->request->data['Customvariable'])) {
            $serviceTemplate['Customvariable'] = $this->request->data['Customvariable'];
        }
        $this->Frontend->set('data_placeholder', __('Please choose a contact'));
        $this->Frontend->set('data_placeholder_empty', __('No entries found'));
        $this->Frontend->setJson('lang_minutes', __('minutes'));
        $this->Frontend->setJson('lang_seconds', __('seconds'));
        $this->Frontend->setJson('lang_and', __('and'));
        $this->Frontend->setJson('servicetemplate_id', $serviceTemplate['Servicetemplate']['id']);

        $this->set('back_url', $this->referer());

        if ($this->request->is('post') || $this->request->is('put')) {
            $ext_data_for_changelog = [
                'Contact'             => [],
                'Contactgroup'        => [],
                'Servicegroup'        => [],
                'EventhandlerCommand' => [],
            ];
            if ($this->request->data('Servicetemplate.Contact')) {
                if ($contactsForChangelog = $this->Contact->find('list', [
                    'conditions' => [
                        'Contact.id' => $this->request->data['Servicetemplate']['Contact'],
                    ],
                ])
                ) {
                    foreach ($contactsForChangelog as $contactId => $contactName) {
                        $ext_data_for_changelog['Contact'][] = [
                            'id'   => $contactId,
                            'name' => $contactName,
                        ];
                    }
                    unset($contactsForChangelog);
                }
            }
            if ($this->request->data('Servicetemplate.Contactgroup')) {
                if ($contactgroupsForChangelog = $this->Contactgroup->find('all', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                    ],
                    'fields'     => [
                        'Contactgroup.id',
                    ],
                    'conditions' => [
                        'Contactgroup.id' => $this->request->data['Servicetemplate']['Contactgroup'],
                    ],
                ])
                ) {
                    foreach ($contactgroupsForChangelog as $contactgroupData) {
                        $ext_data_for_changelog['Contactgroup'][] = [
                            'id'   => $contactgroupData['Contactgroup']['id'],
                            'name' => $contactgroupData['Container']['name'],
                        ];
                    }
                    unset($contactgroupsForChangelog);
                }
            }
            if ($this->request->data('Servicetemplate.Servicegroup')) {
                if ($servicegroupsForChangelog = $this->Servicegroup->find('all', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                    ],
                    'fields'     => [
                        'Servicegroup.id',
                    ],
                    'conditions' => [
                        'Servicegroup.id' => $this->request->data['Servicetemplate']['Servicegroup'],
                    ],
                ])
                ) {
                    foreach ($servicegroupsForChangelog as $servicegroupData) {
                        $ext_data_for_changelog['Servicegroup'][] = [
                            'id'   => $servicegroupData['Servicegroup']['id'],
                            'name' => $servicegroupData['Container']['name'],
                        ];
                    }
                    unset($servicegroupsForChangelog);
                }
            }
            if ($this->request->data('Servicetemplate.notify_period_id')) {
                if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data['Servicetemplate']['notify_period_id'],
                    ],
                ])
                ) {
                    foreach ($timeperiodsForChangelog as $timeperiodId => $timeperiodName) {
                        $ext_data_for_changelog['NotifyPeriod'] = [
                            'id'   => $timeperiodId,
                            'name' => $timeperiodName,
                        ];
                    }
                    unset($timeperiodsForChangelog);
                }
            }
            if ($this->request->data('Servicetemplate.check_period_id')) {
                if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data['Servicetemplate']['check_period_id'],
                    ],
                ])
                ) {
                    foreach ($timeperiodsForChangelog as $timeperiodId => $timeperiodName) {
                        $ext_data_for_changelog['CheckPeriod'] = [
                            'id'   => $timeperiodId,
                            'name' => $timeperiodName,
                        ];
                    }
                    unset($timeperiodsForChangelog);
                }
            }
            if ($this->request->data('Servicetemplate.command_id')) {
                if ($commandsForChangelog = $this->Command->find('list', [
                    'conditions' => [
                        'Command.id' => $this->request->data['Servicetemplate']['command_id'],
                    ],
                ])
                ) {
                    foreach ($commandsForChangelog as $commandId => $commandName) {
                        $ext_data_for_changelog['CheckCommand'] = [
                            'id'   => $commandId,
                            'name' => $commandName,
                        ];
                    }
                    unset($commandsForChangelog);
                }
            }

            if ($this->request->data('Servicetemplate.eventhandler_command_id') && $this->request->data('Servicetemplate.eventhandler_command_id') > 0) {
                if ($eventHandlerCommandsForChangelog = $this->Command->find('list', [
                    'conditions' => [
                        'Command.id' => $this->request->data['Servicetemplate']['eventhandler_command_id'],
                    ],
                ])
                ) {
                    foreach ($eventHandlerCommandsForChangelog as $eventHandlerCommandId => $eventHandlerCommandName) {
                        $ext_data_for_changelog['EventhandlerCommand'] = [
                            'id'   => $eventHandlerCommandId,
                            'name' => $eventHandlerCommandName,
                        ];
                    }
                    unset($eventHandlerCommandsForChangelog);
                }
            }

            //Checks if the user deletes a customvariable/macro over the trash icon
            if (!isset($this->request->data['Customvariable'])) {
                $this->request->data['Customvariable'] = [];
            }

            if ($servicetemplatetype_id !== null && is_numeric($servicetemplatetype_id)) {
                $this->request->data['Servicetemplate']['servicetemplatetype_id'] = $servicetemplatetype_id;
            }
            $isJson = $this->request->ext == 'json';

            if ($this->request->data('Servicetemplate.Contact') === '') {
                $this->request->data['Servicetemplate']['Contact'] = [];
            }
            if ($this->request->data('Servicetemplate.Contactgroup') === '') {
                $this->request->data['Servicetemplate']['Contactgroup'] = [];
            }
            if ($this->request->data('Servicetemplate.Servicegroup') === '') {
                $this->request->data['Servicetemplate']['Servicegroup'] = [];
            }
            $this->request->data['Contact']['Contact'] = $this->request->data('Servicetemplate.Contact');
            $this->request->data['Contactgroup']['Contactgroup'] = $this->request->data('Servicetemplate.Contactgroup');
            $this->request->data['Servicegroup']['Servicegroup'] = $this->request->data('Servicetemplate.Servicegroup');

            $this->Servicetemplate->set($this->request->data);
            if ($this->Servicetemplate->validates()) {
                //Delete Command argument values
                $this->Servicetemplatecommandargumentvalue->deleteAll([
                    'servicetemplate_id' => $serviceTemplate['Servicetemplate']['id'],
                ]);

                //Delete old event handler command argument values
                $this->Servicetemplateeventcommandargumentvalue->deleteAll([
                    'servicetemplate_id' => $serviceTemplate['Servicetemplate']['id'],
                ]);

                $this->Customvariable->deleteAll([
                    'object_id'     => $serviceTemplate['Servicetemplate']['id'],
                    'objecttype_id' => OBJECT_SERVICETEMPLATE,
                ], false);

                //CleaUp for services

                // $oldServicetemplateCheckCommandId = $serviceTemplate['Servicetemplate']['command_id'];
                //$oldServicetemplateEventkCommandId

                $dependentServices = $this->Service->find('list', [
                   'conditions' => [
                       'Service.servicetemplate_id' => $serviceTemplate['Servicetemplate']['id'],
                       'Service.command_id IS NULL'
                   ]
                ]);
            }

            // Save everything including custom variables
            if ($this->Servicetemplate->saveAll($this->request->data)) {
                $requestData = array_merge($this->request->data, $ext_data_for_changelog);
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $id,
                    OBJECT_SERVICETEMPLATE,
                    $this->request->data['Servicetemplate']['container_id'],
                    $userId,
                    $this->request->data['Servicetemplate']['name'],
                    $requestData,
                    $servicetemplate_for_changelog
                );

                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if($oldServicetemplateCheckCommandId != $this->request->data['Servicetemplate']['command_id']){
                    //Check command of service template was changed
                    //Delete all custom command arguments of services
                    //if command_id from Service is NULL
                    $serviceCommandArgumentValuesToDelete = $this->Servicetemplate->find('first', [
                        'recursive' => -1,
                        'contain' => [
                            'Service' => [
                                'conditions' => [
                                    'Service.command_id IS NULL'
                                ],
                                'fields' => [
                                    'Service.id'
                                ],
                            ]
                        ],
                        'conditions' => [
                            'Servicetemplate.id' => $this->Servicetemplate->id
                        ],
                        'fields' => [
                            'Servicetemplate.id'
                        ]
                    ]);

                    if(!empty($serviceCommandArgumentValuesToDelete['Service'])){
                        $serviceIds = Hash::extract($serviceCommandArgumentValuesToDelete['Service'], '{n}.id');
                        if(!empty($serviceIds)){
                            $this->Servicecommandargumentvalue->deleteAll([
                                'Servicecommandargumentvalue.service_id' => $serviceIds
                            ]);
                        }
                    }
                }

                if($oldServicetemplateEventkCommandId != $this->request->data['Servicetemplate']['eventhandler_command_id']){
                    //Event handler command of service template was changed
                    //Delete all custom event handler command arguments of services
                    //if eventhandler_command_id from Service is NULL
                    $serviceEventHandlerCommandArgumentValuesToDelete = $this->Servicetemplate->find('first', [
                        'recursive' => -1,
                        'contain' => [
                            'Service' => [
                                'conditions' => [
                                    'Service.eventhandler_command_id IS NULL'
                                ],
                                'fields' => [
                                    'Service.id'
                                ],
                            ]
                        ],
                        'conditions' => [
                            'Servicetemplate.id' => $this->Servicetemplate->id
                        ],
                        'fields' => [
                            'Servicetemplate.id'
                        ]
                    ]);

                    if(!empty($serviceEventHandlerCommandArgumentValuesToDelete['Service'])){
                        $serviceIds = Hash::extract($serviceEventHandlerCommandArgumentValuesToDelete['Service'], '{n}.id');
                        if(!empty($serviceIds)){
                            $this->Serviceeventcommandargumentvalue->deleteAll([
                                'Serviceeventcommandargumentvalue.service_id' => $serviceIds
                            ]);
                        }
                    }
                }


                if ($isJson) {
                    $this->serializeId();
                    return;
                }

                $flashHref = $this->Servicetemplate->flashRedirect($this->request->params, ['action' => 'edit']);
                $flashHref[] = $id;
                $flashHref[] = $servicetemplatetype_id;

                $this->setFlash(__('<a href="'.Router::url($flashHref).'">Servicetemplate</a> successfully saved.'));

                $redirect = $this->Servicetemplate->redirect($this->request->params, ['action' => 'index']);
                $this->redirect($redirect);
            } else {
                if ($isJson) {
                    $this->serializeErrorMessage();
                    return;
                }

                $this->setFlash(__('Could not save data.'), false);
                $this->CustomValidationErrors->loadModel($this->Servicetemplate);
                $this->CustomValidationErrors->customFields([
                    'notification_interval',
                    'check_interval',
                    'retry_interval',
                    'notify_on_recovery',
                    'flap_detection_on_up',
                ]);
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

                    // Refill data that was loaded by Ajax
                    if ($this->Container->exists($this->request->data('Servicetemplate.container_id'))) {
                        $containerIds = $this->request->data('Servicetemplate.container_id');
                        $containerIds = $this->Tree->resolveChildrenOfContainerIds($containerIds);

                        $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
                        $contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
                        $contactGroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
                        $serviceGroups = $this->Servicegroup->servicegroupsByContainerId($containerIds, 'list');
                    }
                }
                $serviceTemplate['Contact'] = $this->request->data['Servicetemplate']['Contact'];
                $serviceTemplate['Contactgroup'] = $this->request->data['Servicetemplate']['Contactgroup'];
                $serviceTemplate['Servicegroup'] = $this->request->data['Servicetemplate']['Servicegroup'];
            }
        } else {
            $serviceTemplate['Contact'] = Hash::combine($serviceTemplate['Contact'], '{n}.id', '{n}.id');
            $serviceTemplate['Contactgroup'] = Hash::combine($serviceTemplate['Contactgroup'], '{n}.id', '{n}.id');
            $serviceTemplate['Servicegroup'] = Hash::combine($serviceTemplate['Servicegroup'], '{n}.id', '{n}.id');
        }

        $this->request->data = Hash::merge($serviceTemplate, $this->request->data);
        $this->set([
            '_timeperiods'     => $timeperiods,
            '_contacts'        => $contacts,
            '_contactgroups'   => $contactGroups,
            '_servicegroups'   => $serviceGroups,
            'containers'       => $containers,
            'servicetemplate'  => $serviceTemplate,
            'commands'         => $commands,
            'eventhandlers'    => $eventHandlers,
            'commandarguments' => $commandarguments,
        ]);
    }


    public function add($servicetemplatetype_id = null)
    {
        $systemsettings = $this->Systemsetting->findAsArraySection('MONITORING');
        $active_checks_enabled = false;
        //If servicetemplate != checkMK servicetemplate
        if($servicetemplatetype_id == GENERIC_SERVICE || $servicetemplatetype_id === null){
            $active_checks_enabled = $systemsettings['MONITORING']['MONITORING.SERVICE_CHECK_ACTIVE_DEFAULT'];
        }

        $_timeperiods = [];
        $_contacts = [];
        $_contactgroups = [];
        $_servicegroups = [];
        $userId = $this->Auth->user('id');
        // Checking if the user hit submit and a validation error happents, to refill input fields
        $Customvariable = [];
        $customFieldsToRefill = [
            'Servicetemplate' => [
                'notification_interval',
                'notify_on_recovery',
                'notify_on_warning',
                'notify_on_unknown',
                'notify_on_critical',
                'notify_on_flapping',
                'notify_on_downtime',
                'check_interval',
                'retry_interval',
                'process_performance_data',
                'active_checks_enabled',
                'flap_detection_enabled',
                'flap_detection_on_ok',
                'flap_detection_on_warning',
                'flap_detection_on_unknown',
                'flap_detection_on_critical',
                'priority',
                'Contact',
                'Contactgroup',
                'Servicegroup'
            ],
        ];
        $this->CustomValidationErrors->checkForRefill($customFieldsToRefill);

        //Fix that we dont lose any unsaved host macros, because of vaildation error
        if (isset($this->request->data['Customvariable'])) {
            $Customvariable = $this->request->data['Customvariable'];
        }

        $userContainerId = $this->Auth->user('container_id');

        $commands = $this->Command->serviceCommands('list');
        $eventhandlers = $this->Command->eventhandlerCommands('list');

        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_SERVICETEMPLATE, [], $this->hasRootPrivileges, [CT_SERVICETEMPLATEGROUP]);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_SERVICETEMPLATE, [], $this->hasRootPrivileges, [CT_SERVICETEMPLATEGROUP]);
        }

        $this->Frontend->set('ServicetemplateActiveChecksEnabled_', __('1'));
        $this->Frontend->set('data_placeholder', __('Please choose a contact'));
        $this->Frontend->set('data_placeholder_empty', __('No entries found'));
        $this->Frontend->setJson('lang_minutes', __('minutes'));
        $this->Frontend->setJson('lang_seconds', __('seconds'));
        $this->Frontend->setJson('lang_and', __('and'));

        $this->set('back_url', $this->referer());
        $this->set(compact(['containers', 'commands', 'eventhandlers', 'userContainerId', 'userValues', 'Customvariable', 'active_checks_enabled']));
        if ($this->request->is('post') || $this->request->is('put')) {
            //Fixing structure of $this->request->data for HABTM

            if (isset($this->request->data['Servicetemplate']) && is_array($this->request->data['Servicetemplate'])) {
                if (isset($this->request->data['Contact'])) {
                    $this->request->data['Servicetemplate']['Contact'] = $this->request->data['Contact'];
                }
                if (isset($this->request->data['Contactgroup'])) {
                    $this->request->data['Servicetemplate']['Contactgroup'] = $this->request->data['Contactgroup'];
                }
            }

            $ext_data_for_changelog = [
                'Contact'             => [],
                'Contactgroup'        => [],
                'Servicegroup'        => [],
                'EventhandlerCommand' => [],
            ];
            if ($this->request->data('Servicetemplate.Contact')) {

                if ($contactsForChangelog = $this->Contact->find('list', [
                    'conditions' => [
                        'Contact.id' => $this->request->data['Servicetemplate']['Contact'],
                    ],
                ])
                ) {
                    foreach ($contactsForChangelog as $contactId => $contactName) {
                        $ext_data_for_changelog['Contact'][] = [
                            'id'   => $contactId,
                            'name' => $contactName,
                        ];
                    }
                    unset($contactsForChangelog);
                }
            }
            if ($this->request->data('Servicetemplate.Contactgroup')) {
                if ($contactgroupsForChangelog = $this->Contactgroup->find('all', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                    ],
                    'fields'     => [
                        'Contactgroup.id',
                    ],
                    'conditions' => [
                        'Contactgroup.id' => $this->request->data['Servicetemplate']['Contactgroup'],
                    ],
                ])
                ) {
                    foreach ($contactgroupsForChangelog as $contactgroupData) {
                        $ext_data_for_changelog['Contactgroup'][] = [
                            'id'   => $contactgroupData['Contactgroup']['id'],
                            'name' => $contactgroupData['Container']['name'],
                        ];
                    }
                    unset($contactgroupsForChangelog);
                }
            }
            if ($this->request->data('Servicetemplate.Servicegroup')) {
                if ($servicegroupsForChangelog = $this->Servicegroup->find('all', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                    ],
                    'fields'     => [
                        'Servicegroup.id',
                    ],
                    'conditions' => [
                        'Servicegroup.id' => $this->request->data['Servicetemplate']['Servicegroup'],
                    ],
                ])
                ) {
                    foreach ($servicegroupsForChangelog as $servicegroupData) {
                        $ext_data_for_changelog['Servicegroup'][] = [
                            'id'   => $servicegroupData['Servicegroup']['id'],
                            'name' => $servicegroupData['Container']['name'],
                        ];
                    }
                    unset($servicegroupsForChangelog);
                }
            }
            if ($this->request->data('Servicetemplate.notify_period_id')) {
                if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data['Servicetemplate']['notify_period_id'],
                    ],
                ])
                ) {
                    foreach ($timeperiodsForChangelog as $timeperiodId => $timeperiodName) {
                        $ext_data_for_changelog['NotifyPeriod'] = [
                            'id'   => $timeperiodId,
                            'name' => $timeperiodName,
                        ];
                    }
                    unset($timeperiodsForChangelog);
                }
            }
            if ($this->request->data('Servicetemplate.check_period_id')) {
                if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data['Servicetemplate']['check_period_id'],
                    ],
                ])
                ) {
                    foreach ($timeperiodsForChangelog as $timeperiodId => $timeperiodName) {
                        $ext_data_for_changelog['CheckPeriod'] = [
                            'id'   => $timeperiodId,
                            'name' => $timeperiodName,
                        ];
                    }
                    unset($timeperiodsForChangelog);
                }
            }
            if ($this->request->data('Servicetemplate.command_id')) {
                if ($commandsForChangelog = $this->Command->find('list', [
                    'conditions' => [
                        'Command.id' => $this->request->data['Servicetemplate']['command_id'],
                    ],
                ])
                ) {
                    foreach ($commandsForChangelog as $commandId => $commandName) {
                        $ext_data_for_changelog['CheckCommand'] = [
                            'id'   => $commandId,
                            'name' => $commandName,
                        ];
                    }
                    unset($commandsForChangelog);
                }
            }

            if ($this->request->data('Servicetemplate.eventhandler_command_id')) {
                if ($commandsForChangelog = $this->Command->find('list', [
                    'conditions' => [
                        'Command.id' => $this->request->data['Servicetemplate']['eventhandler_command_id'],
                    ],
                ])
                ) {
                    foreach ($commandsForChangelog as $commandId => $commandName) {
                        $ext_data_for_changelog['EventhandlerCommand'] = [
                            'id'   => $commandId,
                            'name' => $commandName,
                        ];
                    }
                    unset($commandsForChangelog);
                }
            }

            $this->request->data['Servicetemplate']['uuid'] = $this->Servicetemplate->createUUID();

            if ($servicetemplatetype_id !== null && is_numeric($servicetemplatetype_id)) {
                $this->request->data['Servicetemplate']['servicetemplatetype_id'] = $servicetemplatetype_id;
            }

            $this->request->data['Contact'] = $this->request->data('Servicetemplate.Contact');
            $this->request->data['Contactgroup'] = $this->request->data('Servicetemplate.Contactgroup');
            $this->request->data['Servicegroup'] = $this->request->data('Servicetemplate.Servicegroup');

            $isJson = $this->request->ext == 'json';
            //Save everything including custom variables

            if ($this->Servicetemplate->saveAll($this->request->data)) {
                $changelogData = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $this->Servicetemplate->id,
                    OBJECT_SERVICETEMPLATE,
                    $this->request->data('Servicetemplate.container_id'),
                    $userId,
                    $this->request->data['Servicetemplate']['name'],
                    array_merge($this->request->data, $ext_data_for_changelog)
                );
                if ($changelogData) {
                    CakeLog::write('log', serialize($changelogData));
                }

                if ($isJson) {
                    $this->serializeId();
                } else {
                    $flashHref = $this->Servicetemplate->flashRedirect($this->request->params, ['action' => 'edit']);
                    $flashHref[] = $this->Servicetemplate->id;
                    $flashHref[] = $servicetemplatetype_id;

                    $this->setFlash(__('<a href="'.Router::url($flashHref).'">Servicetemplate</a> successfully saved.'));

                    $redirect = $this->Servicetemplate->redirect($this->request->params, ['action' => 'index']);
                    $this->redirect($redirect);
                }
                //$this->redirect(array('action' => 'index'));
            } else {
                if ($isJson) {
                    $this->serializeErrorMessage();

                    return;
                }

                $this->setFlash(__('Could not save data'), false);
                $this->CustomValidationErrors->loadModel($this->Servicetemplate);
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

                //Refil data that was loaded by ajax due to selected container id
                if ($this->Container->exists($this->request->data('Servicetemplate.container_id'))) {
                    $container_id = $this->request->data('Servicetemplate.container_id');
                    $containerIds = $this->Tree->resolveChildrenOfContainerIds($container_id);

                    $_timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
                    $_contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
                    $_contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
                    $_servicegroups = $this->Servicegroup->servicegroupsByContainerId($containerIds, 'list');
                }
            }
        }
        $this->set(compact(['_timeperiods', '_contacts', '_contactgroups', '_servicegroups']));
    }

    public function delete($id = null)
    {
        $userId = $this->Auth->user('id');
        if (!$this->Servicetemplate->exists($id)) {
            throw new NotFoundException(__('Invalid servicetemplate'));
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $servicetemplate = $this->Servicetemplate->find('first', [
            'recursive' => -1,
            'contain' => [
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
        $redirect = $this->Servicetemplate->redirect($this->request->params, ['action' => 'index']);
        $flashHref = $this->Servicetemplate->flashRedirect($this->request->params, ['action' => 'usedBy']);
        $flashHref[] = $this->Servicetemplate->id;
        $flashHref[] = $servicetemplate['Servicetemplate']['servicetemplatetype_id'];

        if ($this->Servicetemplate->__allowDelete($id)) {
            if ($this->Servicetemplate->delete()) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $id,
                    OBJECT_SERVICETEMPLATE,
                    $servicetemplate['Servicetemplate']['container_id'],
                    $userId,
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
                $this->setFlash(__('Servicetemplate deleted'));
                $this->redirect($redirect);
            }
            $this->setFlash(__('Could not delete servicetemplate'), false);
            $this->redirect($redirect);
        }
        $this->setFlash(__('Could not delete servicetemplate: <a href="'.Router::url($flashHref).'">').$servicetemplate['Servicetemplate']['template_name'].'</a>', false);
        $this->redirect($redirect);
    }

    public function mass_delete($id = null)
    {

        $userId = $this->Auth->user('id');

        $datasource = $this->Servicetemplate->getDataSource();
        try {
            $datasource->begin();
            $deletedServicetemplates = [];

            // $counter = 0;
            foreach (func_get_args() as $serviceTemplateId) {
                // if(++$counter == 3)
                // throw new Exception('Invalid servicetemplate test', 1);
                if (!$this->Servicetemplate->exists($serviceTemplateId)) {
                    throw new Exception('Invalid servicetemplate', 1);
                }

                $servicetemplate = $this->Servicetemplate->findById($serviceTemplateId);
                $containerIdsToCheck = Hash::extract($servicetemplate, 'Servicetemplate.container_id');
                if (!$this->allowedByContainerId($containerIdsToCheck)) {
                    throw new Exception('', 403);
                }

                $this->Servicetemplate->id = $serviceTemplateId;
                if (!$this->Servicetemplate->__allowDelete($serviceTemplateId)) {
                    throw new Exception('Some of the Servicetemplates could not be deleted', 1);
                }

                if (!$this->Servicetemplate->delete()) {
                    throw new Exception('Some of the Servicetemplates could not be deleted', 1);
                }

                //Delete Documentation record if exists
                $documentation = $this->Documentation->findByUuid($servicetemplate['Servicetemplate']['uuid']);
                if (isset($documentation['Documentation']['id'])) {
                    $this->Documentation->delete($documentation['Documentation']['id']);
                    unset($documentation);
                }


                //Servicetemplate deleted, now we need to delete all services that are using this template
                $this->loadModel('Service');
                $services = $this->Service->find('all', [
                    'conditions' => [
                        'Service.servicetemplate_id' => $serviceTemplateId,
                    ],
                ]);
                foreach ($services as $service) {
                    if (!$this->Service->__delete($service, $this->Auth->user('id'))) {
                        throw new Exception('Some of the Servicetemplates could not be deleted', 1);
                    }
                }

                $deletedServicetemplates[] = $servicetemplate;

            }

            foreach ($deletedServicetemplates as $deletedServicetemplate) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $deletedServicetemplate['Servicetemplate']['id'],
                    OBJECT_SERVICETEMPLATE,
                    $deletedServicetemplate['Servicetemplate']['container_id'],
                    $userId,
                    $deletedServicetemplate['Servicetemplate']['name'],
                    $deletedServicetemplate
                );
                if ($changelog_data) {
                    if (!CakeLog::write('log', serialize($changelog_data))) {
                        throw new Exception('Cannot write logs. Servicetemplates was not deleted.', 1);
                    }
                }
            }

            $datasource->commit();

            $this->setFlash(__('Servicetemplates deleted'));
            $this->redirect(['action' => 'index']);

        } catch (Exception $e) {
            $datasource->rollback();
            switch ($e->getCode()) {
                case 403:
                    $this->render403();
                    break;
                default:
                    $this->setFlash(__($e->getMessage()), false);
                    $this->redirect(['action' => 'index']);
            }
        }

    }

    public function copy($id = null)
    {
        $userId = $this->Auth->user('id');
        $servicetmpl = $this->Servicetemplate->find('all', [
            'recursive' => -1,
            'fields' => [
                'Servicetemplate.template_name',
                'Servicetemplate.name',
                'Servicetemplate.description',
                'Servicetemplate.container_id',
                'Servicetemplate.servicetemplatetype_id',
                'Servicetemplate.check_period_id',
                'Servicetemplate.notify_period_id',
                'Servicetemplate.command_id',
                'Servicetemplate.eventhandler_command_id',
                'Servicetemplate.check_interval',
                'Servicetemplate.retry_interval',
                'Servicetemplate.max_check_attempts',
                'Servicetemplate.notification_interval',
                'Servicetemplate.notifications_enabled',
                'Servicetemplate.notify_on_warning',
                'Servicetemplate.notify_on_unknown',
                'Servicetemplate.notify_on_critical',
                'Servicetemplate.notify_on_recovery',
                'Servicetemplate.notify_on_flapping',
                'Servicetemplate.notify_on_downtime',
                'Servicetemplate.flap_detection_enabled',
                'Servicetemplate.notes',
                'Servicetemplate.priority',
                'Servicetemplate.tags',
                'Servicetemplate.service_url',
                'Servicetemplate.active_checks_enabled',
                'Servicetemplate.process_performance_data',
                'Servicetemplate.is_volatile',
                'Servicetemplate.freshness_checks_enabled',
                'Servicetemplate.freshness_threshold',
                'Servicetemplate.flap_detection_on_ok',
                'Servicetemplate.flap_detection_on_warning',
                'Servicetemplate.flap_detection_on_unknown',
                'Servicetemplate.flap_detection_on_critical'
            ],
            'conditions' => [
                'Servicetemplate.id' => func_get_args(),
            ],
            'contain'    => [
                'CheckPeriod' =>[
                    'fields' =>[
                        'CheckPeriod.id',
                        'CheckPeriod.name'
                    ]
                ],
                'NotifyPeriod' =>[
                    'fields' =>[
                        'NotifyPeriod.id',
                        'NotifyPeriod.name'
                    ]
                ],
                'CheckCommand' => [
                    'fields' => [
                        'CheckCommand.id',
                        'CheckCommand.name',
                    ]
                ],
                'Contact'                                  => [
                    'fields' => [
                        'Contact.id',
                        'Contact.name'
                    ],
                ],
                'Contactgroup'                             => [
                    'fields' => [
                        'Contactgroup.id',
                    ],
                    'Container' => [
                        'fields' => [
                            'Container.name'
                        ]
                    ]
                ],
                'Servicegroup'                             => [
                    'fields' => [
                        'Servicegroup.id',
                    ],
                    'Container' => [
                        'fields' => [
                            'Container.name'
                        ]
                    ]
                ],
                'Servicetemplatecommandargumentvalue'      => [
                    'fields' => [
                        'commandargument_id', 'value',
                    ],
                ],
                'Servicetemplateeventcommandargumentvalue' => [
                    'fields' => [
                        'commandargument_id', 'value',
                    ],
                ],
                'Customvariable' => [
                    'fields' => [
                        'name',
                        'value',
                    ],
                ],
            ],
        ]);
        $servicetemplates = Hash::combine($servicetmpl, '{n}.Servicetemplate.id', '{n}');
        if ($this->request->is('post') || $this->request->is('put')) {
            $datasource = $this->Servicetemplate->getDataSource();
            try {
                $datasource->begin();
                foreach ($this->request->data['Servicetemplate'] as $newServicetemplate) {
                    $contactIds = Hash::extract($servicetemplates[$newServicetemplate['source']], 'Contact.{n}.id');
                    $contactgroupIds = Hash::extract($servicetemplates[$newServicetemplate['source']], 'Contactgroup.{n}.id');
                    $servicegroupIds = Hash::extract($servicetemplates[$newServicetemplate['source']], 'Servicegroup.{n}.id');

                    $newServicetemplateData = [
                        'Servicetemplate' => Hash::merge($servicetemplates[$newServicetemplate['source']]['Servicetemplate'], [
                            'uuid'        => $this->Servicetemplate->createUUID(),
                            'template_name'=> $newServicetemplate['template_name'],
                            'name'        => $newServicetemplate['name'],
                            'description' => $newServicetemplate['description'],
                        ]),
                        'Customvariable'                           => Hash::insert(
                            Hash::remove(
                                $servicetemplates[$newServicetemplate['source']]['Customvariable'], '{n}.object_id'
                            ),
                            '{n}.objecttype_id',
                            OBJECT_SERVICETEMPLATE
                        ),
                        'Servicetemplatecommandargumentvalue'      => Hash::remove(
                            $servicetemplates[$newServicetemplate['source']]['Servicetemplatecommandargumentvalue'],
                            '{n}.servicetemplate_id'
                        ),
                        'Servicetemplateeventcommandargumentvalue' => Hash::remove(
                            $servicetemplates[$newServicetemplate['source']]['Servicetemplateeventcommandargumentvalue'],
                            '{n}.servicetemplate_id'
                        ),
                        'Contact' => $contactIds,
                        'Contactgroup' => $contactgroupIds,
                        'Servicegroup' => $servicegroupIds
                    ];
                    $newServicetemplateData['Servicetemplate'] = Hash::remove($newServicetemplateData['Servicetemplate'], 'id');
                    if(!empty($servicetemplates[$newServicetemplate['source']]['Contactgroup'])){
                        $contactgroups = [];
                        foreach($servicetemplates[$newServicetemplate['source']]['Contactgroup'] as $contactgroup){
                            $contactgroups[] = [
                                'id' => $contactgroup['id'],
                                'name' => $contactgroup['Container']['name']
                            ];
                        }
                        $servicetemplates[$newServicetemplate['source']]['Contactgroup'] = $contactgroups;
                    }
                    if(!empty($servicetemplates[$newServicetemplate['source']]['Servicegroup'])){
                        $servicegroups = [];
                        foreach($servicetemplates[$newServicetemplate['source']]['Servicegroup'] as $servicegroup){
                            $servicegroups[] = [
                                'id' => $servicegroup['id'],
                                'name' => $servicegroup['Container']['name']
                            ];
                        }
                        $servicetemplates[$newServicetemplate['source']]['Servicegroup'] = $servicegroups;
                    }
                    $this->Servicetemplate->create();
                    if (!$this->Servicetemplate->saveAll($newServicetemplateData)) {
                        throw new Exception('Some of the Servicetemplates could not be copied');
                    }
                    $changelog_data = $this->Changelog->parseDataForChangelog(
                        $this->params['action'],
                        $this->params['controller'],
                        $this->Servicetemplate->id,
                        OBJECT_SERVICETEMPLATE,
                        $servicetemplates[$newServicetemplate['source']]['Servicetemplate']['container_id'],
                        $userId,
                        $newServicetemplate['template_name'],
                        Hash::merge(
                            $servicetemplates[$newServicetemplate['source']], [
                            'Servicetemplate' => [
                                'template_name'=> $newServicetemplate['template_name'],
                                'name'        => $newServicetemplate['name'],
                                'description' => $newServicetemplate['description'],
                            ]
                        ])
                    );
                    if ($changelog_data) {
                        CakeLog::write('log', serialize($changelog_data));
                    }
                }
                $datasource->commit();
                $this->setFlash(__('Servicetemplates are successfully copied'));
                $this->redirect(['action' => 'index']);

            } catch (Exception $e) {
                $datasource->rollback();
                $this->setFlash(__($e->getMessage()), false);
                $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('servicetemplates'));
        $this->set('back_url', $this->referer());
    }

    public function assignGroup($id = null)
    {
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
                } elseif ($checkedContanerId != $servicetemplate['Servicetemplate']['container_id']) {
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
        $resolvedPathContainerName = $this->Tree->easyPath([$checkedContanerId], OBJECT_SERVICETEMPLATEGROUP, [], $this->hasRootPrivileges);
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

        $myContainerId = $this->Tree->resolveChildrenOfContainerIds($checkedContanerId);
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

    public function usedBy($id = null)
    {

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
                'Servicetemplate.id' => $id,
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
                'Service'       => $Service->toArray(),
                'Host'          => $Host->toArray(),
            ];
            $all_services[] = $tmpRecord;
        }


        $this->set(compact(['all_services','servicetemplate']));
        $this->set('_serialize', ['all_services', 'servicetemplate']);
    }

    public function loadArguments($command_id = null, $servicetemplate_id = null)
    {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Servicetemplate->exists($servicetemplate_id)) {
            throw new NotFoundException(__('Invalid servicetemplate'));
        }
        $commandarguments = [];
        $commandarguments = $this->Servicetemplatecommandargumentvalue->find('all', [
            'conditions' => [
                'Commandargument.command_id'                             => $command_id,
                'Servicetemplatecommandargumentvalue.servicetemplate_id' => $servicetemplate_id,
            ],
        ]);
        //Checking if the servicetemplade has own arguments defined
        if (empty($commandarguments)) {
            $commandarguments = $this->Commandargument->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'Commandargument.command_id' => $command_id,
                ],
            ]);
        }

        $this->set('commandarguments', $commandarguments);
    }

    public function loadContactsAndContactgroups($servicetemplate_id = null)
    {
        $this->allowOnlyAjaxRequests();

        $this->loadModel('Contact');
        $this->loadModel('Contactgroup');

        $result = [
            'contacts'      => [
                'contacts' => [],
                'sizeof'   => 0,
            ],
            'contactgroups' => [
                'contactgroups' => [],
                'sizeof'        => 0,
            ],
        ];
        //$result['contacts']['contacts'] = $this->Contact->contactsByServicetemplateId($servicetemplate_id, 'list');
        $containerIds = $this->Tree->resolveChildrenOfContainerIds($servicetemplate_id);
        $result['contacts']['contacts'] = $this->Contact->contactsByContainerId($containerIds, 'list');

        $result['contacts']['sizeof'] = sizeof($result['contacts']['contacts']);
        //container_id = 1 => ROOT
        $result['contactgroups']['contactgroups'] = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
        $result['contactgroups']['sizeof'] = sizeof($result['contactgroups']['contactgroups']);

        $this->set(compact(['result']));
        $this->set('_serialize', ['result']);

    }

    public function loadArgumentsAdd($command_id = null)
    {
        $this->allowOnlyAjaxRequests();
        $this->loadModel('Commandargument');

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

    public function loadNagArgumentsAdd($command_id = null)
    {
        $this->allowOnlyAjaxRequests();
        $this->loadModel('Commandargument');

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
        $this->render('load_nag_arguments');
    }

    public function addCustomMacro($counter)
    {
        $this->allowOnlyAjaxRequests();

        $this->set('objecttype_id', OBJECT_SERVICETEMPLATE);
        $this->set('counter', $counter);
    }

    /**
     * Loads the paramters for 'Check Command'.
     */
    public function loadParametersByCommandId($command_id = null, $servicetemplate_id = null)
    {
        $this->allowOnlyAjaxRequests();

        $test = [];
        $commandarguments = [];
        if ($command_id) {
            $commandarguments = $this->Commandargument->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'Commandargument.command_id' => $command_id,
                ],
            ]);
            foreach ($commandarguments as $key => $commandargument) {
                if ($servicetemplate_id) {
                    $servicetemplate_command_argument_value = $this->Servicetemplatecommandargumentvalue->find('first', [
                        'conditions' => [
                            'Servicetemplatecommandargumentvalue.servicetemplate_id' => $servicetemplate_id,
                            'Servicetemplatecommandargumentvalue.commandargument_id' => $commandargument['Commandargument']['id'],
                        ],
                        'fields'     => [
                            'Servicetemplatecommandargumentvalue.value',
                            'Servicetemplatecommandargumentvalue.id',
                        ],
                    ]);
                    if (isset($servicetemplate_command_argument_value['Servicetemplatecommandargumentvalue']['value'])) {
                        $commandarguments[$key]['Servicetemplatecommandargumentvalue']['value'] =
                            $servicetemplate_command_argument_value['Servicetemplatecommandargumentvalue']['value'];
                    }
                    if (isset($servicetemplate_command_argument_value['Servicetemplatecommandargumentvalue']['id'])) {
                        $commandarguments[$key]['Servicetemplatecommandargumentvalue']['id'] =
                            $servicetemplate_command_argument_value['Servicetemplatecommandargumentvalue']['id'];
                    }
                }
            }
        }

        $this->set(compact('commandarguments'));
    }

    /**
     * Loads the parameters for the 'Eventhandler check command'.
     */
    public function loadNagParametersByCommandId($command_id = null, $servicetemplate_id = null)
    {
        $this->allowOnlyAjaxRequests();

        $test = [];
        $commandarguments = [];
        if ($command_id) {
            $commandarguments = $this->Commandargument->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'Commandargument.command_id' => $command_id,
                ],
            ]);
            foreach ($commandarguments as $key => $commandargument) {
                if ($servicetemplate_id) {
                    $servicetemplate_command_argument_value = $this->Servicetemplateeventcommandargumentvalue->find('first', [
                        'conditions' => [
                            'Servicetemplateeventcommandargumentvalue.servicetemplate_id' => $servicetemplate_id,
                            'Servicetemplateeventcommandargumentvalue.commandargument_id' => $commandargument['Commandargument']['id'],
                        ],
                        'fields'     => [
                            'Servicetemplateeventcommandargumentvalue.value',
                            'Servicetemplateeventcommandargumentvalue.id',
                        ],
                    ]);
                    if (isset($servicetemplate_command_argument_value['Servicetemplateeventcommandargumentvalue']['value'])) {
                        $commandarguments[$key]['Servicetemplateeventcommandargumentvalue']['value'] =
                            $servicetemplate_command_argument_value['Servicetemplateeventcommandargumentvalue']['value'];
                    }
                    if (isset($servicetemplate_command_argument_value['Servicetemplateeventcommandargumentvalue']['id'])) {
                        $commandarguments[$key]['Servicetemplateeventcommandargumentvalue']['id'] =
                            $servicetemplate_command_argument_value['Servicetemplateeventcommandargumentvalue']['id'];
                    }
                }
            }
        }

        $this->set(compact('commandarguments'));
    }

    public function loadElementsByContainerId($containerId = null)
    {
        $this->allowOnlyAjaxRequests();
        if (!$this->Container->exists($containerId)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($containerId);

        $timeperiods = $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
        $timeperiods = $this->Timeperiod->makeItJavaScriptAble($timeperiods);
        $checkperiods = $timeperiods;

        $contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
        $contacts = $this->Timeperiod->makeItJavaScriptAble($contacts);

        $contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
        $contactgroups = $this->Timeperiod->makeItJavaScriptAble($contactgroups);

        $servicegroups = $this->Servicegroup->servicegroupsByContainerId($containerIds, 'list');
        $servicegroups = $this->Servicegroup->makeItJavaScriptAble($servicegroups);

        $this->set(compact(['timeperiods', 'checkperiods', 'contacts', 'contactgroups', 'servicegroups']));
        $this->set('_serialize', ['timeperiods', 'checkperiods', 'contacts', 'contactgroups', 'servicegroups']);
    }

    public function loadServicetemplatesByContainerId($containerId = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $containerId = $this->request->query('containerId');
        $selected = $this->request->query('selected');
        $ServicetemplateFilter = new ServicetemplateFilter($this->request);

        $containerIds = [ROOT_CONTAINER, $containerId];
        if ($containerId == ROOT_CONTAINER) {
            $containerIds = $this->Tree->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
        }
        $servicetemplates = $this->Servicetemplate->makeItJavaScriptAble(
            $this->Servicetemplate->getServicetemplatesForAngular($containerIds, $ServicetemplateFilter, $selected)
        );
        $this->set(compact(['servicetemplates']));
        $this->set('_serialize', ['servicetemplates']);
    }

}
