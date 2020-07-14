<?php

{{STATIC_FILE_HEADER}}

/* Main configuration file of Statusengine's ModPerfdata
 * This is a PHP file, please check for syntax errors!
 * Example command to check for any syntax erros:
 *   php --syntax-check /opt/statusengine/cakephp/app/Config/Perfdata.php
 */
$config = [
    'perfdata' => [
        /* Number of worker processes, ModPerfdata will create
         * Type: Integer
         * Default: 2
         */
        'worker'             => 2,

        /* Every character that will not match the RegEx will be replaced with _
         * in the file name of .rrd and .xml files
         * Type: String
         * Default: /[^a-zA-Z^0-9\-\.]/
         */
        'replace_characters' => '/[^a-zA-Z^0-9\-\.]/',

        'RRA' => [
            'step'    => 60,
            'average' => '0.5:1:576000',
            'max'     => '0.5:1:576000',
            'min'     => '0.5:1:576000',
        ],

        'RRD' => [
            'heartbeat' => 8460,

            'DATATYPE' => [
                //rrdtool support different datatypes for each datasoruce
                //http://oss.oetiker.ch/rrdtool/doc/rrdcreate.en.html
                //You can now set a datatype for each unit.
                // value=500c  -> c is the unit
                // value=250ms -> ms is the unit

                'c'       => 'COUNTER',
                'd'       => 'DERIVE',

                //Default datatype for each unit, that is not listed
                'default' => 'GAUGE'
            ]
        ],

        'XML' => [

            /* If ModPerfdata will write pnp4nagios compatible XML files or not
             * Type: Boolean
             * Default: true
             */
            'write_xml_files' => true,

            /* If your like to reduce the I/O operations on your storage system
             * you can increse this delay value.
             * This is a interval in seconds, how often ModPerfdata will update
             * your XML files
             * Type: Integer
             * Default: 0
             */
            'delay'           => 0,
        ],

        'RRDCACHED' => [

            // apt-get install rrdcached

            /* If ModPerfdata will use rrdcached or not
             * Type: Boolean
             * Default: false
             */
            'use'  => false,

            /* Path to rrdcached's unix socket
             * Type: String
             * Default: unix:/var/run/rrdcached.sock
             */
            'sock' => 'unix:/var/run/rrdcached.sock'
        ],

        'PERFDATA' => [

            /* Path where ModPerfdata will save *.rrd and *.xml files
             * Type: String
             * Default: /opt/openitc/nagios/share/perfdata/
             */
            'dir' => '/opt/openitc/nagios/share/perfdata/'
        ],

        'MOD_GEARMAN' => [

            /* Mod_Gearman encrypt every data inside of the Gearman Job Server with AES
             * You can turn this on and of in your module.cfg of Mod_Gearman
             * Type: Boolean
             * Default: true
             */
            'encryption' => true,

            /* Shared password, that ModPerfdata is able to decrypt data
             * provided by Mod_Gearman
             * Default: should_be_changed
             */
            'key'        => 'should_be_changed'
        ],

        'GEARMAN' => [

            /* IP address of your Gearman Job Server
             * Type: String
             * Default: 127.0.0.1
             */
            'server' => '127.0.0.1',

            /* Port of your Gearman Job Server
             * Type: Integer
             * Default: 4730
             */
            'port'   => 4730
        ]
    ]
];
