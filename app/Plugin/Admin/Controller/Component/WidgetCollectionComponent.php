<?php

class WidgetCollectionComponent extends Component
{
    public $stateArrayHost = [];
    public $stateArrayService = [];
    public $parentOutages = [];
    public $hostsInDowntime = [];
    public $servicesInDowntime = [];
    public $serviceIdsForSelect = [];
    public $widgetSettingsCache = [];
    public $servicesStatusList = [];
    public $hostsStatusList = [];
    public $allMaps = [];
    public $widgetMaps = [];
    public $allNotes = [];

    public $widgetTypes = [
        1  => [
            'constantName'  => 'WIDGET_DOWNTIMEHOSTS',
            'element'       => 'Admin.Dashboard/widget_downtime_hosts',
            'icon'          => 'power-off',
            'title'         => 'Host downtimes',
            'defaultWidth'  => 5,
            'defaultHeight' => 13,
        ],
        2  => [
            'constantName'  => 'WIDGET_PIECHARTHOSTS',
            'element'       => 'Admin.Dashboard/widget_piechart_hosts',
            'icon'          => 'pie-chart',
            'title'         => 'Hosts Piechart',
            'defaultWidth'  => 5,
            'defaultHeight' => 13,
        ],
        3  => [
            'constantName'  => 'WIDGET_PARENTOUTAGES',
            'element'       => 'Admin.Dashboard/widget_parent_outages',
            'icon'          => 'exchange',
            'title'         => 'Parentoutages',
            'defaultWidth'  => 5,
            'defaultHeight' => 11,
        ],
        4  => [
            'constantName'  => 'WIDGET_DOWNTIMESERVICES',
            'element'       => 'Admin.Dashboard/widget_downtime_services',
            'icon'          => 'power-off',
            'title'         => 'Services downtimes',
            'defaultWidth'  => 5,
            'defaultHeight' => 13,
        ],
        5  => [
            'constantName'  => 'WIDGET_PIECHARTSERVICES',
            'element'       => 'Admin.Dashboard/widget_piechart_services',
            'icon'          => 'pie-chart',
            'title'         => 'Services Piechart',
            'defaultWidth'  => 5,
            'defaultHeight' => 13,
        ],
        6  => [
            'constantName'  => 'WIDGET_TRAFFICLIGHT',
            'element'       => 'Admin.Dashboard/widget_trafficlight',
            'icon'          => 'road',
            'title'         => 'Trafficlight',
            'defaultWidth'  => 4,
            'defaultHeight' => 17,
        ],
        7  => [
            'constantName'  => 'WIDGET_TACHO',
            'element'       => 'Admin.Dashboard/widget_tacho',
            'icon'          => 'tachometer',
            'title'         => 'Tachometer',
            'defaultWidth'  => 4,
            'defaultHeight' => 17,
        ],
        8  => [
            'constantName'  => 'WIDGET_WELCOME',
            'element'       => 'Admin.Dashboard/widget_welcome',
            'icon'          => 'comment',
            'title'         => 'Welcome',
            'defaultWidth'  => 5,
            'defaultHeight' => 11,
        ],
        9  => [
            'constantName'  => 'WIDGET_SERVICESSTATUSLIST',
            'element'       => 'Admin.Dashboard/widget_statuslist_services',
            'icon'          => 'list-alt',
            'title'         => 'Services Statuslist',
            'defaultWidth'  => 8,
            'defaultHeight' => 25,
        ],
        10 => [
            'constantName'  => 'WIDGET_HOSTSSTATUSLIST',
            'element'       => 'Admin.Dashboard/widget_statuslist_hosts',
            'icon'          => 'list-alt',
            'title'         => 'Hosts Statuslists',
            'defaultWidth'  => 8,
            'defaultHeight' => 25,
        ],
        11 => [
            'constantName'  => 'WIDGET_MAPS',
            'element'       => 'Admin.Dashboard/widget_maps',
            'icon'          => 'globe',
            'title'         => 'Maps',
            'defaultWidth'  => 12,
            'defaultHeight' => 46,
        ],
        /*12 => [
            'constantName' => 'WIDGET_BROWSER',
            'element' => 'Admin.Dashboard/widget_browser',
            'icon' => 'laptop',
            'title' => 'Browser',
            'defaultWidth' => 12,
            'defaultHeight' => 35,
        ],*/
        13 => [
            'constantName'  => 'WIDGET_NOTICE',
            'element'       => 'Admin.Dashboard/widget_notice',
            'icon'          => 'pencil-square-o',
            'title'         => 'Notice',
            'defaultWidth'  => 5,
            'defaultHeight' => 15,
        ],
        14 => [
            'constantName'  => 'WIDGET_GRAPHGENERATOR',
            'element'       => 'Admin.Dashboard/widget_graphgenerator',
            'icon'          => 'line-chart',
            'title'         => 'Graph Generator',
            'defaultWidth'  => 12,
            'defaultHeight' => 24,
        ],
        15 => [
            'constantName'  => 'WIDGET_HALFPIECHARTHOSTS',
            'element'       => 'Admin.Dashboard/widget_half_piechart_hosts',
            'icon'          => 'pie-chart',
            'title'         => 'Hosts Half Piechart',
            'defaultWidth'  => 5,
            'defaultHeight' => 13,
        ],
        16 => [
            'constantName'  => 'WIDGET_HALFPIECHARTSERVICES',
            'element'       => 'Admin.Dashboard/widget_half_piechart_services',
            'icon'          => 'pie-chart',
            'title'         => 'Services Half Piechart',
            'defaultWidth'  => 5,
            'defaultHeight' => 13,
        ],
    ];

    public function initialize(Controller $controller, $settings = [])
    {
        $this->_settings = Set::merge($this->_settings, $settings);
        $this->_controller = $controller;
        $this->_request = $controller->request;
        $this->_controller->set('widgetTypes', $this->widgetTypes);
        $this->_defineConstants();
    }

    private function _defineConstants()
    {
        foreach ($this->widgetTypes as $key => $widgetType) {
            define($widgetType['constantName'], $key);
        }
    }

    public function setWidgetDataToView($widgets, $MapModule = false)
    {
        foreach ($widgets as $widget) {
            switch ($widget['Widget']['type_id']) {
                case WIDGET_DOWNTIMEHOSTS:
                    $this->downtimeHosts();
                    break;

                case WIDGET_PIECHARTHOSTS:
                    $this->piechartHosts();
                    break;

                case WIDGET_PARENTOUTAGES:
                    $this->parentOutages();
                    break;

                case WIDGET_DOWNTIMESERVICES:
                    $this->downtimeServices();
                    break;

                case WIDGET_PIECHARTSERVICES:
                    $this->piechartServices();
                    break;

                case WIDGET_WELCOME:
                    $this->welcome();
                    break;

                case WIDGET_TRAFFICLIGHT:
                    $this->trafficLight($widget);
                    break;

                case WIDGET_TACHO:
                    $this->tachometer($widget);
                    break;

                case WIDGET_SERVICESSTATUSLIST:
                    $this->serviceStatusList($widget);
                    break;

                case WIDGET_HOSTSSTATUSLIST:
                    $this->hostsStatusList($widget);
                    break;

                case WIDGET_MAPS:
                    $this->maps($widget, $MapModule);
                    break;

                /*case WIDGET_BROWSER:
                    $this->browser($widget);
                    break;*/

                case WIDGET_NOTICE:
                    $this->notice($widget);
                    break;

                case WIDGET_GRAPHGENERATOR:
                    $this->graphgenerator($widget);
                    break;

                case WIDGET_HALFPIECHARTHOSTS:
                    $this->halfPiechartHosts();
                    break;

                case WIDGET_HALFPIECHARTSERVICES:
                    $this->halfPiechartServices();
                    break;
            }
        }

    }

