<?php

class GrafanaUserdashboardData extends GrafanaModuleAppModel {

    public $useTable = 'grafana_userdashboards_data';
    public $belongsTo = [
        'GrafanaUserdashboards' => [
            'className' => 'GrafanaModule.GrafanaUserdashboard',
            'foreignKey' => 'userdashboard_id'
        ],
        'Host' => [
            'className'  => 'Host',
            'foreignKey' => 'host_id'
        ],
        'Service' => [
            'className'  => 'Service',
            'foreignKey' => 'service_id'
        ],
    ];

}