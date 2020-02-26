<?php
$config = [
    'public_actions' => [
        'login'            => '*',
        'dev'              => '*',
        'pages'            => '*',
        'packetmanager'    => ['getPackets'],
        'NagiosModule.cmd' => ['submit', 'ack'],
    ],
    'auth_actions'   => [
        'profile'               => [
            '*' => '*',
        ],
        'Admin.hosts'           => [
            '*' => '*',
        ],
        'Admin.dashboard'       => [
            '*' => [
                Types::ROLE_ADMIN,
                Types::ROLE_EMPLOYEE,
            ],
        ],
        'users'                 => [
            '*'    => [
                Types::ROLE_ADMIN,
            ],
            'view' => [
                Types::ROLE_EMPLOYEE,
            ],
        ],
        'usergroups'            => [
            '*'    => [
                Types::ROLE_ADMIN,
            ],
            'view' => [
                Types::ROLE_EMPLOYEE,
            ],
        ],
        'proxy'                 => [
            '*' => '*',
        ],
        'packetmanager'         => [
            '*' => '*',
        ],
        'changelogs'            => [
            '*' => '*',
        ],
        'rrds'                  => [
            '*' => '*',
        ],
        'exports'               => [
            '*' => '*',
        ],/*
        'reports' => [
            '*' => '*'
        ],*/
        'administrators'        => [
            '*' => '*',
        ],
        'containers'            => [
            '*' => '*',
        ],
        'hosts'                 => [
            '*' => '*',
        ],
        'services'              => [
            '*' => '*',
        ],
        'browsers'              => [
            '*' => '*',
        ],
        'tenants'               => [
            '*' => '*',
        ],
        'contacts'              => [
            '*' => '*',
        ],
        'nodes'                 => [
            '*' => '*',
        ],
        'timeperiods'           => [
            '*' => '*',
        ],
        'commands'              => [
            '*' => '*',
        ],
        'hosttemplates'         => [
            '*' => '*',
        ],
        'contactgroups'         => [
            '*' => '*',
        ],
        'calendars'             => [
            '*' => '*',
        ],
        'servicetemplates'      => [
            '*' => '*',
        ],
        'locations'             => [
            '*' => '*',
        ],
        /*'devicegroups' => [
            '*' => '*'
        ],*/
        'hostgroups'            => [
            '*' => '*',
        ],
        'hostescalations'       => [
            '*' => '*',
        ],
        'macros'                => [
            '*' => '*',
        ],
        'documentations'        => [
            '*' => '*',
        ],
        'graphgenerators'       => [
            '*' => '*',
        ],
        'graph_collections'     => [
            '*' => '*',
        ],
        'downtimes'             => [
            '*' => '*',
        ],
        'logentries'            => [
            '*' => '*',
        ],
        'nagiostats'            => [
            '*' => '*',
        ],
        'notifications'         => [
            '*' => '*',
        ],
        'systemfailures'        => [
            '*' => '*',
        ],
        'servicegroups'         => [
            '*' => '*',
        ],
        'servicechecks'         => [
            '*' => '*',
        ],
        'serviceescalations'    => [
            '*' => '*',
        ],
        'statehistories'        => [
            '*' => '*',
        ],
        'hostchecks'            => [
            '*' => '*',
        ],
        'acknowledgements'      => [
            '*' => '*',
        ],
        'systemsettings'        => [
            '*' => '*',
        ],
        'hostdependencies'      => [
            '*' => '*',
        ],
        'servicedependencies'   => [
            '*' => '*',
        ],
        'systemdowntimes'       => [
            '*' => '*',
        ],
        'forward'               => [
            '*' => '*',
        ],
        'deleted_hosts'         => [
            '*' => '*',
        ],
        'servicetemplategroups' => [
            '*' => '*',
        ],
        'registers'             => [
            '*' => '*',
        ],
        'cronjobs'              => [
            '*' => '*',
        ],
        'statusmaps'            => [
            '*' => '*',
        ],
        'search'                => [
            '*' => '*',
        ],
        'qr'                    => [
            '*' => '*',
        ],
        'instantreports'        => [
            '*' => '*',
        ],
        'downtimereports'       => [
            '*' => '*',
        ],
        'currentstatereports'   => [
            '*' => '*',
        ],
        'automaps'              => [
            '*' => '*',
        ],
        'NagiosModule.cmd'      => ['index'],
    ],
];
