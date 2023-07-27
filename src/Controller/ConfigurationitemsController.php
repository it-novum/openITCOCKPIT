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

use App\Form\ConfigurationitemsExportForm;
use App\itnovum\openITCOCKPIT\Export\DependencyCollector;
use App\Model\Table\CommandsTable;
use App\Model\Table\ContactgroupsTable;
use App\Model\Table\ContactsTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplategroupsTable;
use App\Model\Table\ServicetemplatesTable;
use App\Model\Table\TimeperiodsTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\FileDebugger;
use itnovum\openITCOCKPIT\Core\KeyValueStore;

class ConfigurationitemsController extends AppController {
    public function import() {
        if (!$this->isJsonRequest()) {
            return;
        }
    }

    public function export() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $configurationitemsExportForm = new ConfigurationitemsExportForm();
        $requestData = $this->request->getData('Configurationitems', []);
        $configurationitemsExportForm->execute($requestData);

        if (!empty($configurationitemsExportForm->getErrors())) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $configurationitemsExportForm->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        // Start JSON Export
        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');

        $jsonExport = [
            'servicetemplategroups' => [],
            'servicetemplates'      => [],
            'contactgroups'         => [],
            'contacts'              => [],
            'timeperiods'           => [],
            'commands'              => []
        ];

        $DependencyCollector = new DependencyCollector();

        // Export Service template groups
        if (!empty($requestData['servicetemplategroups']['_ids'])) {
            // User has selected specific service template groups to export

            /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
            $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');
            $servicetemplategroupsForExport = $ServicetemplategroupsTable->getServicetemplategroupsByIdsForExport(
                $requestData['servicetemplategroups']['_ids']
            );

            foreach ($servicetemplategroupsForExport as $servicetemplategroup) {
                $servicetemplateUuids = [];

                // Add service templates to dependency collector
                foreach ($servicetemplategroup['servicetemplates'] as $servicetemplate) {
                    $servicetemplateUuids[] = $servicetemplate['uuid'];

                    $DependencyCollector->addServicetemplate(
                        $servicetemplate['id'],
                        $servicetemplate['uuid'],
                    );
                }

                // Add service template group to the json export
                $jsonServicetemplateGroup = [
                    'uuid'             => $servicetemplategroup['uuid'],
                    'description'      => $servicetemplategroup['description'],
                    'container'        => [
                        'name'             => $servicetemplategroup['container']['name'],
                        'parent_id'        => $servicetemplategroup['container']['parent_id'],
                        'containertype_id' => CT_SERVICETEMPLATEGROUP
                    ],
                    'servicetemplates' => [
                        // The importer will replace the UUID with the corresponding ID of the service template
                        '_ids' => $servicetemplateUuids
                    ]
                ];

                $jsonExport['servicetemplategroups'][] = $jsonServicetemplateGroup;
            }
        }

        // Export Services templates
        if (!isset($requestData['servicetemplates']['_ids'])) {
            $requestData['servicetemplates']['_ids'] = [];
        }

        // Merge manually selected service template ids (selected by the user) with service template ids that are required due to dependencies
        foreach ($DependencyCollector->getServicetemplates() as $servicetemplateID => $servicetemplateUuid) {
            $requestData['servicetemplates']['_ids'][] = $servicetemplateID;
        }
        $requestData['servicetemplates']['_ids'] = array_unique($requestData['servicetemplates']['_ids']);

