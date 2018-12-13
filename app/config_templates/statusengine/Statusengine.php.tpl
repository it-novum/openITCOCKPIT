<?php

{{STATIC_FILE_HEADER}}

/* Main configuration file of Statusengine
 * This is a PHP file, please check for syntax errors!
 * Example command to check for any syntax erros:
 *   php --syntax-check /opt/statusengine/cakephp/app/Config/Statusengine.php
 */

$config = [
    //Logfile moved to SysLog!

    //max age of service status records in gearman queue
    'servicestatus_freshness'     => 300,

    //address of gearman-job-server
    'server'                      => '127.0.0.1',

    //port of gearman-job-server
    'port'                        => 4730,

    //path to your naemon.cfg or nagios.cfg
    'coreconfig'                  => '/etc/openitcockpit/nagios.cfg',

    //Number of your monitoring instance (just an integer value)
    'instance_id'                 => 1,

    //The number of the config type you would to dump to the database (just an integer value)
    'config_type'                 => 1,

    //Define the way of empty the data tables (DELETE FROM or TRUNCATE TABLE)
    //If innodb_file_per_table is enabled, delete is may be faster than truncate
    'empty_method'                => 'TRUNCATE',

    //If you want, Statusengine's servicestatus workers are able to
    //process performacne data for you and save them to RRD files
    //so you don't need to install any additional software to
    //get the job done.
    'process_perfdata'            => true,

    //Checkout Config/Perfdata.php for RRDTool configuration
    //Checkout Config/Graphite.php for Graphite configuration
    //
    // Examples:
    //  1. RRD only: ['Rrd']
    //  2. Graphite only: ['Graphite']
    //  3. RRD and Graphite: ['Rrd', 'Graphite']
    'perfdata_storage'            => ['Graphite'],

    //Use bulk queries for host and servicestatus
    //And other tables, many thanks to dhoffend
    'use_bulk_queries_for_status' => true,

    //Records per bulk operations
    'bulk_query_limit'            => {{number_of_bulk_records}},

    //Time between forced bulk flushes
    'bulk_query_time'             => {{max_bulk_delay}},

    //Workers Statusengine will fork in worker mode
    //Check: https://statusengine.org/documentation.php#scaleout-statusengine
    'workers'                     => [
{% for servicestatusWorker in se2_number_servicestatus_worker %}
        [
            {{servicestatusWorker|raw}}
        ],
{% endfor %}
        [
            'queues' => [
                'statusngin_hoststatus'   => 'processHoststatus',
                'statusngin_statechanges' => 'processStatechanges'
            ]
        ],
{% for servicecheckWorker in se2_number_servicecheck_worker %}
        [
            {{servicecheckWorker|raw}}
        ],
{% endfor %}
        [
            'queues' => [
                'statusngin_hostchecks' => 'processHostchecks',
                'statusngin_logentries' => 'processLogentries'
            ]
        ],
{% for hoststatusWorker in se2_number_hoststatus_worker %}
        [
            {{hoststatusWorker|raw}}
        ],
{% endfor %}
{% for hostcheckWorker in se2_number_hostcheck_worker %}
        [
            {{hostcheckWorker|raw}}
        ],
{% endfor %}
        [
            'queues' => [
                'statusngin_contactstatus'    => 'processContactstatus',
                'statusngin_acknowledgements' => 'processAcknowledgements',
                'statusngin_comments'         => 'processComments',
                'statusngin_flappings'        => 'processFlappings',
                'statusngin_downtimes'        => 'processDowntimes',
                'statusngin_externalcommands' => 'processExternalcommands',
                'statusngin_systemcommands'   => 'processSystemcommands',
                'statusngin_eventhandler'     => 'processEventhandler'
            ]
        ],
        [
            'queues' => [
                'statusngin_contactnotificationmethod' => 'processContactnotificationmethod',
                'statusngin_notifications'             => 'processNotifications',
                'statusngin_contactnotificationdata'   => 'processContactnotificationdata'
            ]
        ]
    ],


    //Memcached settings
    'memcached'                   => [
        //use memcached or not
        'use_memcached'   => false,

        //1 = save only in memcached, 0 = save in db and memcached
        'processing_type' => 0,

        //clear all memcacehd entries on start up
        'drop_on_start'   => false,

        //address of memcached server
        'server'          => '127.0.0.1',

        //port of memcached server
        'port'            => 11211
    ]
];
