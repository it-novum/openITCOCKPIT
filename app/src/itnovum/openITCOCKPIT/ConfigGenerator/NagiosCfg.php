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


class NagiosCfg {

    private $template = '';

    private $default = [
        //Log settings
        'use_syslog'                       => 0,
        'log_notifications'                => 1,
        'log_service_retries'              => 1,
        'log_host_retries'                 => 1,
        'log_event_handlers'               => 1,
        'log_initial_states'               => 0,
        'log_current_states'               => 0,
        'log_external_commands'            => 0,
        'log_passive_checks'               => 0,

        //Check settings
        'max_concurrent_checks'            => 0,

        //Timeout values
        'host_check_timeout'               => 30,
        'service_check_timeout'            => 60,
        'event_handler_timeout'            => 30,
        'notification_timeout'             => 30,

        //Switches
        'enable_notifications'             => 1,
        'enable_event_handlers'            => 1,
        'service_check_timeout_state'      => 'c',

        //Flapping
        'low_host_flap_threshold'          => 5.0,
        'high_host_flap_threshold'         => 20.0,
        'low_service_flap_threshold'       => 5.0,
        'high_service_flap_threshold'      => 20.0,

        //Freshness
        'check_host_freshness'             => 1,
        'host_freshness_check_interval'    => 60,
        'check_service_freshness'          => 1,
        'service_freshness_check_interval' => 60,
        'additional_freshness_latency'     => 15,

        'debug_level'                      => 0,
        'debug_verbosity'                  => 1

    ];

}