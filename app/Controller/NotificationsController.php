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

use itnovum\openITCOCKPIT\Core\HostNotificationConditions;
use itnovum\openITCOCKPIT\Core\NotificationsControllerRequest;
use itnovum\openITCOCKPIT\Core\ServiceNotificationConditions;
use itnovum\openITCOCKPIT\Core\ValueObjects\HostStates;

class NotificationsController extends AppController {

    /*
     * Attention! In this case we load an external Model from the monitoring plugin! The Controller
     * use this external model to fetch the required data out of the database
     */
    public $uses = [
        MONITORING_NOTIFICATION,
        MONITORING_NOTIFICATION_HOST,
        'Host',
        MONITORING_HOSTSTATUS,
        'Service',
        MONITORING_SERVICESTATUS,
        'Documentation'
    ];

    public $components = ['Paginator', 'ListFilter.ListFilter', 'RequestHandler'];
    public $helpers = ['ListFilter.ListFilter', 'Status', 'Monitoring', 'CustomValidationErrors', 'Uuid'];
    public $layout = 'Admin.default';

    public $listFilters = [
        'index' => [
            'fields' => [
                'NotificationHost.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
            ],
        ],
        'hostNotification' => [
            'fields' => [
                'NotificationHost.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
            ],
        ],
        'serviceNotification' => [
            'fields' => [
                'Notification.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function index(){
        if (!isset($this->Paginator->settings['conditions'])) {
            $this->Paginator->settings['conditions'] = [];
        }
        //Process request and set request settings back to front end
        $NotificationsControllerRequest = new NotificationsControllerRequest($this->request, new HostStates());

        //Process conditions
        $Conditions = new HostNotificationConditions();
        $Conditions->setContainerIds($this->MY_RIGHTS);
        $Conditions->setLimit($NotificationsControllerRequest->getLimit());
        $Conditions->setFrom($NotificationsControllerRequest->getFrom());
        $Conditions->setTo($NotificationsControllerRequest->getTo());
        $Conditions->setOrder($NotificationsControllerRequest->getOrder());

        //Query notification records
        $query = $this->NotificationHost->getQuery($Conditions, $this->Paginator->settings['conditions']);
        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
        $all_notification = $this->Paginator->paginate(
            $this->NotificationHost->alias,
            [],
            [key($this->Paginator->settings['order'])]
        );
        $this->set('all_notification', $all_notification);
        //Data for json and xml view /notifications.json or .xml
        $this->set('_serialize', ['all_notification']);
        $this->set('NotificationListsettings', $NotificationsControllerRequest->getRequestSettingsForListSettings());
    }

    public function hostNotification($host_id){
        if (!$this->Host->exists($host_id)) {
            throw new NotFoundException(__('invalid host'));
        }

        //Process request and set request settings back to front end
        $NotificationsControllerRequest = new NotificationsControllerRequest($this->request, new HostStates());

        $host = $this->Host->find('first', [
            'fields' => [
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.address',
                'Host.host_url',
                'Host.host_type',
                'Host.container_id'
            ],
            'conditions' => [
                'Host.id' => $host_id,
            ],
            'contain' => [
                'Container',
            ],
        ]);

        //Check if user is permitted to see this object
        $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $host['Host']['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        //Process conditions
        $Conditions = new HostNotificationConditions();
        $Conditions->setLimit($NotificationsControllerRequest->getLimit());
        $Conditions->setFrom($NotificationsControllerRequest->getFrom());
        $Conditions->setTo($NotificationsControllerRequest->getTo());
        $Conditions->setOrder($NotificationsControllerRequest->getOrder());
        $Conditions->setHostUuid($host['Host']['uuid']);

        //Query host notification records
        $query = $this->NotificationHost->getQuery($Conditions, $this->Paginator->settings['conditions']);
        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
        $all_notification = $this->Paginator->paginate(
            $this->NotificationHost->alias,
            [],
            [key($this->Paginator->settings['order'])]
        );
        $docuExists = $this->Documentation->existsForUuid($host['Host']['uuid']);

        //Get meta data and push to front end
        $hoststatus = $this->Hoststatus->byUuid($host['Host']['uuid'], [
            'fields' => [
                'Hoststatus.current_state',
            ],
        ]);
        $this->set(compact(['host', 'all_notification', 'hoststatus', 'docuExists']));
        $this->set('NotificationListsettings', $NotificationsControllerRequest->getRequestSettingsForListSettings());

    }

    public function serviceNotification($service_id){
        if ($this->Service->exists($service_id)) {
            /*
            $service = $this->Service->find('first', [
                'fields' => [
                    'Service.id',
                    'Service.uuid',
                    'Service.name',
                    'Service.servicetemplate_id',
                    'Service.service_url'
                ],
                'conditions' => [
                    'Service.id' => $service_id
                ],
                'contain' => [
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.id',
                            'Servicetemplate.name'
                        ]
                    ],
                    'Host' => [
                        'fields' => [
                            'Host.id',
                            'Host.uuid',
                            'Host.name',
                            'Host.address'
                        ]
                    ]
                ]
            ]);*/
            $service = $this->Service->find('first', [
                'recursive' => -1,
                'contain' => [
                    'Host' => [
                        'Container',
                    ],
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.id',
                            'Servicetemplate.name',
                        ],
                    ],
                ],
                'conditions' => [
                    'Service.id' => $service_id,
                ],
            ]);

            if (!$this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
                $this->render403();

                return;
            }

            $allowEdit = false;
            if ($this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
                $allowEdit = true;
            }

            $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], [
                'fields' => [
                    'Objects.name2',
                    'Servicestatus.current_state',
                ],
            ]);
            $order = $this->ListsettingsParser('serviceNotification', ['hostUuid' => $service['Host']['uuid'], 'serviceUuid' => $service['Service']['uuid']]);
            $docuExists = $this->Documentation->existsForUuid($service['Service']['uuid']);
            $this->set('docuExists', $docuExists);

            //--force --doit --yes-i-know-what-i-do
            $all_notification = $this->Paginator->paginate(null, [], $order);
            $this->set(compact(['service', 'servicestatus', 'all_notification', 'allowEdit']));
        } else {
            throw new NotFoundException(__('Invalid host'));
        }
    }

    private function ListsettingsParser($action = 'index', $options = []){
        //Get Parameters out of $_GET
        if (isset($this->request->params['named']['Listsettings'])) {
            $this->request->data['Listsettings'] = $this->request->params['named']['Listsettings'];
        }

        switch ($action) {
            case 'serviceNotification':
                $requestSettings = $this->Notification->serviceListSettings($this->request->data, $options['hostUuid'], $options['serviceUuid']);

                $join = [
                    'join' => [
                        'table' => 'servicetemplates',
                        'type' => 'INNER',
                        'alias' => 'Servicetemplate',
                        'conditions' => 'Service.servicetemplate_id = Servicetemplate.id',
                    ],
                    'fields' => ['Servicetemplate.id', 'Servicetemplate.uuid', 'Servicetemplate.name'],
                ];


                break;

            case 'hostNotification':
                $requestSettings = $this->Notification->hostListSettings($this->request->data, $options['hostUuid']);
                $join = [
                    'join' => null,
                    'fields' => [],
                ];
                break;


            default:
                $requestSettings = $this->Notification->listSettings($this->request->data);
                $join = [
                    'join' => null,
                    'fields' => [],
                ];
                break;
        }

        //if(isset($this->request->data['Listsettings'])){
        //	$this->set('NotificationListsettings', $this->request->data['Listsettings']);
        //}
        $this->set('NotificationListsettings', $requestSettings['Listsettings']);

        if (!isset($requestSettings['paginator']['limit'])) {
            $requestSettings['paginator']['limit'] = $this->Paginator->settings['limit'];
            $requestSettings['Listsettings']['limit'] = $this->Paginator->settings['limit'];
        }


        if (!isset($requestSettings['notifiction_type']) && !isset($requestSettings['Listsettings']['view'])) {
            $requestSettings['notifiction_type'] = 0;
            $requestSettings['Listsettings']['view'] = 'hostOnly';
        }

        if (isset($requestSettings['Listsettings']['view'])) {
            if ($requestSettings['Listsettings']['view'] == 'serviceOnly') {
                $join = [
                    'join' => [
                        'table' => 'servicetemplates',
                        'type' => 'INNER',
                        'alias' => 'Servicetemplate',
                        'conditions' => 'Service.servicetemplate_id = Servicetemplate.id',
                    ],
                    'fields' => ['Servicetemplate.id', 'Servicetemplate.uuid', 'Servicetemplate.name'],
                ];
            }
        }

        if (!isset($requestSettings['Listsettings']['from'])) {
            $requestSettings['Listsettings']['from'] = date('d.m.Y H:i', strtotime('3 days ago'));
        }

        if (!isset($requestSettings['Listsettings']['to'])) {
            $requestSettings['Listsettings']['to'] = date('d.m.Y H:i', time());
        }

        if (isset($this->Paginator->settings['conditions'])) {
            //Merging our conditions and ListFilterComponents conditions
            if (isset($requestSettings['paginator']['conditions'])) {
                $requestSettings['paginator']['conditions'] = Hash::merge($requestSettings['paginator']['conditions'], $this->Paginator->settings['conditions']);
            } else {
                $requestSettings['paginator']['conditions'] = $this->Paginator->settings['conditions'];
            }
        }

        $order = [];
        if (isset($this->request->params['named']['sort']) && isset($this->request->params['named']['direction'])) {
            $order = [$this->request->params['named']['sort'] => $this->request->params['named']['direction']];
        } else {
            $order = ['Notification.start_time' => 'desc'];
        }
        //$this->Frontend->setJson('HostOrService', $requestSettings['Listsettings']['view']);

        // Set URL Parameters to template
        $ListsettingsUrlParams = [];
        if (!empty($requestSettings['Listsettings'])) {
            $ListsettingsUrlParams['Listsettings'] = $requestSettings['Listsettings'];
            $this->set('ListsettingsUrlParams', $ListsettingsUrlParams);
        } else {
            $this->set('ListsettingsUrlParams', []);
        }


        $this->Paginator->settings = Hash::merge(
            $this->Notification->paginatorSettings(
                $requestSettings['notifiction_type'], $order, $requestSettings['conditions'], $join, $this->MY_RIGHTS
            ),
            $requestSettings['paginator']
        );

        return [key($order)];
    }
}
