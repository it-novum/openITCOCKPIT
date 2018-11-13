<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\ConfigGenerator;


class NagiosCfg extends ConfigGenerator implements ConfigInterface {

    protected $templateDir = 'nagios';

    protected $template = 'nagios.cfg';

    //protected $outfile = '/etc/openitcockpit/nagios.cfg';
    protected $outfile = '/tmp/nagios.cfg';

    /**
     * @var string
     */
    protected $commentChar = '#';

    protected $defaults = [
        'bool' => [
            'use_syslog'            => 0,
            'log_notifications'     => 1,
            'log_service_retries'   => 1,
            'log_host_retries'      => 1,
            'log_event_handlers'    => 1,
            'log_initial_states'    => 0,
            'log_current_states'    => 0,
            'log_external_commands' => 0,
            'log_passive_checks'    => 0,

            'enable_notifications'    => 1,
            'enable_event_handlers'   => 1,
            'check_host_freshness'    => 1,
            'check_service_freshness' => 1,
        ],

        'int' => [
            'max_concurrent_checks' => 0,

            'host_check_timeout'    => 30,
            'service_check_timeout' => 60,
            'event_handler_timeout' => 30,
            'notification_timeout'  => 30,

            'host_freshness_check_interval'    => 60,
            'service_freshness_check_interval' => 60,
            'additional_freshness_latency'     => 15,

            'debug_level'     => 0,
            'debug_verbosity' => 1
        ],

        'string' => [
            'service_check_timeout_state' => 'c',
        ],

        'float' => [
            'low_host_flap_threshold'     => 5.0,
            'high_host_flap_threshold'    => 20.0,
            'low_service_flap_threshold'  => 5.0,
            'high_service_flap_threshold' => 20.0,
        ]
    ];

    protected $dbKey = 'NagiosCfg';