        $EventhandlerKV = new KeyValueStore();
        if (!empty($requestData['servicetemplates']['_ids'])) {
            /** @var $ServicetemplatesTable ServicetemplatesTable */
            $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
            $servicetemplatesForExport = $ServicetemplatesTable->getServicetemplatesByIdsForExport(
                $requestData['servicetemplates']['_ids']
            );

            foreach ($servicetemplatesForExport as $servicetemplate) {
                // Save dependencies for later processing
                $contactUuids = [];
                $contactgroupUuids = [];

                $DependencyCollector->addCommand(
                    $servicetemplate['check_command']['id'],
                    $servicetemplate['check_command']['uuid']
                );
                $DependencyCollector->addTimperiod(
                    $servicetemplate['check_period']['id'],
                    $servicetemplate['check_period']['uuid']
                );
                $DependencyCollector->addTimperiod(
                    $servicetemplate['notify_period']['id'],
                    $servicetemplate['notify_period']['uuid']
                );
                foreach ($servicetemplate['contacts'] as $contact) {
                    $contactUuids[] = $contact['uuid'];
                    $DependencyCollector->addContact(
                        $contact['id'],
                        $contact['uuid']
                    );
                }
                foreach ($servicetemplate['contactgroups'] as $contactgroup) {
                    $contactgroupUuids[] = $contactgroup['uuid'];
                    $DependencyCollector->addContactgroup(
                        $contactgroup['id'],
                        $contactgroup['uuid']
                    );
                }

                $eventhandlerCommandUuid = null;
                if ($servicetemplate['eventhandler_command_id'] > 0) {
                    if (!$EventhandlerKV->has($servicetemplate['eventhandler_command_id'])) {
                        $eventhandler = $CommandsTable->getCommandById($servicetemplate['eventhandler_command_id']);
                        $EventhandlerKV->set($servicetemplate['eventhandler_command_id'], $eventhandler);
                    }

                    $eventhandler = $EventhandlerKV->get($servicetemplate['eventhandler_command_id']);
                    $eventhandlerCommandUuid = $eventhandler['Command']['uuid'];

                    $DependencyCollector->addCommand(
                        $eventhandler['Command']['id'],
                        $eventhandler['Command']['uuid']
                    );
                }

                $jsonServicetemplate = [
                    'uuid'                                      => $servicetemplate['uuid'],
                    'template_name'                             => $servicetemplate['template_name'],
                    'name'                                      => $servicetemplate['name'],
                    'container_id'                              => $servicetemplate['container_id'],
                    'servicetemplatetype_id'                    => $servicetemplate['servicetemplatetype_id'],
                    'check_period_id'                           => $servicetemplate['check_period']['uuid'],
                    'notify_period_id'                          => $servicetemplate['notify_period']['uuid'],
                    'description'                               => $servicetemplate['description'],
                    'command_id'                                => $servicetemplate['check_command']['uuid'],
                    'check_command_args'                        => $servicetemplate['check_command_args'],
                    'checkcommand_info'                         => $servicetemplate['checkcommand_info'],
                    'eventhandler_command_id'                   => $eventhandlerCommandUuid,
                    'timeperiod_id'                             => $servicetemplate['timeperiod_id'], //unused
                    'check_interval'                            => $servicetemplate['check_interval'],
                    'retry_interval'                            => $servicetemplate['retry_interval'],
                    'max_check_attempts'                        => $servicetemplate['max_check_attempts'],
                    'first_notification_delay'                  => $servicetemplate['first_notification_delay'],
                    'notification_interval'                     => $servicetemplate['notification_interval'],
                    'notify_on_warning'                         => $servicetemplate['notify_on_warning'],
                    'notify_on_unknown'                         => $servicetemplate['notify_on_unknown'],
                    'notify_on_critical'                        => $servicetemplate['notify_on_critical'],
                    'notify_on_recovery'                        => $servicetemplate['notify_on_recovery'],
                    'notify_on_flapping'                        => $servicetemplate['notify_on_flapping'],
                    'notify_on_downtime'                        => $servicetemplate['notify_on_downtime'],
                    'flap_detection_enabled'                    => $servicetemplate['flap_detection_enabled'],
                    'flap_detection_on_ok'                      => $servicetemplate['flap_detection_on_ok'],
                    'flap_detection_on_warning'                 => $servicetemplate['flap_detection_on_warning'],
                    'flap_detection_on_unknown'                 => $servicetemplate['flap_detection_on_unknown'],
                    'flap_detection_on_critical'                => $servicetemplate['flap_detection_on_critical'],
                    'low_flap_threshold'                        => $servicetemplate['low_flap_threshold'],
                    'high_flap_threshold'                       => $servicetemplate['high_flap_threshold'],
                    'process_performance_data'                  => $servicetemplate['process_performance_data'],
                    'freshness_checks_enabled'                  => $servicetemplate['freshness_checks_enabled'],
                    'freshness_threshold'                       => $servicetemplate['freshness_threshold'],
                    'passive_checks_enabled'                    => $servicetemplate['passive_checks_enabled'],
                    'event_handler_enabled'                     => $servicetemplate['event_handler_enabled'],
                    'active_checks_enabled'                     => $servicetemplate['active_checks_enabled'],
                    'retain_status_information'                 => $servicetemplate['retain_status_information'],
                    'retain_nonstatus_information'              => $servicetemplate['retain_nonstatus_information'],
                    'notifications_enabled'                     => $servicetemplate['notifications_enabled'],
                    'notes'                                     => $servicetemplate['notes'],
                    'priority'                                  => $servicetemplate['priority'],
                    'tags'                                      => $servicetemplate['tags'],
                    'service_url'                               => $servicetemplate['service_url'],
                    'is_volatile'                               => $servicetemplate['is_volatile'],
                    'check_freshness'                           => $servicetemplate['check_freshness'],
                    'contacts'                                  => [
                        '_ids' => $contactUuids
                    ],
                    'contactgroups'                             => [
                        '_ids' => $contactgroupUuids
                    ],
                    'servicetemplatecommandargumentvalues'      => [],
                    'servicetemplateeventcommandargumentvalues' => [],
                    'customvariables'                           => [],
                    'servicegroups'                             => [] //unused for now
                ];

                foreach ($servicetemplate['servicetemplatecommandargumentvalues'] as $starg) {
                    $jsonServicetemplate['servicetemplatecommandargumentvalues'][] = [
                        'commandargument_id' => $starg['commandargument']['name'], // $ARGn$
                        'value'              => $starg['value']
                    ];
                }

                foreach ($servicetemplate['servicetemplateeventcommandargumentvalues'] as $stevarg) {
                    $jsonServicetemplate['servicetemplateeventcommandargumentvalues'][] = [
                        'commandargument_id' => $stevarg['commandargument']['name'],  // $ARGn$
                        'value'              => $stevarg['value']
                    ];
                }

                foreach ($servicetemplate['customvariables'] as $cv) {
                    $jsonServicetemplate['customvariables'][] = [
                        'objecttype_id' => OBJECT_SERVICETEMPLATE,
                        'name'          => $cv['name'],
                        'value'         => $cv['value'],
                        'password'      => $cv['password']
                    ];
                }

                $jsonExport['servicetemplates'][] = $jsonServicetemplate;

            }
        }