    public function getDefaultTitle($typeId)
    {
        return $this->widgetTypes[$typeId]['title'];
    }

    public function getDefaultWidth($typeId)
    {
        return $this->widgetTypes[$typeId]['defaultWidth'];
    }

    public function getDefaultHeight($typeId)
    {
        return $this->widgetTypes[$typeId]['defaultHeight'];
    }

    private function _getStateArrayHost()
    {
        if (empty($this->stateArrayHost)) {
            for ($i = 0; $i < 3; $i++) {
                $this->stateArrayHost[$i] = $this->_controller->Hoststatus->find('count', [
                    'recursive'  => -1,
                    'joins'      => [
                        [
                            'table'      => 'nagios_objects',
                            'type'       => 'INNER',
                            'alias'      => 'Objects',
                            'conditions' => 'Objects.object_id = Hoststatus.host_object_id',
                        ], [
                            'table'      => 'hosts',
                            'type'       => 'INNER',
                            'alias'      => 'Host',
                            'conditions' => 'Host.uuid = Objects.name1',
                        ],
                    ],
                    'conditions' => [
                        'current_state' => $i,
                        'Host.disabled' => 0,
                    ],
                ]);
            }
        }
    }

    private function _getStateArrayServices()
    {
        if (empty($this->stateArrayService)) {
            for ($i = 0; $i < 4; $i++) {
                $this->stateArrayService[$i] = $this->_controller->Servicestatus->find('count', [
                    'recursive'  => -1,
                    'joins'      => [
                        [
                            'table'      => 'nagios_objects',
                            'type'       => 'INNER',
                            'alias'      => 'Objects',
                            'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                        ], [
                            'table'      => 'services',
                            'type'       => 'INNER',
                            'alias'      => 'Service',
                            'conditions' => 'Service.uuid = Objects.name2',
                        ], [
                            'table'      => 'servicetemplates',
                            'type'       => 'INNER',
                            'alias'      => 'Servicetemplate',
                            'conditions' => 'Servicetemplate.id = Service.servicetemplate_id',
                        ],
                    ],
                    'conditions' => [
                        'current_state'    => $i,
                        'Service.disabled' => 0,
                    ],
                ]);
            }
        }
    }

    public function welcome()
    {
        $this->_getStateArrayHost();
        $this->_getStateArrayServices();
        $this->_controller->set(['state_array_host' => $this->stateArrayHost, 'state_array_service' => $this->stateArrayService]);
    }

    public function parentOutages()
    {
        if (empty($this->parentOutages)) {
            $this->parentOutages = $this->_controller->Parenthost->find('all', [
                'joins'      => [
                    [
                        'table'      => 'nagios_objects',
                        'type'       => 'INNER',
                        'alias'      => 'Objects',
                        'conditions' => 'Objects.object_id = Parenthost.parent_host_object_id',
                    ], [
                        'table'      => 'nagios_hoststatus',
                        'type'       => 'INNER',
                        'alias'      => 'Hoststatus',
                        'conditions' => 'Hoststatus.host_object_id = Parenthost.parent_host_object_id',
                    ], [
                        'table'      => 'hosts',
                        'type'       => 'INNER',
                        'alias'      => 'Host',
                        'conditions' => 'Host.uuid = Objects.name1',
                    ],
                ],
                'fields'     => [
                    'Parenthost.parent_host_object_id',
                    'Hoststatus.current_state',
                    'Hoststatus.output',
                    'Objects.name1',
                    'Host.name',
                    'Host.id',
                ],
                'conditions' => [
                    'Hoststatus.current_state >' => 0,
                ],
                'group'      => ['Host.uuid'],
            ]);
        }
        $this->_controller->set(['parentOutages' => $this->parentOutages]);
    }

    public function piechartHosts()
    {
        $this->_getStateArrayHost();
        $this->_controller->set(['state_array_host' => $this->stateArrayHost]);
    }

    public function piechartServices()
    {
        $this->_getStateArrayServices();
        $this->_controller->set(['state_array_service' => $this->stateArrayService]);
    }