    /**
     * @param array $data
     * @return array|bool|true
     */
    public function customValidationRules($data) {
        $error = [];
        $fakeModelName = 'Configfile';
        if (isset($data['string']) && is_array($data['string'])) {
            foreach ($data['string'] as $field => $value) {
                if ($field === 'service_check_timeout_state') {
                    if (!in_array($value, ['c', 'u', 'w', 'o'], true)) {
                        $error[$fakeModelName][$field][] = __('Value out of range (c, u, w, o)');
                    }
                }
            }
        }

        if (!empty($error)) {
            return $error;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getAngularDirective() {
        return 'nagios-cfg';
    }

    /**
     * @param string $key
     * @return string
     */
    public function getHelpText($key) {
        $help = [
            'use_syslog'            => 'If you want messages logged to the syslog facility, as well as the Naemon/Nagios log file set this option to 1.  If not, set it to 0.',
            'log_notifications'     => 'If you don\'t want notifications to be logged, set this value to 0. If notifications should be logged, set the value to 1.',
            'log_service_retries'   => 'If you don\'t want service check retries to be logged, set this value to 0.  If retries should be logged, set the value to 1.',
            'log_host_retries'      => 'If you don\'t want host check retries to be logged, set this value to 0.  If retries should be logged, set the value to 1.',
            'log_event_handlers'    => 'If you don\'t want host and service event handlers to be logged, set this value to 0.  If event handlers should be logged, set the value to 1.',
            'log_initial_states'    => 'If you want Naemon/Nagios to log all initial host and service states to the main log file (the first time the service or host is checked) you can enable this option by setting this value to 1.  If you are not using an external application that does long term state statistics reporting, you do not need to enable this option.  In this case, set the value to 0.',
            'log_current_states'    => 'If you don\'t want Naemon/Nagios to log all current host and service states after log has been rotated to the main log file, you can disable this option by setting this value to 0. Default value is 1.',
            'log_external_commands' => 'If you don\'t want Naemon/Nagios to log external commands, set this value to 0.  If external commands should be logged, set this value to 1. Note: This option does not include logging of passive service checks - see the option below for controlling whether or not passive checks are logged.',
            'log_passive_checks'    => 'If you don\'t want Naemon/Nagios to log passive host and service checks, set this value to 0.  If passive checks should be logged, set this value to 1.',

            'max_concurrent_checks' => 'This option allows you to specify the maximum number of service checks that can be run in parallel at any given time. Specifying a value of 1 for this variable essentially prevents any service checks from being parallelized.  A value of 0 will not restrict the number of concurrent checks that are being executed.',

            'enable_notifications'    => 'This determines whether or not Naemon/Nagios will sent out any host or service notifications when it is initially (re)started. Values: 1 = enable notifications, 0 = disable notifications',
            'enable_event_handlers'   => 'This determines whether or not Naemon/Nagios will run any host or service event handlers when it is initially (re)started.  Unless you\'re implementing redundant hosts, leave this option enabled. Values: 1 = enable event handlers, 0 = disable event handlers',
            'check_host_freshness'    => 'This option determines whether or not Naemon/Nagios will periodically check the "freshness" of host results.  Enabling this option is useful for ensuring passive checks are received in a timely manner. Values: 1 = enabled freshness checking, 0 = disable freshness checking',
            'check_service_freshness' => 'This option determines whether or not Naemon/Nagios will periodically check the "freshness" of service results.  Enabling this option is useful for ensuring passive checks are received in a timely manner. Values: 1 = enabled freshness checking, 0 = disable freshness checking',


            'host_check_timeout'    => 'These options control how much time Naemon/Nagios will allow various types of commands to execute before killing them off.  Options are available for controlling maximum time allotted for service checks, host checks, event handlers, notifications, the ocsp command, and performance data commands.  All values are in seconds.',
            'service_check_timeout' => 'These options control how much time Naemon/Nagios will allow various types of commands to execute before killing them off.  Options are available for controlling maximum time allotted for service checks, host checks, event handlers, notifications, the ocsp command, and performance data commands.  All values are in seconds.',
            'event_handler_timeout' => 'These options control how much time Naemon/Nagios will allow various types of commands to execute before killing them off.  Options are available for controlling maximum time allotted for service checks, host checks, event handlers, notifications, the ocsp command, and performance data commands.  All values are in seconds.',
            'notification_timeout'  => 'These options control how much time Naemon/Nagios will allow various types of commands to execute before killing them off.  Options are available for controlling maximum time allotted for service checks, host checks, event handlers, notifications, the ocsp command, and performance data commands.  All values are in seconds.',

            'host_freshness_check_interval'    => 'This setting determines how often (in seconds) Naemon/Nagios will check the "freshness" of host check results.  If you have disabled host freshness checking, this option has no effect.',
            'service_freshness_check_interval' => 'This setting determines how often (in seconds) Naemon/Nagios will check the "freshness" of service check results.  If you have disabled service freshness checking, this option has no effect.',
            'additional_freshness_latency'     => 'This setting determines the number of seconds that Naemon/Nagios will add to any host and service freshness thresholds that it calculates (those not explicitly specified by the user).',

            'debug_level'     => 'This option determines how much (if any) debugging information will be written to the debug file. Values: -1 = Everything, 0 = Nothing',
            'debug_verbosity' => 'This option determines how verbose the debug log out will be. Values: 0 = Brief output, 1 = More detailed, 2 = Very detailed',


            'service_check_timeout_state' => 'This setting determines the state Naemon/Nagios will report when a service check times out - that is does not respond within service_check_timeout seconds.  This can be useful if a machine is running at too high a load and you do not want to consider a failed service check to be critical (the default).',


            'low_host_flap_threshold'     => 'FLAP DETECTION THRESHOLDS FOR HOSTS AND SERVICES Read the HTML documentation on flap detection for an explanation of what this option does.  This option has no effect if flap detection is disabled.',
            'high_host_flap_threshold'    => 'FLAP DETECTION THRESHOLDS FOR HOSTS AND SERVICES Read the HTML documentation on flap detection for an explanation of what this option does.  This option has no effect if flap detection is disabled.',
            'low_service_flap_threshold'  => 'FLAP DETECTION THRESHOLDS FOR HOSTS AND SERVICES Read the HTML documentation on flap detection for an explanation of what this option does.  This option has no effect if flap detection is disabled.',
            'high_service_flap_threshold' => 'FLAP DETECTION THRESHOLDS FOR HOSTS AND SERVICES Read the HTML documentation on flap detection for an explanation of what this option does.  This option has no effect if flap detection is disabled.',

        ];

        if (isset($help[$key])) {
            return $help[$key];
        }

        return '';
    }

    /**
     * Save the configuration as text file on disk
     *
     * @param array $dbRecords
     * @return bool|int
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function writeToFile($dbRecords) {
        $config = $this->mergeDbResultWithDefaultConfiguration($dbRecords);
        $configToExport = [];
        foreach ($config as $type => $fields) {
            foreach ($fields as $key => $value) {
                $configToExport[$key] = $value;
            }
        }

        return $this->saveConfigFile($configToExport);
    }

}