<?php

{{STATIC_FILE_HEADER}}

/* Main configuration file of Statusengine's ModPerfdata Graphite extension
 * This is a PHP file, please check for syntax errors!
 * Example command to check for any syntax erros:
 *   php --syntax-check /opt/statusengine/cakephp/app/Config/Graphite.php
 */
$config = [
    'graphite' => [

        //Statusengine will create a TCP connection to your graphite server
        'host'                     => '{{graphite_address}}',
        'port'                     => {{graphite_port}},

        /* Every character that will not match the RegEx will be replaced with _
        * in the key name for Graphite
        * Type: String
        * Default: /[^a-zA-Z^0-9\-\.]/
        */
        'replace_characters'       => '/[^a-zA-Z^0-9\-\.]/',

        //prefix for every key
        'prefix'                   => '{{graphite_prefix}}',

        //if false, statusengine will use the host name as key
        //if true, statusengine will use the display_name as key
        'use_host_display_name'    => false,

        //if false, statusengine will use the service description as key
        //if true, statusengine will use the display_name as key
        'use_service_display_name' => false,

        //full example for key: statusengine.localhost.Ping.rta
    ]
];