    public function halfPiechartHosts()
    {
        $this->_getStateArrayHost();
        $this->_controller->set(['state_array_host' => $this->stateArrayHost]);

        $allHosts = $this->_controller->Hoststatus->find('all', [
            'recursive'  => -1,
            'joins'      => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.object_id = Hoststatus.host_object_id',
                ], [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => [
                        'AND' => [
                            'Host.uuid = Objects.name1',
                            'Objects.name2 IS NULL',
                            'Host.disabled' => 0,
                        ],
                    ],
                ],
            ],
            'fields'     => [
                'Hoststatus.host_object_id',
                'Hoststatus.scheduled_downtime_depth',
                'Hoststatus.current_state',
                'Hoststatus.problem_has_been_acknowledged',
            ],
            'conditions' => [
                'Hoststatus.current_state > 0',
            ],
        ]);
        $this->_controller->set(['allHosts' => $allHosts]);
    }

    public function halfPiechartServices()
    {
        $this->_getStateArrayServices();
        $this->_controller->set(['state_array_service' => $this->stateArrayService]);

        $allServices = $this->_controller->Servicestatus->find('all', [
            'recursive'  => -1,
            'fields'     => [
                'Servicestatus.service_object_id',
                'Servicestatus.scheduled_downtime_depth',
                'Servicestatus.current_state',
                'Servicestatus.problem_has_been_acknowledged',
                'Servicestatus.active_checks_enabled',
                'Hoststatus.current_state',
            ],
            'joins'      => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'ServiceObject',
                    'conditions' => 'ServiceObject.object_id = Servicestatus.service_object_id',
                ],
                [
                    'table'      => 'services',
                    'type'       => 'INNER',
                    'alias'      => 'Service',
                    'conditions' => [
                        'AND' => [
                            'Service.uuid = ServiceObject.name2',
                            'Service.disabled' => 0,
                        ],
                    ],
                ],
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'HostObject',
                    'conditions' => [
                        'AND' => [
                            'HostObject.name1 = ServiceObject.name1',
                            'HostObject.name2 IS NULL',
                        ],
                    ],
                ],
                [
                    'table'      => 'nagios_hoststatus',
                    'type'       => 'INNER',
                    'alias'      => 'Hoststatus',
                    'conditions' => 'Hoststatus.host_object_id = HostObject.object_id',
                ],
                [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => [
                        'AND' => [
                            'Host.uuid = HostObject.name1',
                            'Host.disabled' => 0,
                        ],
                    ],
                ],
            ],
            'conditions' => [
                'Servicestatus.current_state > 0',
            ],
        ]);
        $this->_controller->set(['allServices' => $allServices]);
    }

    public function downtimeHosts()
    {
        if (empty($this->hostsInDowntime)) {
            $this->hostsInDowntime = $this->_controller->Hoststatus->find('all', [
                'recursive'  => -1,
                'fields'     => [
                    'Hoststatus.host_object_id',
                    'Objects.name1',
                    'Host.name',
                    'Host.id',
                    'Hoststatus.scheduled_downtime_depth',
                ],
                'joins'      => [
                    [
                        'table'      => 'nagios_objects',
                        'type'       => 'INNER',
                        'alias'      => 'Objects',
                        'conditions' => 'Objects.object_id = Hoststatus.host_object_id',
                    ], [
                        'table'      => 'hosts',
                        'type'       => 'INNER',
                        'alias'      => 'Host',
                        'conditions' => 'Host.uuid = Objects.name1',
                    ],
                ],
                'conditions' => [
                    'Hoststatus.scheduled_downtime_depth >' => 0,
                ],
            ]);
        }
        $this->_controller->set(['hostsInDowntime' => $this->hostsInDowntime]);
    }

    public function downtimeServices()
    {
        if (empty($this->servicesInDowntime)) {
            $this->servicesInDowntime = $this->_controller->Servicestatus->find('all', [
                'recursive'  => -1,
                'fields'     => [
                    'Servicestatus.service_object_id',
                    'Objects.name2',
                    'Service.name',
                    'Service.id',
                    'Service.servicetemplate_id',
                    'Servicestatus.scheduled_downtime_depth',
                    'Servicetemplate.id',
                    'Servicetemplate.name',
                ],
                'joins'      => [
                    [
                        'table'      => 'nagios_objects',
                        'type'       => 'INNER',
                        'alias'      => 'Objects',
                        'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                    ], [
                        'table'      => 'services',
                        'type'       => 'INNER',
                        'alias'      => 'Service',
                        'conditions' => 'Service.uuid = Objects.name2',
                    ], [
                        'table'      => 'servicetemplates',
                        'type'       => 'INNER',
                        'alias'      => 'Servicetemplate',
                        'conditions' => 'Servicetemplate.id = Service.servicetemplate_id',
                    ],
                ],
                'conditions' => [
                    'Servicestatus.scheduled_downtime_depth >' => 0,
                ],
            ]);
        }
        $this->_controller->set(['servicesInDowntime' => $this->servicesInDowntime]);
    }

    public function trafficLight($widget)
    {
        if (empty($this->serviceIdsForSelect)) {
            //$this->serviceIdsForSelect = $this->_controller->Host->servicesByContainerIds($this->_controller->MY_RIGHTS,'list');
            $this->serviceIdsForSelect = $this->_controller->getAllServiceWithCurrentState($this->_controller->MY_RIGHTS);
        }
        $this->_controller->set(['service_ids_for_select' => $this->serviceIdsForSelect]);

        if ($widget['Widget']['service_id']) {
            $service = $this->_controller->Objects->find('first', [
                'recursive'  => -1,
                'fields'     => [
                    'Service.uuid',
                    'Service.check_interval',
                    'Servicetemplate.check_interval',
                    'Servicetemplate.name',
                    'Host.name',
                ],
                'conditions' => [
                    'Service.id' => $widget['Widget']['service_id'],
                ],
                'joins'      => [
                    [
                        'table'      => 'services',
                        'alias'      => 'Service',
                        'conditions' => [
                            'Objects.name2 = Service.uuid',
                        ],
                    ],
                    [
                        'table'      => 'hosts',
                        'alias'      => 'Host',
                        'conditions' => [
                            'Objects.name1 = Host.uuid',
                        ],
                    ], [
                        'table'      => 'servicetemplates',
                        'type'       => 'INNER',
                        'alias'      => 'Servicetemplate',
                        'conditions' => [
                            'Servicetemplate.id = Service.servicetemplate_id',
                        ],
                    ], [
                        'table'      => 'nagios_servicestatus',
                        'type'       => 'LEFT OUTER',
                        'alias'      => 'Servicestatus',
                        'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                    ],
                ],
            ]);

            if (empty($service)) {
                $widgetConfiguration = [
                    'serviceGone' => 'Service not found! Maybe it has been deleted.',
                ];
            } else {
                $fields = [
                    'fields' => [
                        'Servicestatus.current_state',
                        'Servicestatus.is_flapping',
                        'Objects.name2',
                        'Objects.objecttype_id',
                    ],
                ];
                $serviceStatus = $this->_controller->Servicestatus->byUuid($service['Service']['uuid'], $fields);

                if ($service['Service']['check_interval']) {
                    $checkInterval = $service['Service']['check_interval'];
                } else {
                    $checkInterval = $service['Servicetemplate']['check_interval'];
                }
                //debug($serviceStatus);
                $widgetConfiguration = [
                    'service_id'     => $widget['Widget']['service_id'],
                    'current_state'  => $serviceStatus[$service['Service']['uuid']]['Servicestatus']['current_state'],
                    'is_flapping'    => $serviceStatus[$service['Service']['uuid']]['Servicestatus']['is_flapping'],
                    'check_interval' => $checkInterval,
                    'host_name'      => $service['Host']['name'],
                    'service_name'   => $service['Servicetemplate']['name'],
                ];
            }
            $this->widgetSettingsCache[$widget['Widget']['type_id']][$widget['Widget']['id']] = $widgetConfiguration;
            $this->_controller->Frontend->setJson('allWidgetParameters', $this->widgetSettingsCache);
        }

    }

    public function tachometer($widget)
    {

        if (empty($this->serviceIdsForSelect)) {
            //$this->serviceIdsForSelect = $this->_controller->Host->servicesByContainerIds($this->_controller->MY_RIGHTS,'list');
            $this->serviceIdsForSelect = $this->_controller->getAllServiceWithCurrentState($this->_controller->MY_RIGHTS);
        }
        $this->_controller->set(['service_ids_for_select' => $this->serviceIdsForSelect]);


        if ($widget['Widget']['service_id']) {
            $service = $this->_controller->Objects->find('first', [
                'recursive'  => -1,
                'fields'     => [
                    'Service.uuid',
                    'Service.check_interval',
                    'Servicetemplate.check_interval',
                    'Servicetemplate.name',
                    'Host.name',
                    'Servicestatus.perfdata',
                ],
                'conditions' => [
                    'Service.id' => $widget['Widget']['service_id'],
                ],
                'joins'      => [
                    [
                        'table'      => 'services',
                        'alias'      => 'Service',
                        'conditions' => [
                            'Objects.name2 = Service.uuid',
                        ],
                    ],
                    [
                        'table'      => 'hosts',
                        'alias'      => 'Host',
                        'conditions' => [
                            'Objects.name1 = Host.uuid',
                        ],
                    ], [
                        'table'      => 'servicetemplates',
                        'type'       => 'INNER',
                        'alias'      => 'Servicetemplate',
                        'conditions' => [
                            'Servicetemplate.id = Service.servicetemplate_id',
                        ],
                    ], [
                        'table'      => 'nagios_servicestatus',
                        'type'       => 'LEFT OUTER',
                        'alias'      => 'Servicestatus',
                        'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                    ],
                ],
            ]);

            if (empty($service)) {
                $widgetConfiguration = [
                    'serviceGone' => 'Service not found! Maybe it has been deleted.',
                ];
            } else {
                $servicePerfData = $this->_controller->Rrd->parsePerfData($service['Servicestatus']['perfdata']);
                //debug($servicePerfData);
                $widgetTacho = $this->_controller->Widget->find('first', [
                    'contain'    => [
                        'Service'     => [
                            'fields'          => [
                                'id',
                                'uuid',
                                'name',
                                'check_interval',
                            ],
                            'Servicetemplate' => [
                                'fields' => [
                                    'name',
                                    'check_interval',
                                ],
                            ],
                            'Host'            => [
                                'fields' => [
                                    'Host.name',
                                ],
                            ],
                        ],
                        'WidgetTacho' => [
                        ],
                    ],
                    'conditions' => [
                        'Widget.id' => $widget['Widget']['id'],
                    ],
                ]);
                //debug($widgetTacho);
                if ($service['Service']['check_interval']) {
                    $checkInterval = $service['Service']['check_interval'];
                } else {
                    $checkInterval = $service['Servicetemplate']['check_interval'];
                }

                if (count($servicePerfData) > 1) {
                    $current = $servicePerfData[$widgetTacho['WidgetTacho']['data_source']]['current'];
                } else {
                    $current = $servicePerfData[key($servicePerfData)]['current'];
                }
                //debug($current);
                //debug($serviceStatus);
                $widgetConfiguration = [
                    'service_id'     => $widget['Widget']['service_id'],
                    'check_interval' => $checkInterval,
                    'host_name'      => $service['Host']['name'],
                    'service_name'   => $service['Servicetemplate']['name'],
                    'min'            => $widgetTacho['WidgetTacho']['min'],
                    'max'            => $widgetTacho['WidgetTacho']['max'],
                    'warn'           => $widgetTacho['WidgetTacho']['warn'],
                    'crit'           => $widgetTacho['WidgetTacho']['crit'],
                    'data_source'    => $widgetTacho['WidgetTacho']['data_source'],
                    'current'        => $current,
                ];
            }
            $this->widgetSettingsCache[$widget['Widget']['type_id']][$widget['Widget']['id']] = $widgetConfiguration;
            //debug($this->widgetSettingsCache);
            $this->_controller->Frontend->setJson('allWidgetParameters', $this->widgetSettingsCache);
        }

    }

    public function serviceStatusList($widget)
    {
        //debug($widget['Widget']['id']);
        if (empty($this->servicesStatusList[$widget['Widget']['id']])) {
            $this->servicesStatusList[$widget['Widget']['id']] = $this->_controller->Widget->find('first', [
                'recursive'  => -1,
                'contain'    => [
                    'WidgetServiceStatusList',
                ],
                'conditions' => [
                    'Widget.id' => $widget['Widget']['id'],
                ],
            ]);
        }
        //debug($this->servicesStatusList);

        $showOK = $this->servicesStatusList[$widget['Widget']['id']]['WidgetServiceStatusList']['show_ok'];
        $showWarning = $this->servicesStatusList[$widget['Widget']['id']]['WidgetServiceStatusList']['show_warning'];
        $showCrit = $this->servicesStatusList[$widget['Widget']['id']]['WidgetServiceStatusList']['show_critical'];
        $showUnknown = $this->servicesStatusList[$widget['Widget']['id']]['WidgetServiceStatusList']['show_unknown'];
        $showAcknowledged = $this->servicesStatusList[$widget['Widget']['id']]['WidgetServiceStatusList']['show_acknowledged'];
        $showDowntime = $this->servicesStatusList[$widget['Widget']['id']]['WidgetServiceStatusList']['show_downtime'];

        $statesArray = [];
        $extraConditions = [];

        if ($showOK === 'true') {
            $statesArray[] = '0';
        }
        if ($showWarning === 'true') {
            $statesArray[] = '1';
        }
        if ($showCrit === 'true') {
            $statesArray[] = '2';
        }
        if ($showUnknown === 'true') {
            $statesArray[] = '3';
        }

        if ($showAcknowledged === 'true') {
            $extraConditions[] = '1';
        } else {
            $extraConditions[] = '0';
        }
        if ($showDowntime === 'true') {
            $extraConditions[] = '0';
        }

        //debug($statesArray);
        $statesToSelect = implode(' OR current_state = ', $statesArray);

        if ($statesToSelect === '') {
            $statesToSelect = '4';
        }

        $extraConditionsString = implode(' OR scheduled_downtime_depth > ', $extraConditions);

        $servicestatus = $this->_controller->Servicestatus->find('all', [
            'recursive'  => -1,
            'fields'     => [
                'Host.name',
                'Host.id',
                'Service.uuid',
                'Service.name',
                'Service.id',
                'Servicetemplate.name',
                'Servicestatus.current_state',
                'Servicestatus.status_update_time',
                'Servicestatus.is_flapping',
                'Servicestatus.active_checks_enabled',
                'Servicestatus.last_hard_state_change',
                'Servicestatus.problem_has_been_acknowledged',
                'Servicestatus.scheduled_downtime_depth',
            ],
            'conditions' => [
                'AND' => [
                    'Servicestatus.current_state = '.$statesToSelect,
                    'Servicestatus.problem_has_been_acknowledged = '.$extraConditionsString,
                ],
            ],
            'joins'      => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                ],
                [
                    'table'      => 'services',
                    'type'       => 'INNER',
                    'alias'      => 'Service',
                    'conditions' => 'Service.uuid = Objects.name2',
                ],
                [
                    'table'      => 'servicetemplates',
                    'type'       => 'INNER',
                    'alias'      => 'Servicetemplate',
                    'conditions' => 'Servicetemplate.id = Service.servicetemplate_id',
                ],
                [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'Host.id = Service.host_id',
                ],
            ],
        ]);
        //debug($this->servicesStatusList);
        $this->_controller->set(['config' => $this->servicesStatusList]);

        $this->_controller->set(['servicestatus' => $servicestatus]);

        $widgetConfiguration = [
            'show_ok'            => $showOK,
            'show_warning'       => $showWarning,
            'show_critical'      => $showCrit,
            'show_unknown'       => $showUnknown,
            'show_acknowledged'  => $showAcknowledged,
            'show_downtime'      => $showDowntime,
            'scroll_direction'   => $this->servicesStatusList[$widget['Widget']['id']]['WidgetServiceStatusList']['scroll_direction'],
            'services_per_page'  => $this->servicesStatusList[$widget['Widget']['id']]['WidgetServiceStatusList']['services_per_page'],
            'refresh_interval'   => $this->servicesStatusList[$widget['Widget']['id']]['WidgetServiceStatusList']['refresh_interval'],
            'animation_interval' => $this->servicesStatusList[$widget['Widget']['id']]['WidgetServiceStatusList']['animation_interval'],
        ];
        //debug($widgetConfiguration);
        $this->widgetSettingsCache[$widget['Widget']['type_id']][$widget['Widget']['id']] = $widgetConfiguration;
        //debug($this->widgetSettingsCache);
        $this->_controller->Frontend->setJson('allWidgetParameters', $this->widgetSettingsCache);

    }

    public function hostsStatusList($widget)
    {
        if (empty($this->hostsStatusList[$widget['Widget']['id']])) {
            $this->hostsStatusList[$widget['Widget']['id']] = $this->_controller->Widget->find('first', [
                'recursive'  => -1,
                'contain'    => [
                    'WidgetHostStatusList',
                ],
                'conditions' => [
                    'Widget.id' => $widget['Widget']['id'],
                ],
            ]);
        }
        //debug($this->hostsStatusList);

        $showUp = $this->hostsStatusList[$widget['Widget']['id']]['WidgetHostStatusList']['show_up'];
        $showDown = $this->hostsStatusList[$widget['Widget']['id']]['WidgetHostStatusList']['show_down'];
        $showUnreachable = $this->hostsStatusList[$widget['Widget']['id']]['WidgetHostStatusList']['show_unreachable'];
        $showAcknowledged = $this->hostsStatusList[$widget['Widget']['id']]['WidgetHostStatusList']['show_acknowledged'];
        $showDowntime = $this->hostsStatusList[$widget['Widget']['id']]['WidgetHostStatusList']['show_downtime'];

        $statesArray = [];
        $extraConditions = [];

        if ($showUp === 'true') {
            $statesArray[] = '0';
        }
        if ($showDown === 'true') {
            $statesArray[] = '1';
        }
        if ($showUnreachable === 'true') {
            $statesArray[] = '2';
        }

        if ($showAcknowledged === 'true') {
            $extraConditions[] = '1';
        } else {
            $extraConditions[] = '0';
        }
        if ($showDowntime === 'true') {
            $extraConditions[] = '0';
        }

        $statesToSelect = implode(' OR current_state = ', $statesArray);

        if ($statesToSelect === '') {
            $statesToSelect = '4';
        }

        $extraConditionsString = implode(' OR scheduled_downtime_depth > ', $extraConditions);

        $hoststatus = $this->_controller->Hoststatus->find('all', [
            'recursive'  => -1,
            'fields'     => [
                'Host.name',
                'Host.id',
                'Host.uuid',
                'Hoststatus.current_state',
                'Hoststatus.status_update_time',
                'Hoststatus.is_flapping',
                'Hoststatus.active_checks_enabled',
                'Hoststatus.problem_has_been_acknowledged',
                'Hoststatus.last_hard_state_change',
                'Hoststatus.scheduled_downtime_depth',
            ],
            'conditions' => [
                'AND' => [
                    'Hoststatus.current_state = '.$statesToSelect,
                    'Hoststatus.problem_has_been_acknowledged = '.$extraConditionsString,
                ],
            ],
            'joins'      => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.object_id = Hoststatus.host_object_id',
                ],
                [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'Host.uuid = Objects.name1',
                ],
            ],
        ]);

        $this->_controller->set(['config' => $this->hostsStatusList]);
        $this->_controller->set(['hoststatus' => $hoststatus]);

        $widgetConfiguration = [
            'show_up'            => $showUp,
            'show_down'          => $showDown,
            'show_unreachable'   => $showUnreachable,
            'show_acknowledged'  => $showAcknowledged,
            'show_downtime'      => $showDowntime,
            'scroll_direction'   => $this->hostsStatusList[$widget['Widget']['id']]['WidgetHostStatusList']['scroll_direction'],
            'hosts_per_page'     => $this->hostsStatusList[$widget['Widget']['id']]['WidgetHostStatusList']['hosts_per_page'],
            'refresh_interval'   => $this->hostsStatusList[$widget['Widget']['id']]['WidgetHostStatusList']['refresh_interval'],
            'animation_interval' => $this->hostsStatusList[$widget['Widget']['id']]['WidgetHostStatusList']['animation_interval'],
        ];
        //debug($widgetConfiguration);
        $this->widgetSettingsCache[$widget['Widget']['type_id']][$widget['Widget']['id']] = $widgetConfiguration;
        //debug($this->widgetSettingsCache);
        $this->_controller->Frontend->setJson('allWidgetParameters', $this->widgetSettingsCache);
    }

    public function maps($widget, $MapModule)
    {
        if (in_array('MapModule', CakePlugin::loaded())) {
            if (!$MapModule) {
                $this->_controller->loadModel('Map');
                $this->_controller->loadModel('Mapitem');
                $this->_controller->loadModel('Mapline');
                $this->_controller->loadModel('Mapgadget');
                $this->_controller->loadModel('Maptext');
                $this->_controller->loadModel('Background');
            }

            $MapModule = true;
            $this->_controller->set(compact('MapModule'));
            //$this->_controller->Frontend->setJson('MapModule', $MapModule);
            if (empty($this->allMaps)) {
                $allMaps = $this->_controller->Map->find('all', [
                    'fields' => [
                        'Map.name',
                        'Map.id',
                    ],
                ]);
                foreach ($allMaps as $map) {
                    $this->allMaps[$map['Map']['id']] = $map['Map']['name'];
                }
            }
            $this->_controller->set(['allMaps' => $this->allMaps]);

            //debug($widget);
            if ($widget["Widget"]["map_id"]) {
                $mapId = $widget["Widget"]["map_id"];
                $rotate = null;
                if (isset($this->request->params['named']['rotate'])) {
                    $isFirst = true;
                    $rotation = [];
                    foreach ($this->request->params['named']['rotate'] as $rotation_map_id) {
                        if ($isFirst === true) {
                            $id = $rotation_map_id;
                            $isFirst = false;
                        } else {
                            $rotation[] = $rotation_map_id;
                        }
                    }

                    //Add the current map id as the last element in rotation array, to rotate
                    $rotation[] = $id;
                    $this->_controller->Frontend->setJson('rotation_ids', $rotation);
                    $this->_controller->Frontend->setJson('interval', $this->request->params['named']['interval']);

                } else {
                    $this->_controller->Frontend->setJson('interval', 0);
                }

                if (!$this->_controller->Map->exists($mapId)) {
                    //throw new NotFoundException(__('Invalid map'));
                }

                $isFullscreen = false;
                if (isset($this->request->params['named']['fullscreen'])) {
                    $this->layout = 'Admin.fullscreen';
                    $isFullscreen = true;
                    $this->Frontend->setJson('is_fullscren', true);
                }

                $mapstatus = [];

                $map = $this->_controller->Map->find('first', [
                    'conditions' => [
                        'Map.id' => $mapId,
                    ],
                    'fields'     => [
                        'Map.*',
                    ],
                ]);
                $map = Hash::extract($map, 'Map');

                $mapstatus['Map'] = $map;

                $map = $this->_controller->Map->findById($mapId);


                $map_items = $this->_controller->Mapitem->find('all', [
                    //'recursive' => -1,
                    'joins'      => [
                        [
                            'table'      => 'hosts',
                            'alias'      => 'Host',
                            'type'       => 'LEFT OUTER',
                            'conditions' => [
                                [
                                    'AND' => [
                                        'Host.id = Mapitem.object_id',
                                        'Mapitem.type' => 'host',
                                    ],
                                ],
                            ],
                        ],
                        [
                            'table'      => 'services',
                            'alias'      => 'Service',
                            'type'       => 'LEFT OUTER',
                            'conditions' => [
                                [
                                    'AND' => [
                                        'Service.id = Mapitem.object_id',
                                        'Mapitem.type' => 'service',
                                    ],
                                ],
                            ],
                        ],
                        [
                            'table'      => 'hostgroups',
                            'alias'      => 'Hostgroup',
                            'type'       => 'LEFT OUTER',
                            'conditions' => [
                                [
                                    'AND' => [
                                        'Hostgroup.id = Mapitem.object_id',
                                        'Mapitem.type' => 'hostgroup',
                                    ],
                                ],
                            ],
                        ],
                        [
                            'table'      => 'servicegroups',
                            'alias'      => 'Servicegroup',
                            'type'       => 'LEFT OUTER',
                            'conditions' => [
                                [
                                    'AND' => [
                                        'Servicegroup.id = Mapitem.object_id',
                                        'Mapitem.type' => 'servicegroup',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'conditions' => [
                        'Mapitem.map_id' => $mapId,
                    ],
                    'fields'     => [
                        'Mapitem.*', 'Host.*', 'Hostgroup.*', 'Service.*', 'Servicegroup.*', 'Map.*',
                    ],
                ]);

                $map_lines = $this->_controller->Mapline->find('all', [
                    'joins'      => [
                        [
                            'table'      => 'hosts',
                            'alias'      => 'Host',
                            'type'       => 'LEFT OUTER',
                            'conditions' => [
                                [
                                    'AND' => [
                                        'Host.id = Mapline.object_id',
                                        'Mapline.type' => 'host',
                                    ],
                                ],
                            ],
                        ],
                        [
                            'table'      => 'services',
                            'alias'      => 'Service',
                            'type'       => 'LEFT OUTER',
                            'conditions' => [
                                [
                                    'AND' => [
                                        'Service.id = Mapline.object_id',
                                        'Mapline.type' => 'service',
                                    ],
                                ],
                            ],
                        ],
                        [
                            'table'      => 'hostgroups',
                            'alias'      => 'Hostgroup',
                            'type'       => 'LEFT OUTER',
                            'conditions' => [
                                [
                                    'AND' => [
                                        'Hostgroup.id = Mapline.object_id',
                                        'Mapline.type' => 'hostgroup',
                                    ],
                                ],
                            ],
                        ],
                        [
                            'table'      => 'servicegroups',
                            'alias'      => 'Servicegroup',
                            'type'       => 'LEFT OUTER',
                            'conditions' => [
                                [
                                    'AND' => [
                                        'Servicegroup.id = Mapline.object_id',
                                        'Mapline.type' => 'servicegroup',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'conditions' => [
                        'Mapline.map_id' => $mapId,
                    ],
                    'fields'     => [
                        'Mapline.*', 'Host.*', 'Hostgroup.*', 'Service.*', 'Servicegroup.*',
                    ],
                ]);

                $map_gadgets = $this->_controller->Mapgadget->find('all', [
                    'joins'      => [
                        [
                            'table'      => 'hosts',
                            'alias'      => 'Host',
                            'type'       => 'LEFT OUTER',
                            'conditions' => [
                                [
                                    'AND' => [
                                        'Host.id = Mapgadget.object_id',
                                        'Mapgadget.type' => 'host',
                                    ],
                                ],
                            ],
                        ],
                        [
                            'table'      => 'services',
                            'alias'      => 'Service',
                            'type'       => 'LEFT OUTER',
                            'conditions' => [
                                [
                                    'AND' => [
                                        'Service.id = Mapgadget.object_id',
                                        'Mapgadget.type' => 'service',
                                    ],
                                ],
                            ],
                        ],
                        [
                            'table'      => 'hostgroups',
                            'alias'      => 'Hostgroup',
                            'type'       => 'LEFT OUTER',
                            'conditions' => [
                                [
                                    'AND' => [
                                        'Hostgroup.id = Mapgadget.object_id',
                                        'Mapgadget.type' => 'hostgroup',
                                    ],
                                ],
                            ],
                        ],
                        [
                            'table'      => 'servicegroups',
                            'alias'      => 'Servicegroup',
                            'type'       => 'LEFT OUTER',
                            'conditions' => [
                                [
                                    'AND' => [
                                        'Servicegroup.id = Mapgadget.object_id',
                                        'Mapgadget.type' => 'servicegroup',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'conditions' => [
                        'Mapgadget.map_id' => $mapId,
                    ],
                    'fields'     => [
                        'Mapgadget.*', 'Host.*', 'Hostgroup.*', 'Service.*', 'Servicegroup.*',
                    ],
                ]);
                //debug($map_gadgets);
                $map_texts = $this->_controller->Maptext->find('all', [
                    'conditions' => [
                        'map_id' => $mapId,
                    ],
                ]);

                //keep the null values out
                $map_items = Hash::filter($map_items);
                $map_lines = Hash::filter($map_lines);
                $map_gadgets = Hash::filter($map_gadgets);

                $hostUuids = Hash::extract($map_items, '{n}.Host.uuid');
                $serviceUuids = Hash::extract($map_items, '{n}.Service.uuid');
                $hostgroupUuids = Hash::extract($map_items, '{n}.Hostgroup.uuid');
                $servicegroupUuids = Hash::extract($map_items, '{n}.Servicegroup.uuid');

                $hostLineUuids = Hash::extract($map_lines, '{n}.Host.uuid');
                $serviceLineUuids = Hash::extract($map_lines, '{n}.Service.uuid');
                $hostgroupLineUuids = Hash::extract($map_lines, '{n}.Hostgroup.uuid');
                $servicegroupLineUuids = Hash::extract($map_lines, '{n}.Servicegroup.uuid');

                $hostGadgetUuids = Hash::extract($map_gadgets, '{n}.Host.uuid');
                $serviceGadgetUuids = Hash::extract($map_gadgets, '{n}.Service.uuid');
                $hostgroupGadgetUuids = Hash::extract($map_gadgets, '{n}.Hostgroup.uuid');
                $servicegroupGadgetUuids = Hash::extract($map_gadgets, '{n}.Servicegroup.uuid');

                //merge the LineUuids and the item uuids
                $hostUuids = Hash::merge($hostUuids, $hostLineUuids, $hostGadgetUuids);
                $serviceUuids = Hash::merge($serviceUuids, $serviceLineUuids, $serviceGadgetUuids);
                $hostgroupUuids = Hash::merge($hostgroupUuids, $hostgroupLineUuids, $hostgroupGadgetUuids);
                $servicegroupUuids = Hash::merge($servicegroupUuids, $servicegroupLineUuids, $servicegroupGadgetUuids);

                //$this->_controller->__unbindAssociations('Objects');
                //just the Hosts
                $hoststatus = null;
                if (count($hostUuids) > 0) {
                    $hoststatus = $this->_controller->Objects->find('all', [
                        'conditions' => [
                            'name1'         => $hostUuids,
                            'objecttype_id' => 1,
                        ],
                        'fields'     => [
                            'Objects.*',
                            'Hoststatus.*',
                        ],
                        'joins'      => [
                            [
                                'table'      => 'nagios_hoststatus',
                                'type'       => 'LEFT OUTER',
                                'alias'      => 'Hoststatus',
                                'conditions' => 'Objects.object_id = Hoststatus.host_object_id',
                            ],
                        ],
                    ]);

                    $currentHostUuids = Hash::extract($hoststatus, '{n}.Objects.name1');

                    foreach ($currentHostUuids as $key => $currentHostUuid) {
                        $hostServiceStatus = $this->_controller->Objects->find('all', [
                            'recursive'  => -1,
                            'conditions' => [
                                'name1'         => $currentHostUuid,
                                'objecttype_id' => 2,
                            ],
                            'fields'     => [
                                'Objects.*',
                                'Servicetemplate.name',
                                'Servicetemplate.description',
                                'Servicestatus.*',
                                'Service.name',
                                'Service.description',
                            ],
                            'joins'      => [
                                [
                                    'table'      => 'services',
                                    'alias'      => 'Service',
                                    'conditions' => [
                                        'Objects.name2 = Service.uuid',
                                    ],
                                ],
                                [
                                    'table'      => 'servicetemplates',
                                    'type'       => 'INNER',
                                    'alias'      => 'Servicetemplate',
                                    'conditions' => [
                                        'Servicetemplate.id = Service.servicetemplate_id',
                                    ],
                                ],
                                [
                                    'table'      => 'nagios_servicestatus',
                                    'type'       => 'LEFT OUTER',
                                    'alias'      => 'Servicestatus',
                                    'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                                ],
                            ],
                        ]);
                        $hoststatus[$key]['Hoststatus']['Servicestatus'] = $hostServiceStatus;
                    }
                    $mapstatus['hoststatus'] = $hoststatus;
                }

                //just the hostgroups
                $hostgroup = null;
                if (count($hostgroupUuids) > 0) {
                    foreach ($hostgroupUuids as $hostgroupUuid) {
                        $hostgroup = $this->_controller->Hostgroup->find('all', [
                            'recursive'  => -1,
                            'conditions' => [
                                'uuid' => $hostgroupUuid,
                            ],
                            'contain'    => [
                                'Container' => [
                                    'fields' => [
                                        'Container.name',
                                    ],
                                ],
                                'Host'      => [
                                    'fields' => [
                                        'Host.name',
                                        'Host.uuid',
                                        'Host.description',
                                        'Host.address',
                                    ],
                                ],
                            ],
                            'fields'     => [
                                'Hostgroup.*',
                            ],
                        ]);
                        $currentHostgroupHostUuids = Hash::extract($hostgroup, '{n}.Host.{n}.uuid');
                        $hostgroupHoststatus = [];
                        $hostgroupServicestatus = [];

                        foreach ($currentHostgroupHostUuids as $key => $currentHostgroupHostUuid) {
                            $hostgroupHoststatus = $this->_controller->Objects->find('all', [
                                'conditions' => [
                                    'name1'         => $currentHostgroupHostUuid,
                                    'objecttype_id' => 1,
                                ],
                                'fields'     => [
                                    'Objects.*',
                                    'Hoststatus.*',
                                ],
                                'joins'      => [
                                    [
                                        'table'      => 'nagios_hoststatus',
                                        'type'       => 'LEFT OUTER',
                                        'alias'      => 'Hoststatus',
                                        'conditions' => 'Objects.object_id = Hoststatus.host_object_id',
                                    ],
                                ],
                            ]);

                            $hostgroupServicestatus = $this->_controller->Objects->find('all', [
                                'recursive'  => -1,
                                'conditions' => [
                                    'name1'         => $currentHostgroupHostUuid,
                                    'objecttype_id' => 2,
                                ],
                                'fields'     => [
                                    'Objects.*',
                                    'Servicetemplate.name',
                                    'Servicetemplate.description',
                                    'Servicestatus.*',
                                    'Service.name',
                                    'Service.description',
                                ],
                                'joins'      => [
                                    [
                                        'table'      => 'services',
                                        'alias'      => 'Service',
                                        'conditions' => [
                                            'Objects.name2 = Service.uuid',
                                        ],
                                    ],
                                    [
                                        'table'      => 'servicetemplates',
                                        'type'       => 'INNER',
                                        'alias'      => 'Servicetemplate',
                                        'conditions' => [
                                            'Servicetemplate.id = Service.servicetemplate_id',
                                        ],
                                    ],
                                    [
                                        'table'      => 'nagios_servicestatus',
                                        'type'       => 'LEFT OUTER',
                                        'alias'      => 'Servicestatus',
                                        'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                                    ],
                                ],
                            ]);
                            $hostgroup[0]['Host'][$key]['Hoststatus'] = $hostgroupHoststatus;
                            $hostgroup[0]['Host'][$key]['Servicestatus'] = $hostgroupServicestatus;
                        }
                    }
                    $mapstatus['hostgroupstatus'] = $hostgroup;
                }

                //just the Servicegroups
                $servicegroup = null;
                if (count($servicegroupUuids) > 0) {
                    foreach ($servicegroupUuids as $servicegroupUuid) {
                        $servicegroup = $this->_controller->Servicegroup->find('all', [
                            'recursive'  => -1,
                            'conditions' => [
                                'uuid' => $servicegroupUuid,
                            ],
                            'contain'    => [
                                'Container' => [
                                    'fields' => [
                                        'Container.name',
                                    ],
                                ],
                                'Service'   => [
                                    'fields' => [
                                        'Service.*',
                                    ],
                                ],
                            ],
                        ]);
                    }

                    $currentServicegroupServiceUuids = Hash::extract($servicegroup, '{n}.Service.{n}.uuid');

                    foreach ($currentServicegroupServiceUuids as $key => $currentServicegroupServiceUuid) {
                        $servicestatus = $this->_controller->Objects->find('all', [
                            'recursive'  => -1,
                            'conditions' => [
                                'name2'         => $currentServicegroupServiceUuid,
                                'objecttype_id' => 2,
                            ],
                            'fields'     => [
                                'Objects.*',
                                'Servicetemplate.name',
                                'Servicetemplate.description',
                                'Servicestatus.*',
                                'Service.name',
                                'Service.description',
                            ],
                            'joins'      => [
                                [
                                    'table'      => 'services',
                                    'alias'      => 'Service',
                                    'conditions' => [
                                        'Objects.name2 = Service.uuid',
                                    ],
                                ],
                                [
                                    'table'      => 'servicetemplates',
                                    'type'       => 'INNER',
                                    'alias'      => 'Servicetemplate',
                                    'conditions' => [
                                        'Servicetemplate.id = Service.servicetemplate_id',
                                    ],
                                ],
                                [
                                    'table'      => 'nagios_servicestatus',
                                    'type'       => 'LEFT OUTER',
                                    'alias'      => 'Servicestatus',
                                    'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                                ],
                            ],
                        ]);
                        $servicegroup[0]['Servicegroup'][$key]['Servicestatus'] = $servicestatus;
                    }
                    $mapstatus['servicegroupstatus'] = $servicegroup;
                }

                //just the Services
                $servicestatus = '';
                if (count($serviceUuids) > 0) {
                    //$this->loadModel('Service');
                    $servicestatus = $this->_controller->Objects->find('all', [
                        'recursive'  => -1,
                        'conditions' => [
                            'name2'         => $serviceUuids,
                            'objecttype_id' => 2,
                        ],
                        'fields'     => [
                            'Objects.*',
                            'Servicetemplate.name',
                            'Servicetemplate.description',
                            'Servicestatus.*',
                            'Service.name',
                            'Service.description',
                        ],
                        'joins'      => [
                            [
                                'table'      => 'services',
                                'alias'      => 'Service',
                                'conditions' => [
                                    'Objects.name2 = Service.uuid',
                                ],
                            ],
                            [
                                'table'      => 'servicetemplates',
                                'type'       => 'INNER',
                                'alias'      => 'Servicetemplate',
                                'conditions' => [
                                    'Servicetemplate.id = Service.servicetemplate_id',
                                ],
                            ],
                            [
                                'table'      => 'nagios_servicestatus',
                                'type'       => 'LEFT OUTER',
                                'alias'      => 'Servicestatus',
                                'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                            ],
                        ],
                    ]);
                    $mapstatus['servicestatus'] = $servicestatus;
                }

                //insert the Host UUID into the servicegadgets (eg. for RRDs)
                foreach ($serviceGadgetUuids as $key => $serviceGadgetUuid) {
                    $map_gadgets[$key]['Service']['host_uuid'] = $this->hostUuidFromServiceUuid($serviceGadgetUuid)[0];
                }

                $backgroundThumbs = $this->_controller->Background->findBackgrounds();
                $iconSets = $this->_controller->Background->findIconsets();
                $icons = $this->_controller->Background->findIcons();

                if (!empty($map_lines)) {
                    $this->_controller->Frontend->setJson('map_lines', Hash::Extract($map_lines, '{n}.Mapline'));
                }

                if (!empty($map_gadgets)) {
                    $this->_controller->Frontend->setJson('map_gadgets', Hash::Extract($map_gadgets, '{n}.Mapgadget'));
                }

                //debug($servicestatus);
                foreach ($map_gadgets as $key => $val) {
                    //debug($val);
                    foreach ($servicestatus as $key2 => $val2) {
                        if ($val[ucfirst($val['Mapgadget']['type'])]['uuid'] === $val2['Objects']['name2']) {
                            //debug($val2['Servicestatus']);
                            $state = [
                                'state'       => $val2['Servicestatus']['current_state'],
                                'is_flapping' => $val2['Servicestatus']['is_flapping'],
                                'perfdata'    => $val2['Servicestatus']['perfdata'],
                            ];
                        }
                    }
                    switch ($val['Mapgadget']['type']) {
                        case 'host':
                            $state = $this->_controller->hoststatus($state);
                            break;
                        case 'service':
                            $state = $this->_controller->servicestatus($state);
                            break;
                        case 'servicegroup':
                            $state = $this->_controller->servicegroupstatus($state);
                            break;
                        case 'hostgroup':
                            $state = $this->_controller->hostgroupstatus($state);
                            break;
                    }
                    //debug($state);
                    if ($val['Mapgadget']['gadget'] === 'RRDGraph') {

                        $Rrd = ClassRegistry::init('Rrd');
                        $rrd_path = Configure::read('rrd.path');
                        $rrd_structure_datasources = $Rrd->getPerfDataStructure($rrd_path.$val['Service']['host_uuid'].DS.$val['Service']['uuid'].'.xml');
                        $rrdBackgroundColor = 'BACK#FFFFFFFF';
                        if (isset($val['Mapgadget']['transparent_background']) && $val['Mapgadget']['transparent_background'] == true) {
                            $rrdBackgroundColor = 'BACK#FFFFFF00';
                        }
                        $options = [
                            'start'        => strtotime('1 hour ago'),
                            'end'          => time(),
                            'path'         => $rrd_path,
                            'host_uuid'    => $val['Service']['host_uuid'],
                            'service_uuid' => $val['Service']['uuid'],
                            'width'        => 300,
                            'color'        => [
                                $rrdBackgroundColor,
                                'CANVAS#FFFFFF99',
                                'ARROW#000000FF',
                                'SHADEA#FFFFFF00',
                                'SHADEB#FFFFFF00',
                            ],
                        ];
                        $RRDGraphLink = $Rrd->createRrdGraph($rrd_structure_datasources[0], $options, [], true)['webPath'];

                        $map_gadgets[$key]['Mapgadget']['rrdGraphLink'] = $RRDGraphLink;
                    }
                    $map_gadgets[$key]['Mapgadget']['state'] = $state;
                    $map_gadgets[$key]['Mapgadget']['perfdata'] = $this->_controller->parsePerfData($state['perfdata']);
                }


                $widgetConfiguration = [
                    'map'              => $map,
                    'map_items'        => $map_items,
                    'mapstatus'        => $mapstatus,
                    'map_lines'        => $map_lines,
                    'map_gadgets'      => $map_gadgets,
                    'map_texts'        => $map_texts,
                    'backgroundThumbs' => $backgroundThumbs,
                    'iconSets'         => $iconSets,
                    'hoststatus'       => $hoststatus,
                    'servicestatus'    => $servicestatus,
                    'hostgroup'        => $hostgroup,
                    'servicegroup'     => $servicegroup,
                    'isFullscreen'     => $isFullscreen,
                    'icons'            => $icons,
                ];
                //debug($widgetConfiguration);
                $this->widgetSettingsCache[$widget['Widget']['type_id']][$widget['Widget']['id']] = $widgetConfiguration;
                $this->_controller->Frontend->setJson('allWidgetParameters', $this->widgetSettingsCache);
            }
        } else {

            $MapModule = false;
            $this->_controller->set(compact('MapModule'));
            $widgetConfiguration = [
                'missingModule' => true,
            ];

            $this->widgetSettingsCache[$widget['Widget']['type_id']][$widget['Widget']['id']] = $widgetConfiguration;
            $this->_controller->Frontend->setJson('allWidgetParameters', $this->widgetSettingsCache);

        }
    }

    public function hostUuidFromServiceUuid($serviceUuid = null)
    {
        $hostUuid = $this->_controller->Objects->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'name2' => $serviceUuid,
            ],
            'fields'     => [
                'Objects.name1',
            ],
        ]);
        $hostUuid = Hash::extract($hostUuid, 'Objects.name1');

        return $hostUuid;
    }

    public function browser($widget)
    {
        //debug($widget);
        if (array_key_exists('WidgetBrowser', $widget)) {
            $widgetConfiguration = [
                'widget_id' => $widget['WidgetBrowser']['widget_id'],
                'url'       => $widget['WidgetBrowser']['url'],
            ];
            //debug($widgetConfiguration);
            $this->widgetSettingsCache[$widget['Widget']['type_id']][$widget['Widget']['id']] = $widgetConfiguration;
            //debug($this->widgetSettingsCache);
            $this->_controller->Frontend->setJson('allWidgetParameters', $this->widgetSettingsCache);
        }
    }

    public function notice($widget)
    {
        if (array_key_exists('WidgetNotice', $widget)) {
            require_once APP.'Vendor'.DS.'parsedown'.DS.'Parsedown.php';
            require_once APP.'Vendor'.DS.'parsedown'.DS.'ParsedownExtra.php';

            $parsedown = new ParsedownExtra();
            $parsedMarkdown = $parsedown->text($widget['WidgetNotice']['note']);

            $widgetConfiguration = [
                'widget_id'    => $widget['WidgetNotice']['widget_id'],
                'note'         => htmlspecialchars($widget['WidgetNotice']['note']),
                'noteMarkdown' => $parsedMarkdown,
            ];

            $this->widgetSettingsCache[$widget['Widget']['type_id']][$widget['Widget']['id']] = $widgetConfiguration;
            $this->_controller->Frontend->setJson('allWidgetParameters', $this->widgetSettingsCache);
        }
    }

    public function graphgenerator($widget)
    {
        //debug($widget);
        if (empty($this->serviceIdsForSelect)) {
            //$this->serviceIdsForSelect = $this->_controller->Host->servicesByContainerIds($this->_controller->MY_RIGHTS,'list');
            $this->serviceIdsForSelect = $this->_controller->getAllServiceWithCurrentState($this->_controller->MY_RIGHTS);
        }
        //debug($this->serviceIdsForSelect);
        $this->_controller->set(['service_ids_for_select' => $this->serviceIdsForSelect]);

        if ($widget['Widget']['service_id']) {
            $service = $this->_controller->Objects->find('first', [
                'recursive'  => -1,
                'fields'     => [
                    'Service.uuid',
                    'Service.name',
                    'Service.check_interval',
                    'Servicetemplate.name',
                    'Servicetemplate.check_interval',
                    'Host.name',
                    'Host.uuid',
                    'Servicestatus.perfdata',
                ],
                'conditions' => [
                    'Service.id' => $widget['Widget']['service_id'],
                ],
                'joins'      => [
                    [
                        'table'      => 'services',
                        'alias'      => 'Service',
                        'conditions' => [
                            'Objects.name2 = Service.uuid',
                        ],
                    ],
                    [
                        'table'      => 'hosts',
                        'alias'      => 'Host',
                        'conditions' => [
                            'Objects.name1 = Host.uuid',
                        ],
                    ], [
                        'table'      => 'servicetemplates',
                        'type'       => 'INNER',
                        'alias'      => 'Servicetemplate',
                        'conditions' => [
                            'Servicetemplate.id = Service.servicetemplate_id',
                        ],
                    ], [
                        'table'      => 'nagios_servicestatus',
                        'type'       => 'LEFT OUTER',
                        'alias'      => 'Servicestatus',
                        'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                    ],
                ],
            ]);
            if (empty($service)) {
                $widgetConfiguration = [
                    'serviceGone' => 'Service not found! Maybe it has been deleted.',
                ];
            } else {
                $service['Servicestatus']['perfdata'] = $this->_controller->Rrd->parsePerfData($service['Servicestatus']['perfdata']);

                if ($service['Service']['check_interval']) {
                    $checkInterval = $service['Service']['check_interval'];
                } else {
                    $checkInterval = $service['Servicetemplate']['check_interval'];
                }
                $widgetConfiguration = [
                    'service_id'     => $widget['Widget']['service_id'],
                    'time'           => $widget['WidgetGraphgenerator']['time'],
                    'data_sources'   => $widget['WidgetGraphgenerator']['data_sources'],
                    'check_interval' => $checkInterval,
                    'alLData'        => $service,
                ];
            }
            $this->widgetSettingsCache[$widget['Widget']['type_id']][$widget['Widget']['id']] = $widgetConfiguration;
            $this->_controller->Frontend->setJson('allWidgetParameters', $this->widgetSettingsCache);
        }
    }

}