        // Export contact groups
        if (!isset($requestData['contactgroups']['_ids'])) {
            $requestData['contactgroups']['_ids'] = [];
        }

        // Merge manually selected contact group ids (selected by the user) with contact groups ids that are required due to dependencies
        foreach ($DependencyCollector->getContactgroups() as $cgId => $cgUuid) {
            $requestData['contactgroups']['_ids'][] = $cgId;
        }
        $requestData['contactgroups']['_ids'] = array_unique($requestData['contactgroups']['_ids']);

        if (!empty($requestData['contactgroups']['_ids'])) {
            /** @var $ContactgroupsTable ContactgroupsTable */
            $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
            $contactgroupsForExport = $ContactgroupsTable->getContactgroupsByIdsForExport(
                $requestData['contactgroups']['_ids']
            );

            foreach ($contactgroupsForExport as $contactgroup) {
                // Save dependencies for later processing
                $contactUuids = [];

                foreach ($contactgroup['contacts'] as $contact) {
                    $contactUuids[] = $contact['uuid'];
                    $DependencyCollector->addContact(
                        $contact['id'],
                        $contact['uuid']
                    );
                }

                $jsonContactgroup = [
                    'uuid'        => $contactgroup['uuid'],
                    'description' => $contactgroup['description'],
                    'container'   => [
                        'name'             => $contactgroup['container']['name'],
                        'parent_id'        => $contactgroup['container']['parent_id'],
                        'containertype_id' => CT_CONTACTGROUP
                    ],
                    'contacts'    => [
                        '_ids' => $contactUuids
                    ]
                ];

                $jsonExport['contactgroups'][] = $jsonContactgroup;
            }
        }

        // Export contacts
        if (!isset($requestData['contacts']['_ids'])) {
            $requestData['contacts']['_ids'] = [];
        }

        // Merge manually selected contact ids (selected by the user) with contact ids that are required due to dependencies
        foreach ($DependencyCollector->getContacts() as $cId => $cUuid) {
            $requestData['contacts']['_ids'][] = $cId;
        }
        $requestData['contacts']['_ids'] = array_unique($requestData['contacts']['_ids']);

