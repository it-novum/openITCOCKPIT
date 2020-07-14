<?php

{{STATIC_FILE_HEADER}}


/**
 * This is the phpNSTA configuration file.
 * Please be careful when you change something in this file!
 * You can check for any syntax errors with the following command:
 *   php --syntax-check /opt/openitc/phpNSTA/config.php
 */

/**
 * Copyright (C) 2005-2018 it-novum GmbH
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License version 2 as published
 * by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 **/
$config = [
    /**
     * phpNSTA main configuration file
     **/
    'MAIN'        => [
        /**
         * version
         * Current version of phpNSTA
         * Type: String
         * @since 1.0
         * @version 1.11.0
         **/
        'version'       => '1.11.0',

        /**
         * oitc_version
         * Version of your openITCOCKPIT installation
         * Value: 2   //for openITCOCKPIT V2
         * Value: 3   //for openITCOCKPIT V3
         * Value: 4   //for openITCOCKPIT V4
         * Type: Integer
         * @since 1.0
         **/
        'oitc_version'  => 4,

        /**
         * oitc_core
         * Path to the openITCOCKPIT V2 core libary
         * Type: String
         * @since 1.0
         **/
        'oitc_core'     => '/opt/openitc/nagios/share/main/includes/_inc_core.php',

        /**
         * oitc_database
         * Path to the openITCOCKPIT database configuration
         * Only important, if oitc_version is set to 3 or 4!
         *
         * V3 Default: /etc/openitcockpit/app/Config/database.php
         * V4 Default: /opt/openitc/frontend/config/datasource.php
         *
         * Type: String
         * @since 1.6.0
         **/
        'oitc_database' => '/opt/openitc/frontend/config/datasource.php',


        /**
         * pid_file
         * Path to phpNSTA's PID file
         * Type: String
         * @since 1.0
         * @version 1.8.0
         **/
        'pid_file'      => '/var/run/phpNSTA.pid',
    ],


    /**
     * phpNSTA API configuration
     **/
    'API'         => [
        /**
         * cmd_file
         * Path to the phpNSTAs API-Socket File
         * Type: String
         * @since 1.0
         * @version 1.8.0
         **/
        'cmd_file' => '/var/run/phpNSTA.cmd',


        /**
         * chmod
         * File permissions
         * Type: Integer
         * Default: 0664
         **/
        'chmod'    => 0664,


        /**
         * api_key
         * API authentication key
         * Type: String
         * @since 1.0
         **/
        'api_key'  => '5e1a03c44119edbd13f1f9f479da1f58',


        /**
         * interval
         * Interval in seconds at which the API is checked for new commands
         * Type: Integer
         * Default: 5
         * @since 1.0
         **/
        'interval' => 5,
    ],


    /**
     * Global logging settings
     **/
    'LOG'         => [
        /**
         * logfile
         * Path to the phpNSTA logfile
         * Type: String
         * @since 1.0
         **/
        'logfile'               => '/var/log/phpNSTA.log',


        /**
         * loglevel
         * -1 disable loging
         * 0 -> only some information about phpNSTA
         * 1 -> process fork information
         * 2 -> file operation information (production value)
         * 3 -> Nagios process information
         * 4 -> SSH tunnel information
         * 5 -> System time synchronization information
         * 6 -> Events triggerd by the SAT-Systems
         * 7 -> child process monitoring
         * 9 -> Data for Mod_Gearman
         * 10 -> Bulk Transmission
         * 11 -> Custom data transmission
         * 12 -> ALL (development value)
         * Type: Integer
         * @since 1.0
         * @version 1.10.0
         **/
        'loglevel'              => {{loglevel}},


        /**
         * logrotate
         * Whether a logrotate should run
         * Type: Boolean
         * true = On
         * false = Off
         * @since 1.0
         **/
        'logrotate'             => true,


        /**
         * logrotate
         * Time for Logrotate
         * Type: Time (HH:MM)
         * @example 00:00 => midnight
         * @example 10:00 => 10 o'Clock AM
         * @example 15:00 => 3  o'Clock PM
         * @since 1.0
         **/
        'logrotate_time'        => '00:00',


        /**
         * logrotate_path
         * Path where the zip-Archiv of the logrotae will be saved
         * Type: String
         * @since 1.0
         **/
        'logrotate_path'        => '/var/log/',


        /**
         * logrotate_date_format
         * Sufix for the *.zip File from logrotate
         * Type: PHP Date-Format (String)
         * @example Germany: 'd_m_Y_H_i' => 31_12_2013_15_30
         * @example USA: 'm_d_Y_H_i' => 12_31_2013_15_30
         * @since 1.0
         **/
        'logrotate_date_format' => '{{logrotate_date_format}}',


        /**
         * date_format
         * Describes the appearance of the date
         * Type: PHP Date-Format (String)
         * @example Germany: 'd.m.Y H:i:s' => 31.12.2013 15:30:59
         * @example USA: 'm.d.Y H:i:s' => 12.31.2013 15:30:59
         * @since 1.0
         **/
        'date_format'           => '{{date_format}}',

        /**
         * full_names
         * Display the full name and ipaddress of a satellite in the logfile
         * Type: Boolean
         * true = On
         * false = Off
         * @since 1.1
         **/
        'full_names'            => true,

        /**
         * cleanup_logfile
         * Delete old logfile in the logrotate_path
         * Type: Boolean
         * true = On
         * false = Off
         * @since 1.2
         **/
        'cleanup_logfile'       => true,

        /**
         * cleanup_fileage
         * Delete all logfiles from phpNSTA that are older than n days
         * Type: Integer
         * Value in Days
         * @example 10
         * @since 1.2
         **/
        'cleanup_fileage'       => {{cleanup_fileage}},
    ],


    /**
     * Global satellite settings
     **/
    'SAT'         => [
        /**
         * master_cmd_path
         * Path where the nagios.cmd files for the satellite systems should be created
         * Type: String
         * @since 1.0
         **/
        'master_cmd_path' => '/opt/openitc/nagios/var/rw/',


        /**
         * suffix
         * Suffix of each nagios.cmd for the satellite systems
         * Type: String
         * Default: _nagios.cmd
         * @since 1.0
         **/
        'suffix'          => '_nagios.cmd',


        /**
         * interval
         * Interval in seconds, phpNSTA will check the $SAT$_nagios.cmd files for new commands
         * Type: Integer
         * Default: 10
         * @since 1.0
         **/
        'interval'        => 10,


        /**
         * chmod
         * File permissions for satellite's nagios.cmd files
         * Type: Integer
         * Default: 0664
         * @since 1.0
         **/
        'chmod'           => 0664,
    ],


    /**
     * Global Nagios settings
     **/
    'NAGIOS'      => [
        /**
         * use_spooldir
         * Decide whether you want to use nagios spooldir or nagios.cmd
         * Type: Integer
         * 1 = use nagios spooldir (may be fail with bus error on nagios4 on >= 33000 services)
         * 2 = use nagios.cmd
         * 3 = use query handler (only in naemon and only if you run in a bus error if use_spooldir = 1 !!)
         * 4 = use Mod_Gearmans check_results queue
         * @since 1.0
         * @version 1.5.4
         **/
        'use_spooldir'                => {{use_spooldir}},


        /**
         * max_checks
         * How many results to be written to one file (Cacheing options)
         * Type: Integer > 0
         * Default: 100
         * Notice: Increase this if there's a queue building up on your satellites
         * @since 1.0
         **/
        'max_checks'                  => {{max_checks}},


        /**
         * max_wait
         * Time in seconds phpNSTA max will wait until max_checks is reached (Cacheing options)
         * Type: Integer
         * Default: 10
         * @since 1.0
         **/
        'max_wait'                    => 10,


        /**
         * cmd_file
         * Path to the nagios command file (nagios.cmd)
         * Type: String
         * Default: /opt/openitc/nagios/var/rw/nagios.cmd
         * @since 1.0
         **/
        'cmd_file'                    => '/opt/openitc/nagios/var/rw/nagios.cmd',

        /**
         * qh_file
         * Path to the nagios query handler socket (nagios.qh)
         * Type: String
         * Default: /opt/openitc/nagios/var/rw/nagios.qh
         * @since 1.5.4
         **/
        'qh_file'                     => '/opt/openitc/nagios/var/rw/nagios.qh',


        /**
         * spool_dir
         * Path to nagios checkresults folder
         * Type: String
         * @since 1.0
         **/
        'spool_dir'                   => '/opt/openitc/nagios/var/spool/checkresults/',


        /**
         * soolfile_prefix
         * Prefix for all spoolfiles phpNSTA will creat in spool_dir
         * ! Only change this value if you know what you are doing!
         * Type: String
         * Default: c
         * @since 1.0
         **/
        'soolfile_prefix'             => 'c',


        /**
         * spoolfile_random_length
         * Length if the unique filename phpNSTA will create in spool_dir
         * ! Caution: The file name including prefix can not be longer than 7 characters!
         * ! Only change this value if you know what you are doing!
         * Type: Integer
         * Default: 6
         * @since 1.0
         **/
        'spoolfile_random_length'     => 6,


        /**
         * nagios_user
         * Username of the Nagios user
         * Type: String
         * Default: nagios
         * @since 1.0
         **/
        'nagios_user'                 => 'nagios',


        /**
         * nagios_group
         * Groupname of the Nagios usergroup
         * Type: String
         * Default: nagios
         * @since 1.0
         **/
        'nagios_group'                => 'nagios',


        /**
         * apache_user
         * Username of Apache Webserver's user
         * @example www-data or wwwrun
         * Type: String
         * @since 1.0
         **/
        'apache_user'                 => 'www-data',


        /**
         * apache_group
         * Groupname of Apache Webserver's usergroup
         * @example www-data or www
         * Type: String
         * @since 1.0
         **/
        'apache_group'                => 'www-data',


        /**
         * chmod
         * File permissions for checkresult files
         * Type: Integer
         * Default: 0600
         * @since 1.0
         **/
        'chmod'                       => 0600,


        /**
         * waid_for_nagios
         * Doesn't create new files in spool_dir as long as nagios hasn't processed the old file
         * ! Caution: Affects performance significantly!
         * ! Notice: Required for proper post-processing of data in case of lost connection
         * ! Only change this value if you know what you are doing!
         * Type: Boolean
         * true = On (recommended if you want a save post processing of the graph data)
         * false = Off
         * @since 1.0
         **/
        'waid_for_nagios'             => true,

        /**
         * grep_for_nagios
         * Check with grep and pf if nagios is running
         * Type: String
         * Default V2: ps -eaf | grep "/opt/openitc/nagios/bin/nagios -d /opt/openitc/nagios/etc/nagios.cfg" |grep -v "grep"
         * Default V3: ps -eaf | grep "/opt/openitc/nagios/bin/nagios -d /etc/openitcockpit/nagios.cfg" |grep -v "grep"
         * Default V4: ps -eaf | grep "/opt/openitc/nagios/bin/nagios -d /opt/openitc/etc/nagios/nagios.cfg" |grep -v "grep"
         * Notice: Use an empty string, to disable!
         * @since 1.4
         * @version 1.8.0
         **/
        'grep_for_nagios'             => '{{grep_command|raw}}',

        /**
         * nagios4x_compatibility
         * Loads some fixes for better Nagios-4x compatibility
         * Type: Boolean
         * true = On (backwards compatible)
         * false = Off (recommended, because deprecated)
         * Default: false
         * @since 1.4.4
         * @version 1.7.0
         * @deprecated DEPRECATED Value! May be removed in future versions!
         **/
        'nagios4x_compatibility'      => false,

        /**
         * nagiostats
         * Path to the nagiostats binary
         * Type: String
         * Default: /opt/openitc/nagios/bin/nagiostats
         * @since 1.4.4
         **/
        'nagiostats'                  => '/opt/openitc/nagios/bin/nagiostats',

        /**
         * nagios_uptime
         * Time in seconds that Nagios must run before the phpNSTA starts its work
         * Type: Integer
         * Default: 180
         * @since 1.4.4
         * @deprecated DEPRECATED Value! May be removed in future versions!
         **/
        'nagios_uptime'               => 180,

        /**
         * nagios_uptime_checkinterval
         * How often the phpNSTA should check the nagios program runtime (in seconds)
         * Type: Integer
         * Default: 60
         * @since 1.4.4
         * @deprecated DEPRECATED Value! May be removed in future versions!
         **/
        'nagios_uptime_checkinterval' => 60
    ],


    /**
     * Global CPU settings
     **/
    'CPU'         => [
        /**
         * max_threads
         * Maximum number of worker threads
         * Type: Integer
         * Notice: Should always be an even number (e.g.:2, 10, 20)
         * @since 1.0
         **/
        'max_threads'    => {{max_threads}},


        /**
         * multithreading
         * Use multiple threads for higher performance.
         * Type: Boolean
         * true = On (recommended for production environment )
         * false = Off (recommended for child forking debugging)
         * @since 1.0
         **/
        'multithreading' => true,

        /**
         * maxidle
         * Descrips how long a SAT-Worker should idle, befor it starts to sleep
         * The sleep will save some CPU time
         * Type: Integer
         * default: 1000
         * @since 1.7.0
         **/
        'maxidle'        => 1000,

        /**
         * multi_kill
         * Killes all Childs at ones and waits for the returncode.
         * Type: Boolean
         * true = On (recommended for production environment )
         * false = Off (better for child killing process debugging)
         * @since 1.0
         **/
        'multi_kill'     => true,
    ],


    /**
     * Global Gearman settings
     **/
    'GEARMAN'     => [
        /**
         * port
         * TCP port on which the Gearman server on the satellite listens
         * Type: Integer
         * Default: 4730
         * @since 1.0
         **/
        'port'                  => 4730,


        /**
         * timeout
         * Connection timeout milliseconds
         * Type: Integer
         * Default: 10000
         * @since 1.0
         **/
        'timeout'               => 10000,


        /**
         * retry_interval
         * Retry interval in seconds if the GearmanWorker runs in a timeout
         * Type: Integer
         * Default: 5
         * @since 1.0
         **/
        'retry_interval'        => 5,

        /**
         * use_multiple_worker
         * --- EXPERIMENTAL FEATURE --- EXPERIMENTAL FEATURE ---
         * Type: Boolean
         * true = On (Create a new GearmanWorker for each address ($array[] = new GearmanWorker->addServers(127.0.0.1:55112); $array[] = new GearmanWorker->addServers(127.0.0.1:55060)))
         * false = Off (One GearmanWorker handle more addresses (127.0.0.1:55112, 127.0.0.1:55060))
         * @since 1.7.0
         **/
        'use_multiple_worker'   => false,

        /**
         * bulk_transmit
         * Determine if phpNSTAClient will create packages of multiple check results for the master
         * Enable this if you have a high network latency e.g Europe -> USA or Europe -> Asia
         * Type: Boolean
         * Default: true
         * @since 1.9.0
         **/
        'bulk_transmit'         => true,

        /**
         * disable_legacy_queues
         * Disable old queues, that are no longer be used like:
         * PARSE_SERVICE, PARSE_HOST, DO_EVENTS
         * Type: Boolean
         * Default: false
         * @since 1.9.0
         **/
        'disable_legacy_queues' => false,
    ],

    /**
     * Global SSH settings
     **/
    'SSH'         => [
        /****************************************************
         *                      NOTICE                      *
         * If you want to tunnel the connections between    *
         * master and satellite with SSH, you need to       *
         * install supervisor!                              *
         * For more information check the SUPERVISOR section*
         ****************************************************/

        /**
         * use_ssh_tunnel
         * Use SSH to tunnel for each SAT-System
         * Type: Boolean
         * true = On
         * false = Off
         * @since 1.4
         **/
        'use_ssh_tunnel'     => {{use_ssh_tunnel}},

        /**
         * username
         * Username via the SSH connection is established
         * Equal bash command: ssh username@10.10.10.10
         * Type: String
         * Default: nagios
         * @since 1.4
         **/
        'username'           => '{{ssh_username}}',

        /**
         * private_path
         * Path to your ssh private key file
         * Type: String
         * Default: /var/www/.ssh/id_rsa (because of the sync.sh/sync.php from the openITCOCKPIT Interface)
         * @since 1.4
         * @version 1.8.0
         **/
        'private_path'       => '{{private_path}}',

        /**
         * public_path
         * Path to your ssh public key file
         * Type: String
         * Default: /var/www/.ssh/id_rsa.pub (because of the sync.sh/sync.php from the openITCOCKPIT Interface)
         * @since 1.4
         * @version 1.8.0
         **/
        'public_path'        => '{{public_path}}',

        /**
         * port
         * The port on which the ssh connection will be established
         * Type: Integer
         * Default: 22
         * @since 1.4
         **/
        'port'               => {{ssh_port}},

        /**
         * remote_port
         * Port number where the remote Gearman Job Server is listening to
         * Type: Integer
         * Default: 4730
         * @since 1.4
         **/
        'remote_port'        => 4730,

        /**
         * local_bind_address
         * The local ip address where the gearman job server on the remote system listens to.
         *
         * Type: String
         * Default: 127.0.0.1
         * @since 1.4
         **/
        'local_bind_address' => '127.0.0.1',

        /**
         * port_range
         * The local ports where each satellite system gets bind to
         * Type: String
         * Default: 55000-55500
         * @since 1.4
         **/
        'port_range'         => '{{port_range}}',

        /**
         * timeout
         * Timeout for port check in seconds
         * Type: Integer
         * Default: 2
         * @since 1.4
         **/
        'timeout'            => 2,

        /**
         * check_connection
         * Check if it's possible to establish a SSH connection to each satellite on start up
         * Type: Boolean
         * Default: false
         * @since 1.4
         **/
        'check_connection'   => false,

        /**
         * alive_interval
         * Interval in seconds how often phpNSTA will check for dead childs
         * Type: Int
         * Default: 300
         * @since 1.7.1
         **/
        'alive_interval'     => 300,
    ],

    /**
     * Global supervisor settings
     **/
    'SUPERVISOR'  => [
        /***************************************************
         *                      NOTICE                     *
         * If you want to tunnel the connections between   *
         * master and satellite with SSH, you need to      *
         * install supervisor!                             *
         *                                                 *
         * apt-get install supervisor                      *
         * Add the folowing example to your supervisor conf*
         * File: /etc/supervisor/supervisord.conf          *
         *                                                 *
         * [inet_http_server]                              *
         * port = 127.0.0.1:9090                           *
         * username = phpNSTA                              *
         * password = phpNSTAsSecretPassword               *
         *                                                 *
         * SysVini                                         *
         * /etc/init.d/supervisor stop                     *
         * /etc/init.d/supervisor start                    *
         *                                                 *
         * service                                         *
         * service supervisor restart                      *
         *                                                 *
         * SystemD                                         *
         * systemctl restart supervisor                    *
         ***************************************************/

        /**
         * host
         * IP-Address of the supervisor host
         * Type: String
         * Default: 127.0.0.1
         * @since 1.4
         **/
        'host'        => '127.0.0.1',

        /**
         * port
         * Port of the supervisor host
         * Type: Integer
         * Default: 9000
         * @since 1.4
         **/
        'port'        => 9090,

        /**
         * username
         * Username of supervisor's XMLRPC API
         * Type: String
         * @since 1.4
         **/
        'username'    => '{{supervisor_username}}',

        /**
         * password
         * Password of supervisor's XMLRPC API
         * Type: String
         **/
        'password'    => '{{supervisor_password}}',

        /**
         * config_path
         * Path to the supervisor configuration files
         * Type: String
         * ! Only change this value if you know what you are doing!
         **/
        'config_path' => '/etc/supervisor/conf.d/',

        /**
         * suffix
         * Suffix of the supervisor configuration files
         * Type: String
         * ! Only change this value if you know what you are doing!
         **/
        'suffix'      => '.conf',

        /**
         * prefix
         * Prefix of the supervisor configuration files
         * Type: String
         * ! Only change this value if you know what you are doing!
         **/
        'prefix'      => 'oitc_',

        /**
         * sleep
         * Wait n seconds for the Supervisor restart
         * Type: Integer
         * Default: 5
         **/
        'sleep'       => 5,

        /**
         * interval
         * How often the phpNSTA will check the connection state via Supervisor
         * Type: Integer
         * Default: 120
         **/
        'interval'    => 120,
    ],

    /**
     * Global TSYNC (system time synchronization) settings
     **/
    'TSYNC'       => [
        /**
         * synchronize_time
         * Synchronize the system clock to each connected satellite system
         * Type: Boolean
         * true = On (recommended if your satellites don't use NTP)
         * false = Off
         * Default: true
         * @since 1.4
         **/
        'synchronize_time' => {{synchronize_time}},

        /**
         * every
         * Synchronize the time every <value>
         * Values: hour, minute, day
         * Type: String
         * Default: hour
         * @example hour
         * @example day
         * @since 1.4
         **/
        'every'            => '{{tsync_every}}',
    ],

    /**
     * Global MOD_GEARMAN settings
     **/
    'MOD_GEARMAN' => [
        /**
         * encryption
         * If Mod_Gearman data encryption is used or not
         * Values: true, false
         * Type: bool
         * Default: true
         * @since 1.7.6
         **/
        'encryption' => true,

        /**
         * key
         * Password that is uses by Mod_Gearman to encrypt data inside of the gearman job server
         * Type: String
         * Default: should_be_changed
         * @since 1.7.6
         **/
        'key'        => 'should_be_changed',
    ]
];
