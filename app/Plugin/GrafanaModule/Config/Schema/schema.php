<?php

class GrafanaModuleSchema extends CakeSchema {

    public function before($event = []) {
        $db = ConnectionManager::getDataSource($this->connection);
        $db->cacheSources = false;

        $CronjobModel = ClassRegistry::init('Cronjob');

        //Check if cronjob exists
        $result = $CronjobModel->find('first', [
            'conditions' => [
                'Cronjob.plugin' => 'GrafanaModule',
                'Cronjob.task'   => 'GrafanaDashboard',
            ],
        ]);
        if (empty($result)) {
            $data = [
                'Cronjob' => [
                    'task'     => 'GrafanaDashboard',
                    'plugin'   => 'GrafanaModule',
                    'interval' => '720',
                ],
            ];
            $CronjobModel->create();
            $CronjobModel->save($data);
        }

        return true;
    }

    public function after($event = []) {
    }

    public $grafana_configurations = [
        'id'                     => ['type' => 'integer', 'null' => false, 'key' => 'primary'],
        'api_url'                => ['type' => 'string', 'null' => false, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'api_key'                => ['type' => 'string', 'null' => false, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'graphite_prefix'        => ['type' => 'string', 'null' => false, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'use_https'              => ['type' => 'integer', 'null' => false, 'length' => 1],
        'use_proxy'              => ['type' => 'integer', 'null' => false, 'length' => 1],
        'ignore_ssl_certificate' => ['type' => 'integer', 'null' => false, 'length' => 1],
        'dashboard_style'        => ['type' => 'string', 'null' => false, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'                => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'               => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                => [
            'PRIMARY' => [
                'column' => 'id',
                'unique' => 1
            ],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hostgroups_to_grafanaconfigurations = [
        'id'               => ['type' => 'integer', 'null' => false, 'key' => 'primary'],
        'configuration_id' => ['type' => 'integer', 'null' => false, 'key' => 'index'],
        'hostgroup_id'     => ['type' => 'integer', 'null' => false, 'key' => 'index'],
        'excluded'         => ['type' => 'integer', 'null' => true, 'length' => 1],
        'indexes'          => [
            'PRIMARY'                    => [
                'column' => 'id',
                'unique' => 1
            ],
            'configuration_to_hostgroup' => [
                'column' => [
                    'configuration_id',
                    'hostgroup_id'
                ],
                'unique' => 1
            ],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $grafana_dashboards = [
        'id'               => ['type' => 'integer', 'null' => false, 'key' => 'primary'],
        'configuration_id' => ['type' => 'integer', 'null' => false, 'key' => 'index'],
        'host_id'          => ['type' => 'integer', 'null' => false, 'key' => 'index'],
        'host_uuid'        => ['type' => 'string', 'null' => false, 'length' => 200, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'          => [
            'PRIMARY' => [
                'column' => 'id',
                'unique' => 1
            ],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $grafana_userdashboards = [
        'id'               => ['type' => 'integer', 'null' => false, 'key' => 'primary'],
        'configuration_id' => ['type' => 'integer', 'null' => false, 'key' => 'index'],
        'name'             => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'],
        'indexes'          => [
            'PRIMARY' => [
                'column' => 'id',
                'unique' => 1
            ],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'],
    ];

    public $grafana_userdashboards_data = [
        'id'               => ['type' => 'integer', 'null' => false, 'key' => 'primary'],
        'userdashboard_id' => ['type' => 'integer', 'null' => false, 'key' => 'index'],
        'row'              => ['type' => 'integer', 'null' => false, 'key' => 'index'],
        'panel'            => ['type' => 'integer', 'null' => false, 'key' => 'index'],
        'metric'           => ['type' => 'integer', 'null' => false, 'key' => 'index'],
        'host_id'          => ['type' => 'integer', 'null' => false, 'key' => 'index'],
        'service_id'       => ['type' => 'integer', 'null' => false, 'key' => 'index'],
        'metric_value'     => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'],
        'indexes'          => [
            'PRIMARY' => [
                'column' => 'id',
                'unique' => 1
            ],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'],
    ];
}