        if (!empty($requestData['contacts']['_ids'])) {
            /** @var $ContactsTable ContactsTable */
            $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
            $contactsForExport = $ContactsTable->getContactsByIdsForExport(
                $requestData['contacts']['_ids']
            );

            foreach ($contactsForExport as $contact) {
                // Save dependencies for later processing
                $hostCommandUuids = [];
                $serviceCommandUuids = [];

                $DependencyCollector->addTimperiod(
                    $contact['host_timeperiod']['id'],
                    $contact['host_timeperiod']['uuid']
                );
                $DependencyCollector->addTimperiod(
                    $contact['service_timeperiod']['id'],
                    $contact['service_timeperiod']['uuid']
                );

                foreach ($contact['host_commands'] as $hc) {
                    $hostCommandUuids[] = $hc['uuid'];
                    $DependencyCollector->addCommand(
                        $hc['id'],
                        $hc['uuid']
                    );
                }

                foreach ($contact['service_commands'] as $sc) {
                    $serviceCommandUuids[] = $sc['uuid'];
                    $DependencyCollector->addCommand(
                        $sc['id'],
                        $sc['uuid']
                    );
                }

                $jsonContact = [
                    'uuid'                               => $contact['uuid'],
                    'name'                               => $contact['name'],
                    'description'                        => $contact['description'],
                    'email'                              => $contact['email'],
                    'phone'                              => $contact['phone'],
                    'user_id'                            => 0, // Not supported by import/export feature
                    'host_timeperiod_id'                 => $contact['host_timeperiod']['uuid'],
                    'service_timeperiod_id'              => $contact['service_timeperiod']['uuid'],
                    'host_notifications_enabled'         => $contact['host_notifications_enabled'],
                    'service_notifications_enabled'      => $contact['service_notifications_enabled'],
                    'notify_service_recovery'            => $contact['notify_service_recovery'],
                    'notify_service_warning'             => $contact['notify_service_warning'],
                    'notify_service_unknown'             => $contact['notify_service_unknown'],
                    'notify_service_critical'            => $contact['notify_service_critical'],
                    'notify_service_flapping'            => $contact['notify_service_flapping'],
                    'notify_service_downtime'            => $contact['notify_service_downtime'],
                    'notify_host_recovery'               => $contact['notify_host_recovery'],
                    'notify_host_down'                   => $contact['notify_host_down'],
                    'notify_host_unreachable'            => $contact['notify_host_unreachable'],
                    'notify_host_flapping'               => $contact['notify_host_flapping'],
                    'notify_host_downtime'               => $contact['notify_host_downtime'],
                    'host_push_notifications_enabled'    => $contact['host_push_notifications_enabled'],
                    'service_push_notifications_enabled' => $contact['service_push_notifications_enabled'],
                    'containers'                         => [
                        '_ids' => [ROOT_CONTAINER]
                    ],
                    'host_commands'                      => [
                        '_ids' => $hostCommandUuids
                    ],
                    'service_commands'                   => [
                        '_ids' => $serviceCommandUuids
                    ],
                    'customvariables'                    => []
                ];

                foreach ($contact['customvariables'] as $cv) {
                    $jsonContact['customvariables'][] = [
                        'objecttype_id' => OBJECT_CONTACT,
                        'name'          => $cv['name'],
                        'value'         => $cv['value'],
                        'password'      => $cv['password']
                    ];
                }

                $jsonExport['contacts'][] = $jsonContact;
            }
        }

        // Export time periods
        if (!isset($requestData['timeperiods']['_ids'])) {
            $requestData['timeperiods']['_ids'] = [];
        }

        // Merge manually selected time period ids (selected by the user) with time period ids that are required due to dependencies
        foreach ($DependencyCollector->getTimeperiods() as $tpId => $tpUuid) {
            $requestData['timeperiods']['_ids'][] = $tpId;
        }
        $requestData['timeperiods']['_ids'] = array_unique($requestData['timeperiods']['_ids']);

        if (!empty($requestData['timeperiods']['_ids'])) {
            /** @var $TimeperiodsTable TimeperiodsTable */
            $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
            $timeperiodsForExport = $TimeperiodsTable->getTimeperiodsByIdsForExport(
                $requestData['timeperiods']['_ids']
            );

            foreach ($timeperiodsForExport as $timeperiod) {
                $jsonTimeperiod = [
                    'uuid'                  => $timeperiod['uuid'],
                    'container_id'          => ROOT_CONTAINER,
                    'name'                  => $timeperiod['name'],
                    'description'           => $timeperiod['description'],
                    'calendar_id'           => 0, // Not supported by import/export
                    'timeperiod_timeranges' => []
                ];

                foreach ($timeperiod['timeperiod_timeranges'] as $timerange) {
                    $jsonTimeperiod['timeperiod_timeranges'][] = [
                        'day'   => $timerange['day'],
                        'start' => $timerange['start'],
                        'end'   => $timerange['end']
                    ];
                }
                $jsonExport['timeperiods'][] = $jsonTimeperiod;
            }
        }

        // Export commands
        if (!isset($requestData['commands']['_ids'])) {
            $requestData['commands']['_ids'] = [];
        }

        // Merge manually selected command ids (selected by the user) with command ids that are required due to dependencies
        foreach ($DependencyCollector->getCommands() as $cId => $cUuid) {
            $requestData['commands']['_ids'][] = $cId;
        }
        $requestData['commands']['_ids'] = array_unique($requestData['commands']['_ids']);

        if (!empty($requestData['commands']['_ids'])) {
            // CommandsTable is loaded at the beginning for event handlers
            $commandsForExport = $CommandsTable->getCommandsByIdsForExport(
                $requestData['commands']['_ids']
            );

            foreach ($commandsForExport as $command) {
                $jsonCommand = [
                    'name'             => $command['name'],
                    'command_line'     => $command['command_line'],
                    'command_type'     => $command['command_type'],
                    'human_args'       => $command['human_args'],
                    'uuid'             => $command['uuid'],
                    'description'      => $command['description'],
                    'commandarguments' => []
                ];

                foreach ($command['commandarguments'] as $commandargument) {
                    $jsonCommand['commandarguments'][] = [
                        'name'       => $commandargument['name'], // $ARG1$
                        'human_name' => $commandargument['human_name']
                    ];
                }
                $jsonExport['commands'][] = $jsonCommand;
            }

        }


        // We add this "salt" to the end of the json string that will be hashed to be able to detect if someone had tried
        // to modify the json file. That's not very secure. Who ever is smart enough to find this in the code
        // is probably also smart enough to modify the json file by itself without breaking anything
        $salt = 'No not modify this file manually!';

        $this->set('export', $jsonExport);
        $this->set('checksum', hash('sha256', json_encode($jsonExport) . $salt));
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['export', 'checksum', 'success']);

    }


    public function loadElementsForExport() { // ROOT container only
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
        /** @var ServicesTable $ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
        /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
        $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');

        $commands = $CommandsTable->getAllCommandsAsList();
        $commands = Api::makeItJavaScriptAble($commands);

        $timeperiods = $TimeperiodsTable->getTimeperiodsByContainerIdExact(ROOT_CONTAINER, 'list');
        $timeperiods = Api::makeItJavaScriptAble($timeperiods);

        $contacts = $ContactsTable->contactsByContainerId(ROOT_CONTAINER, 'list');
        $contacts = Api::makeItJavaScriptAble($contacts);

        $contactgroups = $ContactgroupsTable->getContactgroupsByContainerIdExact(ROOT_CONTAINER, 'list', 'id');
        $contactgroups = Api::makeItJavaScriptAble($contactgroups);

        $servicetemplates = $ServicetemplatesTable->getServicetemplatesByContainerId(ROOT_CONTAINER, 'list');
        $servicetemplates = Api::makeItJavaScriptAble($servicetemplates);

        $servicetemplategroups = $ServicetemplategroupsTable->getServicetemplategroupsByContainerIdExact(ROOT_CONTAINER, 'list', 'id');
        $servicetemplategroups = Api::makeItJavaScriptAble($servicetemplategroups);

        $this->set('commands', $commands);
        $this->set('timeperiods', $timeperiods);
        $this->set('contacts', $contacts);
        $this->set('contactgroups', $contactgroups);
        $this->set('servicetemplates', $servicetemplates);
        $this->set('servicetemplategroups', $servicetemplategroups);

        $this->viewBuilder()->setOption('serialize', [
            'commands',
            'timeperiods',
            'contacts',
            'contactgroups',
            'servicetemplates',
            'servicetemplategroups'
        ]);
    }
}
